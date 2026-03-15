@extends('layouts.admin')

@section('title', 'Link Verification Requests')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4">Link Verification Requests</h2>
        <p class="text-muted">Residents who are not yet linked can submit a verification ID (e.g. Philippine National ID). Review and link their account to a resident record.</p>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($verifications as $index => $verification)
                        <tr>
                            <td>{{ $verifications->firstItem() + $index }}</td>
                            <td>{{ $verification->user->name }}</td>
                            <td>{{ $verification->user->email }}</td>
                            <td>{{ $verification->created_at->format('M j, Y g:i A') }}</td>
                            <td>
                                @if($verification->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($verification->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.verification-requests.show', $verification) }}" class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                                @if($verification->status === 'pending')
                                    <a href="{{ route('admin.verification-requests.show', $verification) }}" class="btn btn-sm btn-success">
                                        <i class="fa-solid fa-link"></i> Link
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No verification requests yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $verifications->links() }}
        </div>
    </div>
@endsection
