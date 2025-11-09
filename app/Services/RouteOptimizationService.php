<?php

namespace App\Services;

use App\Enums\ConstraintType;
use App\Enums\RouteStatus;
use App\Enums\TruckStatus;
use App\Models\DeliveryCompany;
use App\Models\ProductionSite;
use App\Models\Route;
use App\Models\Truck;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RouteOptimizationService
{
    /**
     * Select the best site from the matching ones based on distance/emissions
     */
    private function selectBestSite($matchingSites, DeliveryCompany $company): ?ProductionSite
    {
        return $matchingSites
            ->map(function ($site) use ($company) {
                $distance = $this->estimateDistance($site, $company);
                return [
                    "site" => $site,
                    "distance" => $distance,
                ];
            })
            ->sortBy("distance")

            // Return the site model
            ->first()["site"];
    }

    /**
     * Generate optimal routes based on available data
     */
    public function generateOptimisedRoutes(): array
    {
        //Fetch input data
        $trucks = Truck::where("available_status", TruckStatus::AVAILABLE->value)->get();
        $companies = DeliveryCompany::all();
        $sites = ProductionSite::all();

        if ($trucks->isEmpty() || $companies->isEmpty() || $sites->isEmpty()) {
            return ["error" => "Missing required data to optimise routes"];
        }

        $generatedRoutes = [];

        // Ensure DB consistency
        DB::beginTransaction();

        try {
            foreach ($companies as $company) {

                $matchingSites = $this->findSiteMatchingConstraints($company, $sites);

                if ($matchingSites->isEmpty()) {
                    continue;
                }

                // Choose best site based on distance (or emissions)
                $matchingSite = $this->selectBestSite($matchingSites, $company);


                $requiredCapacity = $company->weekly_min ?? 0;

                $truck = $this->assignBestTruck($trucks, $requiredCapacity);

                // If no trucks can fulfill capacity
                if (!$truck) {
                    continue;
                }

                // Calculate estimated values (distance + emissions)
                $distance = $this->estimateDistance($matchingSite, $company);
                $emissions = $this->estimateEmissions($distance, $truck);
                $co2Delivered = min($requiredCapacity, $truck->co2_capacity);

                // Create the Route entry (the output of the algorithm)
                $route = Route::create([
                    "truck_id" => $truck->id,
                    "production_site_id" => $matchingSite->id,
                    "delivery_company_id" => $company->id,
                    "distance" => $distance,
                    "emissions" => $emissions,
                    "co2_delivered" => $co2Delivered,
                    "status" => RouteStatus::PENDING->value,
                ]);

                // Mark truck as unavailable after assignment
                $truck->update(["available_status" => TruckStatus::IN_TRANSIT->value]);

                $generatedRoutes[] = $route;
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $generatedRoutes;
    }

    /**
     * Match delivery constraints with production site CO₂ source
     */
    private function findSiteMatchingConstraints(DeliveryCompany $company, $sites)
    {
        // Array of constraint values
        $constraints = $company->constraints ?? [];

        // If company says “see credit company constraints”, merge both arrays
        if (
            isset($constraints["delivery_condition"]) &&
            $constraints["delivery_condition"] === ConstraintType::SEE_CREDIT_COMPANY_CONSTRAINTS->value &&
            $company->creditCompany
        ) {
            $constraints = array_merge(
                $constraints,
                $company->creditCompany->constraints ?? []
            );
        }

        /** Map constraints to rule functions */
        $constraintRules = [
            ConstraintType::NONE->value => fn ($site) => true,

            ConstraintType::MUST_BE_DISTILLERY_SOURCE->value =>
                fn ($site) => str_contains(strtolower($site->type), "distillery"),

            ConstraintType::ACCEPTS_CO2_FROM_BIOGAS_NON_MANURE->value =>
                fn ($site) =>
                    str_contains(strtolower($site->type), "biogas")
                    && !str_contains(strtolower($site->type), "manure"),
        ];

        return $sites->filter(function ($site) use ($constraints, $constraintRules) {

            foreach ($constraints as $constraint) {
                if (!isset($constraintRules[$constraint])) {

                    // Ignore unknown constraint values
                    continue;
                }

                $rule = $constraintRules[$constraint];

                // If any constraint fails, site is invalid
                if (!$rule($site)) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * Select the best truck (simplest logic: capacity >= requirement and lowest emissions factor)
     */
    private function assignBestTruck($trucks, float $requiredCapacity): ?Truck
    {
        return $trucks
            ->filter(fn ($truck) => $truck->co2_capacity >= $requiredCapacity)
            ->sortBy("emission_factor")
            ->first();
    }

    protected GeocodingService $geo;

    public function __construct(GeocodingService $geo)
    {
        $this->geo = $geo;
    }

    /**
     * Haversine formula, calculates distance between two lat/lng points in KM
     */
    private function calculateDistance(
        float $lat1, float $lon1,
        float $lat2, float $lon2
    ): float {

        $earthRadius = 6371; // km

        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDiff / 2) * sin($lonDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Estimate distance in km, placeholder logic until real distances used
     */
    private function estimateDistance(ProductionSite $site, DeliveryCompany $company): float
    {
        $siteCoords = Cache::remember("coords_{$site->location}", now()->addDays(30), fn() =>
        $this->geo->geocodePostcode($site->location)
        );

        $companyCoords = Cache::remember("coords_{$company->location}", now()->addDays(30), fn() =>
        $this->geo->geocodePostcode($company->location)
        );

        // If geocoder fails, fallback
        if (!$siteCoords || !$companyCoords) {
            return rand(50, 500);
        }

        return $this->calculateDistance(
            $siteCoords['lat'], $siteCoords['lng'],
            $companyCoords['lat'], $companyCoords['lng']
        );
    }


    /**
     * Calculate CO₂ emissions = distance × truck emission factor
     */
    private function estimateEmissions(float $distance, Truck $truck): float
    {
        return $distance * $truck->emission_factor;
    }
}
