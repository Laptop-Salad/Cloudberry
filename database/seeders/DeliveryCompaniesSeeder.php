<?php

namespace Database\Seeders;

use App\Models\DeliveryCompanies;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryCompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeliveryCompanies::truncate();
        $heading = true;
        $csv_path = fopen(base_path('/database/data/DeliveryCompanies.csv'), 'r');
        while (($line = fgetcsv($csv_path, 1000, ",")) !== FALSE)
        {
            if(!$heading)
            {
                $delivery_companies = array(
                    'co2_delivery_obligations' => $line[0],
                    'location' => $line[1],
                    'type' => $line[2],
                    'cod' => $line[3],
                    'annual_min_obligation' => $line[4],
                    'annual_max_obligation' => $line[5],
                    'weekly_min' => $line[6],
                    'weekly_max' => $line[7],
                    'buffer_tank_size' => $line[8],
                    'constraints' => $line[9],
                );
                DeliveryCompanies::create($delivery_companies);
            }
            $heading = false;
        }
        fclose($csv_path);
    }
}
