<?php

namespace Database\Seeders;

use App\Enums\RouteStatus;
use App\Models\DeliveryCompany;
use App\Models\ProductionSite;
use App\Models\Route;
use App\Models\Truck;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear old data
        Route::truncate();

        $productionSites = ProductionSite::all();
        $deliveryCompanies = DeliveryCompany::all();
        $trucks = Truck::all();

        // Basic safety check
        if ($productionSites->isEmpty() || $deliveryCompanies->isEmpty() || $trucks->isEmpty()) {
            $this->command->warn('Missing dependencies (sites, deliveries, or trucks). Using factories...');
            Route::factory(5)->create();
            return;
        }

        $currentWeek = now()->weekOfYear;
        $currentYear = now()->year;

        // Create logical routes
        foreach ($productionSites as $site) {
            // Each site delivers to 1–2 companies
            $selectedCompanies = $deliveryCompanies->random(min(2, $deliveryCompanies->count()));

            foreach ($selectedCompanies as $company) {
                // Pick an available truck
                $truck = $trucks->random();

                // Example: distance based on rough simulation of delivery range
                $distance = fake()->numberBetween(50, 300); // km

                // Estimate fuel consumption
                $fuelConsumption = $distance * $truck->truckType->fuel_consumption_per_km;

                // Estimate emissions
                $emissions = round($distance * $truck->truckType->emission_factor, 2);

                // Estimate cost
                $cost = $distance * $truck->truckType->fuel_cost_per_km;

                // Estimate CO₂ delivered based on truck capacity (20–32 tonnes)
                $co2Delivered = min($truck->co2_capacity ?? 32, fake()->randomFloat(2, 15, 32));

                // Calculate estimated duration
                $avgSpeed = 60; // km/h
                $travelTime = ($distance / $avgSpeed) * 60; // minutes
                $estimatedDuration = (int) ceil($travelTime + 60); // Add loading/unloading

                // Create route
                Route::create([
                    'production_site_id' => $site->id,
                    'delivery_company_id' => $company->id,
                    'credit_company_id' => null, // Regular delivery, no credit company
                    'truck_id' => $truck->id,
                    'distance' => $distance,
                    'fuel_consumption' => $fuelConsumption,
                    'emissions' => $emissions,
                    'cost' => $cost,
                    'co2_delivered' => $co2Delivered,
                    'status' => RouteStatus::IN_PROGRESS->value,
                    'is_early_delivery' => false,
                    'scheduled_at' => now()->addDays(fake()->numberBetween(1, 10)),
                    'completed_at' => null,
                    'week_number' => $currentWeek,
                    'year' => $currentYear,
                    'trip_number' => 1,
                    'total_trips' => 1,
                    'estimated_duration_minutes' => $estimatedDuration,
                ]);
            }
        }

        $this->command->info('Routes logically seeded based on production sites, delivery companies, and trucks.');
    }
}
