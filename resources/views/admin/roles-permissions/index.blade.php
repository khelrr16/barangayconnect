@extends('layouts.admin')

@section('title', 'Roles Permissions')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4">Roles Permission Management</h2>

        @if($roles->isEmpty())
            <div class="alert alert-info">No roles found in the database.</div>
        @elseif($permissions->isEmpty())
            <div class="alert alert-info">No permissions found in the database.</div>
        @else
            @foreach($roles as $role)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>{{ ucfirst($role->name) }}</strong>
                    </div>
                    <div class="card-body">
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
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
