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
        // Create permissions
        foreach (PermissionType::cases() as $permission) {
            Permission::firstOrCreate(['name' => $permission->value]);
        }

        // Create roles
        $admin = Role::firstOrCreate(['name' => RoleType::ADMIN->value]);
        $ops = Role::firstOrCreate(['name' => RoleType::OPERATIONS_MANAGER->value]);
        $analyst = Role::firstOrCreate(['name' => RoleType::DATA_ANALYST->value]);

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
