<?php

namespace App\Services;

use App\Enums\ConstraintType;
use App\Enums\RouteStatus;
use App\Enums\TruckStatus;
use App\Models\DeliveryCompany;
use App\Models\ProductionSite;
use App\Models\Route;
use App\Models\Truck;
use Illuminate\Support\Facades\DB;

class RouteOptimizationService
{
    /**
     * Generate optimal routes based on available data
     */
    public function generateOptimisedRoutes(): array
    {
        //Fetch input data
        $trucks = Truck::where("status", TruckStatus::AVAILABLE->value)->get();
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

                $matchingSite = $this->findSiteMatchingConstraints($company, $sites);

                // If no valid site matches constraints
                if (!$matchingSite) {
                    continue;
                }

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
                $truck->update(["status" => TruckStatus::IN_TRANSIT->value]);

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
        foreach ($sites as $site) {
            if ($company->constraints["delivery_condition"] === ConstraintType::NONE->value ||
                $company->constraints["delivery_condition"] === $site->co2_source)
            {
                return $site;
            }
        }
        return null;
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


    /**
     * Estimate distance in km, placeholder logic until real distances used
     */
    private function estimateDistance(ProductionSite $site, DeliveryCompany $company): int
    {
        return rand(50, 500);
    }


    /**
     * Calculate CO₂ emissions = distance × truck emission factor
     */
    private function estimateEmissions(float $distance, Truck $truck): float
    {
        return $distance * $truck->emission_factor;
    }
}
