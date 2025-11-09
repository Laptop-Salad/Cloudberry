<?php

namespace Database\Seeders;

use App\Models\TruckType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call seeders here
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
        ]);

        TruckType::factory(3)->create();

        // Call seeders here
        $this->call([
            ProductionSiteSeeder::class,
            CreditCompanySeeder::class,
            DeliveryCompanySeeder::class,
            TruckSeeder::class,
            RouteSeeder::class,
        ]);
    }
}
