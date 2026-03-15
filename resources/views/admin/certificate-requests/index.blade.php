@extends('layouts.admin')

@section('title', 'Certificate Requests')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4">Certificate Requests</h2>
        <p class="text-muted">Document requests submitted by residents (Barangay Clearance, Indigency, Residency, etc.). Review and update status.</p>

        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.certificate-requests.index') }}">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'pending' ? 'active' : '' }}" href="{{ route('admin.certificate-requests.index', ['status' => 'pending']) }}">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'approved' ? 'active' : '' }}" href="{{ route('admin.certificate-requests.index', ['status' => 'approved']) }}">Approved</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'released' ? 'active' : '' }}" href="{{ route('admin.certificate-requests.index', ['status' => 'released']) }}">Released</a>
            </li>
        </ul>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tracking Code</th>
                        <th>Resident</th>
                        <th>Certificate Type</th>
                        <th>Purpose</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $index => $req)
                        <tr>
                            <td>{{ $requests->firstItem() + $index }}</td>
                            <td>{{ $req->tracking_code }}</td>
                            <td>{{ $req->resident->full_name ?? '—' }}<br><small class="text-muted">{{ $req->resident->rbi_no ?? '' }}</small></td>
                            <td>{{ \App\Models\CertificateRequest::TYPES[$req->certificate_type] ?? $req->certificate_type }}</td>
                            <td>{{ Str::limit($req->purpose, 40) }}</td>
                            <td>{{ $req->submitted_at?->format('M j, Y') }}</td>
                            <td>
                                @if($req->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($req->status === 'approved')
                                    <span class="badge bg-info">Approved</span>
                                @else
                                    <span class="badge bg-success">Released</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.certificate-requests.show', $req) }}" class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No certificate requests yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $requests->links() }}
        </div>
    </div>
@endsection
