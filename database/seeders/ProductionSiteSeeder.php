<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductionSite;

class ProductionSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductionSite::truncate();
        $heading = true;
        $csv_path = fopen(base_path('/database/data/ProductionSites.csv'), 'r');
        while (($line = fgetcsv($csv_path, 1000, ",")) !== FALSE)
        {
            if(!$heading)
            {
                $production_site = array(
                    'co2_production_sources' => $line[0],
                    'location' => $line[1],
                    'type' => $line[2],
                    'system_operating_status' => $line[3],
                    'annual_production' => $line[4],
                    'weekly_production' => $line[5],
                    'shutdown_periods' => $line[6],
                    'buffer_tank_size' =>  $line[7],
                );
                ProductionSite::create($production_site);
            }
            $heading = false;
        }
        fclose($csv_path);

        if (ProductionSite::count() === 0) {
            ProductionSite::factory(5)->create();
        }
    }
}
