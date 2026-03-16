<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Committee;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RolePermissionController extends Controller
{
    /** Role names that cannot be deleted (seeded defaults). */
    public const PROTECTED_ROLE_NAMES = ['admin', 'resident', 'committee_head', 'clerk'];

    public function index()
    {
        $roles = Role::query()
            ->with(['permissions', 'committee'])
            ->orderBy('name')
            ->get();

        $permissionsForRole = [];
        foreach ($roles as $role) {
            $query = Permission::query()
                ->where('guard_name', 'web')
                ->with('committee');
            if ($role->committee_id) {
                $query->where(function ($q) use ($role) {
                    $q->whereNull('committee_id')
                        ->orWhere('committee_id', $role->committee_id);
                });
            } else {
                $query->whereNull('committee_id');
            }
            $permissionsForRole[$role->id] = $query->orderBy('name')->get();
        }

        return view('admin.roles-permissions.index', [
            'roles' => $roles,
            'permissionsForRole' => $permissionsForRole,
            'protectedRoleNames' => self::PROTECTED_ROLE_NAMES,
        ]);
    }

    public function create()
    {
        $committees = Committee::orderBy('name')->get();

        return view('admin.roles-permissions.create', compact('committees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_type' => 'required|in:admin,resident,committee',
            'committee_id' => 'required_if:role_type,committee|nullable|exists:committees,id',
            'role_name' => 'nullable|string|max:255',
        ]);

        $guardName = 'web';
        $customName = isset($validated['role_name']) && trim($validated['role_name']) !== ''
            ? Str::lower(Str::slug(trim($validated['role_name']), '_'))
            : null;

        if ($customName !== null) {
            if (Role::where('name', $customName)->where('guard_name', $guardName)->exists()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['role_name' => 'A role with this name already exists.']);
            }
        }

        if ($validated['role_type'] === 'committee') {
            $committee = Committee::findOrFail($validated['committee_id']);
            $name = $customName ?? ('committee_head_' . $committee->slug);
            $role = Role::create([
                'name' => $name,
                'guard_name' => $guardName,
                'committee_id' => $committee->id,
            ]);
            $role->syncPermissions(['view committee_dashboard']);
        } elseif ($validated['role_type'] === 'admin') {
            $name = $customName ?? ('custom_admin_' . now()->format('YmdHis'));
            $role = Role::create([
                'name' => $name,
                'guard_name' => $guardName,
            ]);
        } else {
            $name = $customName ?? ('custom_resident_' . now()->format('YmdHis'));
            $role = Role::create([
                'name' => $name,
                'guard_name' => $guardName,
            ]);
        }

        return redirect()->route('admin.roles-permissions.index')->with('success', "Role \"{$role->name}\" created successfully.");
    }

    public function update(Request $request, Role $role)
    {
        $permissionIds = collect($request->input('permissions', []))
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        $query = Permission::query()->where('guard_name', 'web');
        if ($role->committee_id) {
            $query->where(function ($q) use ($role) {
                $q->whereNull('committee_id')->orWhere('committee_id', $role->committee_id);
            });
        } else {
            $query->whereNull('committee_id');
        }
        $allowedIds = $query->pluck('id')->all();
        $validPermissionIds = array_values(array_intersect($permissionIds, $allowedIds));

        $role->syncPermissions($validPermissionIds);

        return redirect()->route('admin.roles-permissions.index')->with('success', "Permissions updated for role: {$role->name}.");
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, self::PROTECTED_ROLE_NAMES, true)) {
            return redirect()->route('admin.roles-permissions.index')
                ->with('error', 'Default roles from the seeder cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('admin.roles-permissions.index')
            ->with('success', "Role \"{$role->name}\" deleted successfully.");
    }
}
