<?php

namespace Database\Seeders;

use App\Enums\TruckStatus;
use App\Models\DeliveryCompany;
use App\Models\ProductionSite;
use App\Models\Truck;
use App\Models\TruckType;
use Illuminate\Database\Seeder;

class TruckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Truck::query()->delete();

        $productionSites = ProductionSite::all();
        $deliveryCompanies = DeliveryCompany::all();
        $truckTypes = TruckType::all();

        // Basic safety check
        if ($productionSites->isEmpty() || $deliveryCompanies->isEmpty() || $truckTypes->isEmpty()) {
            $this->command->warn('Missing dependencies (sites, deliveries, or truck types). Using factories...');
            Truck::factory(6)->create();
            return;
        }

        $heading = true;
        $csv_path = fopen(base_path('/database/data/Trucks.csv'), 'r');

        while (($line = fgetcsv($csv_path, 1000, ",")) !== FALSE)
        {
            if (!$heading)
            {
                $capacity = (int) $line[1];

                // Match truck type by capacity instead of random
                $matchingTruckType = $truckTypes->firstWhere('capacity', $capacity)
                    ?? $truckTypes->random();

                $status = is_numeric($line[2]) ? (int)$line[2] : TruckStatus::AVAILABLE->value;

                $truck = [
                    'truck_plate'      => $line[0],
                    'co2_capacity'     => $capacity,
                    'available_status' => $status,
                    'truck_type_id'    => $matchingTruckType->id,
                ];

                // Only assign site/company when IN_TRANSIT
                if ($status === TruckStatus::IN_TRANSIT->value) {
                    $truck['production_site_id'] = $productionSites->random()->id;
                    $truck['delivery_company_id'] = $deliveryCompanies->random()->id;
                }

                Truck::create($truck);
            }
            $heading = false;
        }
        fclose($csv_path);

        if (Truck::count() === 0) {
            Truck::factory(5)->create();
        }
    }
}