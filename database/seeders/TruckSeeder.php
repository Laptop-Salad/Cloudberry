<?php

namespace Database\Seeders;

use App\Models\Truck;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TruckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Truck::truncate();
        $heading = true;
        $csv_path = fopen(base_path('/database/data/Trucks.csv'), 'r');
        while (($line = fgetcsv($csv_path, 1000, ",")) !== FALSE)
        {
            if(!$heading)
            {
                $truck = array(
                    'truck_plate' => $line[0],
                    'co2_capacity' => $line[1],
                    'available_status' => $line[2],
                );
                Truck::create($truck);
            }
            $heading = false;
        }
        fclose($csv_path);
    }
}