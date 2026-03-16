<?php

namespace Database\Seeders;

use App\Models\Committee;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
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
                'committee_id' => null,
            ]);
        }

        // Committee-specific permissions (only show for that committee's roles in roles-permissions UI)
        $budgetFinanceCommittee = Committee::where('slug', 'budget_finance')->first();
        if ($budgetFinanceCommittee) {
            $budgetFinancePermissions = [
                'view budget_finance_budget',
                'view budget_finance_disbursements',
                'view budget_finance_fund_sources',
                'view budget_finance_reports',
            ];
            foreach ($budgetFinancePermissions as $perm) {
                Permission::firstOrCreate([
                    'name' => $perm,
                    'guard_name' => $guardName,
                    'committee_id' => $budgetFinanceCommittee->id,
                ]);
            }
        }

        $allPermissions = Permission::query()
            ->whereNull('committee_id')
            ->where('guard_name', $guardName)
            ->pluck('name');

        $residentRole = Role::firstOrCreate([
            'name' => 'resident',
            'guard_name' => $guardName,
        ]);


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

        $assistantRole = Role::firstOrCreate([
            'name' => 'assistant',
            'guard_name' => $guardName,
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

        Role::firstOrCreate([
            'name' => 'assistant',
            'guard_name' => $guardName,
        ]);

        Role::firstOrCreate([
            'name' => 'resident',
            'guard_name' => $guardName,
        ]);
    }
}
