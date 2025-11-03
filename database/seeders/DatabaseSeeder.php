<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call other seeders here
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            CreditCompanySeeder::class,
            DeliveryCompanySeeder::class,
            ProductionSiteSeeder::class,
            TruckTypeSeeder::class,
            TruckSeeder::class,
        ]);
    }
}
