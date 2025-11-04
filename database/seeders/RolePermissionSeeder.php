<?php

namespace Database\Seeders;

use App\Enums\PermissionType;
use App\Enums\RoleType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Set guard name
        $guard = 'web';

        // Create permissions
        foreach (PermissionType::cases() as $permission) {
            Permission::firstOrCreate([
                'name' => $permission->value,
                'guard_name' => $guard,
            ]);
        }

        // Create roles
        $admin = Role::firstOrCreate(['name' => RoleType::ADMIN->value, 'guard_name' => $guard]);
        $ops = Role::firstOrCreate(['name' => RoleType::OPERATIONS_MANAGER->value, 'guard_name' => $guard]);
        $analyst = Role::firstOrCreate(['name' => RoleType::DATA_ANALYST->value, 'guard_name' => $guard]);

        // Assign permissions to roles
        $admin->givePermissionTo(Permission::all());

        $ops->givePermissionTo([
            PermissionType::MANAGE_DELIVERIES->value,
            PermissionType::VIEW_DELIVERIES->value,
            PermissionType::MANAGE_PRODUCTION_SITES->value,
            PermissionType::VIEW_PRODUCTION_SITES->value,
            PermissionType::VIEW_ANALYTICS->value,
        ]);

        $analyst->givePermissionTo([
            PermissionType::VIEW_ANALYTICS->value,
            PermissionType::EXPORT_DATA->value,
        ]);
    }
}
