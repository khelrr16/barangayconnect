@extends('layouts.admin')

@section('title', 'Roles Permissions')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Roles Permission Management</h2>
            <a href="{{ route('admin.roles-permissions.create') }}" class="btn btn-primary">Create Role</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($roles->isEmpty())
            <div class="alert alert-info">No roles found in the database.</div>
        @else
            @foreach($roles as $role)
                @php $permissions = $permissionsForRole[$role->id] ?? collect(); @endphp
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>{{ ucfirst(str_replace('_', ' ', $role->name)) }}</strong>
                        <div class="d-flex align-items-center gap-2">
                            @if($role->committee_id && $role->committee)
                                <span class="badge bg-info">{{ $role->committee->name }}</span>
                            @endif
                            @if(!in_array($role->name, $protectedRoleNames, true))
                                <form action="{{ route('admin.roles-permissions.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete role \"{{ addslashes($role->name) }}\"? Users with this role will lose access.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if($permissions->isEmpty())
                            <p class="text-muted mb-0">No permissions available for this role.</p>
                        @else
                            <form action="{{ route('admin.roles-permissions.update', $role->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="row g-2">
                                    @foreach($permissions as $permission)
                                        <div class="col-md-4 col-sm-6">
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="permissions[]"
                                                    id="role-{{ $role->id }}-permission-{{ $permission->id }}"
                                                    value="{{ $permission->id }}"
                                                    {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}
                                                >
                                                <label class="form-check-label" for="role-{{ $role->id }}-permission-{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Save Permissions</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
