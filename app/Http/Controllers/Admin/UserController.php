<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Official;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles', 'resident')->orderBy('name')->get();
        $roles = Role::query()->orderBy('name')->get();
        $officials = Official::all();
        $residents = Resident::orderBy('last_name')->orderBy('first_name')->get();

        return view('admin.users.index', compact('users', 'roles', 'officials', 'residents'));
    }

    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        $role = $validated['role'];
        unset($validated['role']);

        if ($role !== 'committee_head') {
            $validated['official_id'] = null;
        }
        $validated['resident_id'] = !empty($validated['resident_id']) ? $validated['resident_id'] : null;

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
        if ($role !== 'committee_head') {
            $validated['official_id'] = null;
        }
        $validated['resident_id'] = !empty($validated['resident_id']) ? $validated['resident_id'] : null;

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
