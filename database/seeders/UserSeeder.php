<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure roles exist
        $adminRole = Role::where('name', 'Admin')->first();
        $opsRole = Role::where('name', 'Operations Manager')->first();
        $analystRole = Role::where('name', 'Data Analyst')->first();

        // Create test users and assign roles
        $admin = User::firstOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole($adminRole);

        $ops = User::firstOrCreate(
            ['email' => 'ops@email.com'],
            [
                'name' => 'Operations Manager',
                'password' => Hash::make('password'),
            ]
        );
        $ops->assignRole($opsRole);

        $analyst = User::firstOrCreate(
            ['email' => 'analyst@email.com'],
            [
                'name' => 'Data Analyst',
                'password' => Hash::make('password'),
            ]
        );
        $analyst->assignRole($analystRole);
    }
}
