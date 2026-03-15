<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guardName = 'web';

        $permissions = [
            'view admin_dashboard',
            'manage users',
            'manage user_permissions',

            'view rbi',
            'manage rbi',

            'view officials',
            'manage officials',

            'view clerk_dashboard',

            'view committees',
            'manage committees',

            'view committee_dashboard',

            'view committee_projects',
            'manage committee_projects',

            'view committee_reports',
            'manage committee_reports',

            'view committee_anouncements',
            'manage committee_anouncements',
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
        $committeeHeadRole = Role::firstOrCreate([
            'name' => 'committee_head',
            'guard_name' => $guardName,
        ]);

        $committeeHeadRole->syncPermissions([
            'view committee_dashboard',
            'view committee_projects',
            'manage committee_projects',
            'view committee_reports',
            'manage committee_reports',
            'view committee_anouncements',
            'manage committee_anouncements',
        ]);

        $clerkRole = Role::firstOrCreate([
            'name' => 'clerk',
            'guard_name' => $guardName,
        ]);

        $clerkRole->syncPermissions([
            'view admin_dashboard',
            'view committee_dashboard',
            'view clerk_dashboard',
        ]);

        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => $guardName,
        ]);

        $adminRole->syncPermissions($allPermissions);
    }
}
