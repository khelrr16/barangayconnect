<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::query()
            ->with('permissions')
            ->orderBy('name')
            ->get();

        $permissions = Permission::query()
            ->orderBy('name')
            ->get();

        return view('admin.roles-permissions.index', compact('roles', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $permissionIds = collect($request->input('permissions', []))
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        $validPermissionIds = Permission::query()
            ->whereIn('id', $permissionIds)
            ->pluck('id')
            ->all();

        $role->syncPermissions($validPermissionIds);

        return redirect()->route('admin.roles-permissions.index')->with('success', "Permissions updated for role: {$role->name}.");
    }
}
