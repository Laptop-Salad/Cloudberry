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
        // Only create if not already seeded
        if (TruckType::count() === 0) {
            TruckType::factory()->twentyTonne()->create();
            TruckType::factory()->thirtyTwoTonne()->create();
        }
    }
}
