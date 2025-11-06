<?php

namespace App\Services;

use App\Models\DeliveryCompany;
use App\Models\ProductionSite;
use App\Models\Route;
use App\Models\Truck;
use Illuminate\Support\Facades\DB;

class RouteOptimisationService
{
    /**
     * Generate optimal routes based on available data.
     */
    public function generateOptimisedRoutes(): array
    {
        //Fetch input data
        $trucks = Truck::where("status", "available")->get();
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

                $truck = $this->assignBestTruck($trucks, $company->required_capacity);

                // If no trucks can fulfill capacity
                if (!$truck) {
                    continue;
                }

                // Calculate estimated values (distance + emissions)
                $distance = $this->estimateDistance($matchingSite, $company);
                $emissions = $this->estimateEmissions($distance, $truck);
                $co2Delivered = min($company->required_capacity, $truck->capacity);

                // Create the Route entry (the output of the algorithm)
                $route = Route::create([
                    "truck_id" => $truck->id,
                    "production_site_id" => $matchingSite->id,
                    "delivery_company_id" => $company->id,
                    "distance" => $distance,
                    "emissions" => $emissions,
                    "co2_delivered" => $co2Delivered,
                    "status" => "PENDING",
                ]);

                // Mark truck as unavailable after assignment
                $truck->update(["status" => "unavailable"]);

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
     * Match delivery constraints with production site CO₂ source.
     */
    private function findSiteMatchingConstraints(DeliveryCompany $company, $sites)
    {
        foreach ($sites as $site) {
            if ($company->constraints["delivery_condition"] === "NONE" ||
                $company->constraints["delivery_condition"] === $site->co2_source)
            {
                return $site;
            }
        }
        return null;
    }


    /**
     * Select the best truck (simplest logic: capacity >= requirement and lowest emissions factor).
     */
    private function assignBestTruck($trucks, float $requiredCapacity): ?Truck
    {
        return $trucks
            ->filter(fn ($truck) => $truck->capacity >= $requiredCapacity)
            ->sortBy("emission_factor")
            ->first();
    }


    /**
     * Estimate distance in km — placeholder logic until real distances used.
     */
    private function estimateDistance(ProductionSite $site, DeliveryCompany $company): int
    {
        return rand(50, 500); // Replace with Haversine / API later
    }


    /**
     * Calculate CO₂ emissions = distance × truck emission factor
     */
    private function estimateEmissions(float $distance, Truck $truck): float
    {
        return $distance * $truck->emission_factor;
    }
}
