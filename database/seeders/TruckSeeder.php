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
        Truck::truncate();

        $productionSites = ProductionSite::all();
        $deliveryCompanies = DeliveryCompany::all();
        $truckTypes = TruckType::all();

        // Basic safety check
        if ($productionSites->isEmpty() || $deliveryCompanies->isEmpty() || $truckTypes->isEmpty()) {
            $this->command->warn('Missing dependencies (sites, deliveries, or truck types). Using factories...');
            Truck::factory(5)->create();
            return;
        }

        $heading = true;
        $csv_path = fopen(base_path('/database/data/Trucks.csv'), 'r');

        while (($line = fgetcsv($csv_path, 1000, ",")) !== FALSE)
        {
            if(!$heading)
            {
                $truck = array(
                    'truck_plate' => $line[0],
                    'co2_capacity' => $line[1],
                    'available_status' => is_numeric($line[2]) ? (int)$line[2] : TruckStatus::AVAILABLE->value,
                    'truck_type_id' => $truckTypes->random()->id,
                    'production_site_id' => $productionSites->random()->id,
                    'delivery_company_id' => $deliveryCompanies->random()->id,
                );
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