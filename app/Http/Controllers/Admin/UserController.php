<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Official;
use App\Models\Resident;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles', 'resident')->orderBy('name')->get();
        $roles = Role::query()->with('committee')->orderBy('name')->get();
        $officials = Official::with('committee')->orderBy('name')->get();
        $officialsByCommittee = $officials->groupBy('committee_id')->map(fn ($list) => $list->map(fn ($o) => ['id' => $o->id, 'name' => $o->name])->values())->toArray();
        $residents = Resident::orderBy('last_name')->orderBy('first_name')->get();

        return view('admin.users.index', compact('users', 'roles', 'officials', 'officialsByCommittee', 'residents'));
    }

    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        $role = $validated['role'];
        unset($validated['role']);

        $roleModel = Role::where('name', $role)->first();
        $isCommitteeRole = $roleModel && $roleModel->isCommitteeRole();
        if (!$isCommitteeRole) {
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
        $roleModel = Role::where('name', $role)->first();
        $isCommitteeRole = $roleModel && $roleModel->isCommitteeRole();
        if (!$isCommitteeRole) {
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
