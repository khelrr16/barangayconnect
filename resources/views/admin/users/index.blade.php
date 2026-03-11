@extends('layouts.admin')

@section('title', 'User Accounts')

@section('content')
    <div class="container my-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="mb-0">User Account Management</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fa-solid fa-circle-plus"></i> Add User
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucwords(str_replace('_', ' ',$user->getRoleNames()->first())) ?: 'No role' }}</td>
                            <td class="d-flex gap-2">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>

                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ addslashes($user->name) }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select selectRole" id="role" name="role" required>
                                <option value="" disabled selected>--</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">
                                        {{ ucwords(str_replace('_', ' ',$role->name)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="official" class="form-label">Officials</label>
                            <select class="form-select committee" id="official" name="official_id" required>
                                <option value="" selected disabled>--</option>
                                @foreach($officials as $official)
                                    <option value="{{ $official->id }}" {{ $user->official_id == $official->id ? 'selected' : '' }}>
                                        {{ $official->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($users as $user)
        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name-{{ $user->id }}" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name-{{ $user->id }}" name="name" value="{{ $user->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="email-{{ $user->id }}" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email-{{ $user->id }}" name="email" value="{{ $user->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="role-{{ $user->id }}" class="form-label">Role</label>
                                <select class="form-select selectRole" id="role-{{ $user->id }}" name="role" required>
                                    <option value="" disabled>--</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ ucwords(str_replace('_', ' ',$role->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="official-{{ $user->id }}" class="form-label">Officials</label>
                                <select class="form-select" id="official-{{ $user->id }}" name="official_id" required>
                                    <option value="" selected disabled>--</option>
                                    @foreach($officials as $official)
                                        <option value="{{ $official->id }}" {{ $user->official_id == $official->id ? 'selected' : '' }}>
                                            {{ $official->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="password-{{ $user->id }}" class="form-label">Password (leave blank to keep current)</label>
                                <input type="password" class="form-control" id="password-{{ $user->id }}" name="password">
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation-{{ $user->id }}" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation-{{ $user->id }}" name="password_confirmation">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let progressInterval = null;

            if (window.DataTable) {
                new window.DataTable('#sortTable', {
                    responsive: true,
                    paging: true,
                    pageLength: 20
                });
            }

            const roleSelects = document.querySelectorAll('.selectRole[name="role"]');
            
            roleSelects.forEach(function(roleSelect) {
                // Find the corresponding committee select (in the same parent container or using a specific selector)
                const container = roleSelect.closest('.mb-3').parentNode;
                const committeeDiv = container.querySelector('.mb-3:has([name="official_id"])');
                const committeeSelect = container.querySelector('[name="official_id"]');
                
                // Initial check
                toggleCommittee(roleSelect, committeeDiv, committeeSelect);
                
                // Add event listener for change
                roleSelect.addEventListener('change', function() {
                    toggleCommittee(this, committeeDiv, committeeSelect);
                });
            });
            
            function toggleCommittee(roleSelect, committeeDiv, committeeSelect) {
                // Check if selected role is "Committee Head"
                if (roleSelect.value === 'Committee Head' || roleSelect.value === 'committee_head') {
                    // Show and enable committee
                    committeeDiv.style.display = 'block';
                    committeeSelect.disabled = false;
                    committeeSelect.required = true;
                } else {
                    // Hide and disable committee
                    committeeDiv.style.display = 'none';
                    committeeSelect.disabled = true;
                    committeeSelect.required = false;
                    committeeSelect.value = ''; // Reset value
                }
            }
        });
    </script>
@endpush