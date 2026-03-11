<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Official;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->orderBy('name')->get();
        $roles = Role::query()->orderBy('name')->get();
        $officials = Official::all();

        return view('admin.users.index', compact('users', 'roles', 'officials'));
    }

    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        $role = $validated['role'];
        unset($validated['role']);

        $user = User::create($validated);
        $user->syncRoles([$role]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function update(UserRequest $request, $id)
    {
        $validated = $request->validated();
        $user = User::findOrFail($id);

        $role = $validated['role'];
        unset($validated['role']);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);
        $user->syncRoles([$role]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
