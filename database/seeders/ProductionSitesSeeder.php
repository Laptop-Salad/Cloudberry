<?php

namespace Database\Seeders;

use App\Models\ProductionSites;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductionSitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductionSites::truncate();
        $heading = true;
        $csv_path = fopen(base_path('/database/data/ProductionSites.csv'), 'r');
        while (($line = fgetcsv($csv_path, 1000, ",")) !== FALSE)
        {
            if(!$heading)
            {
                $production_sites = array(
                    'co2_production_sources' => $line[0],
                    'location' => $line[1],
                    'type' => $line[2],
                    'system_operating_status' => $line[3],
                    'annual_production' => $line[4],
                    'weekly_production' => $line[5],
                    'shutdown_periods' => $line[6],
                    'buffer_tank_size' =>  $line[7],
                );
                ProductionSites::create($production_sites);
            }
            $heading = false;
        }
        fclose($csv_path);
    }
}
