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

        // Create logical routes
        foreach ($productionSites as $site) {
            // Each site delivers to 1–2 companies
            $selectedCompanies = $deliveryCompanies->random(min(2, $deliveryCompanies->count()));

            foreach ($selectedCompanies as $company) {
                // Pick an available truck
                $truck = $trucks->random();

                // Example: distance based on rough simulation of delivery range
                $distance = fake()->numberBetween(50, 300); // km

                // Estimate emissions: assume 0.9 kg/km average
                $emissions = round($distance * 0.9, 2);

                // Estimate CO₂ delivered based on truck capacity (20–32 tonnes)
                $co2Delivered = min($truck->co2_capacity ?? 32, fake()->randomFloat(2, 15, 32));

                // Create route
                Route::create([
                    'production_site_id' => $site->id,
                    'delivery_company_id' => $company->id,
                    'truck_id' => $truck->id,
                    'distance' => $distance,
                    'emissions' => $emissions,
                    'co2_delivered' => $co2Delivered,
                    'status' => RouteStatus::IN_PROGRESS->value,
                    'scheduled_at' => now()->addDays(fake()->numberBetween(1, 10)),
                    'completed_at' => null,
                ]);
            }
        }

        $this->command->info('Routes logically seeded based on production sites, delivery companies, and trucks.');
    }
}
