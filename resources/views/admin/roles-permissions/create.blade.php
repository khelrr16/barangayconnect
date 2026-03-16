@extends('layouts.admin')

@section('title', 'Create Role')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4">Create New Role</h2>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.roles-permissions.store') }}" method="POST" id="createRoleForm">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold">What role to create?</label>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="role_type" id="role_type_admin" value="admin" required>
                                <label class="form-check-label" for="role_type_admin">Admin</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="role_type" id="role_type_resident" value="resident">
                                <label class="form-check-label" for="role_type_resident">Resident</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="role_type" id="role_type_committee" value="committee">
                                <label class="form-check-label" for="role_type_committee">Committee</label>
                            </div>
                        </div>
                        @error('role_type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4" id="committeeSelection" style="display: none;">
                        <label for="committee_id" class="form-label fw-bold">Which committee?</label>
                        <select class="form-select @error('committee_id') is-invalid @enderror" name="committee_id" id="committee_id">
                            <option value="">-- Select Committee --</option>
                            @foreach($committees as $committee)
                                <option value="{{ $committee->id }}" @selected(old('committee_id') == $committee->id)>
                                    {{ $committee->name }} ({{ $committee->slug }})
                                </option>
                            @endforeach
                        </select>
                        @error('committee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="role_name" class="form-label fw-bold">Role Name</label>
                        <input type="text" class="form-control @error('role_name') is-invalid @enderror" id="role_name" name="role_name" value="{{ old('role_name') }}" placeholder="e.g. finance_officer, custom_admin (optional – leave blank to auto-generate)">
                        <small class="text-muted">Optional. Use lowercase letters, numbers, and underscores. Leave blank to auto-generate from type/committee.</small>
                        @error('role_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Create Role</button>
                        <a href="{{ route('admin.roles-permissions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('createRoleForm');
            const committeeSelection = document.getElementById('committeeSelection');
            const committeeSelect = document.getElementById('committee_id');
            const roleTypeInputs = form.querySelectorAll('input[name="role_type"]');

            function toggleCommitteeSelection() {
                const isCommittee = form.querySelector('input[name="role_type"]:checked')?.value === 'committee';
                committeeSelection.style.display = isCommittee ? 'block' : 'none';
                committeeSelect.required = isCommittee;
                if (!isCommittee) committeeSelect.value = '';
            }

            roleTypeInputs.forEach(function (input) {
                input.addEventListener('change', toggleCommitteeSelection);
            });

            toggleCommitteeSelection();
        });
    </script>
    @endpush
@endsection
