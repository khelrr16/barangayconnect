<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guardName = 'web';

        $permissions = [
            'view dashboard',
            'manage users',
            'manage residents',
            'manage households',
            'manage healthProfiles',
            'view residents',
            'view households',
            'view healthProfiles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $guardName,
            ]);
        }

        $allPermissions = Permission::query()
            ->where('guard_name', $guardName)
            ->pluck('name');

        // Create roles and assign permissions
        $staffRole = Role::firstOrCreate([
            'name' => 'staff',
            'guard_name' => $guardName,
        ]);

        $staffRole->syncPermissions([
            'view dashboard',
            'manage residents',
            'manage households',
            'manage healthProfiles',
            'view residents',
            'view households',
            'view healthProfiles',
        ]);

        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => $guardName,
        ]);

        $adminRole->syncPermissions($allPermissions);

        $superAdminRole = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => $guardName,
        ]);

        $superAdminRole->syncPermissions($allPermissions);
    }
}
