@extends('layouts.committee')

@section('title', 'Permissions')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-0">Permissions</h4>
                <small class="text-muted">Grant assistant access for committee: {{ strtoupper($committeeSlug) }}</small>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <form method="POST" action="{{ route('committee.permissions.store') }}" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-md-8">
                        <label for="assistant_user_id" class="form-label">Assistant</label>
                        <select id="assistant_user_id" name="assistant_user_id" class="form-select @error('assistant_user_id') is-invalid @enderror" required>
                            <option value="">Select assistant</option>
                            @foreach($assistants as $assistant)
                                <option value="{{ $assistant->id }}">{{ $assistant->name }} ({{ $assistant->email }})</option>
                            @endforeach
                        </select>
                        @error('assistant_user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($assistants->isEmpty())
                            <div class="form-text text-muted">No users with role "assistant" found.</div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Grant Access</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Assigned Assistants</h6>

                @if($assignments->isEmpty())
                    <div class="text-muted">No assistants assigned yet.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th class="text-center">Committee</th>
                                    <th class="text-center">Granted</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $assignment)
                                    <tr>
                                        <td>{{ $assignment->assistant?->name ?? 'N/A' }}</td>
                                        <td>{{ $assignment->assistant?->email ?? 'N/A' }}</td>
                                        <td class="text-center"><span class="badge bg-secondary">{{ strtoupper($assignment->committee_slug) }}</span></td>
                                        <td class="text-center">{{ $assignment->created_at?->format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            <form method="POST" action="{{ route('committee.permissions.destroy', $assignment->id) }}" onsubmit="return confirm('Revoke this assistant\'s access?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Revoke</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
