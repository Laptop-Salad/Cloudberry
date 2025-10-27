<?php

namespace Database\Seeders;

use App\Models\TruckType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TruckTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TruckType::truncate();
        TruckType::insert([
            ['capacity'=>20, 'count_available'=>2],
            ['capacity'=>32, 'count_available'=>3],
        ]);
    }
}
