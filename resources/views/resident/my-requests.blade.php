@extends('layouts.resident')

@section('title', 'My Requests')

@section('content')
    <div class="container">
        <h2 class="mb-4">My Requests</h2>

        @if(!$resident)
            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-3">Link your profile to see your requests.</p>
                    <a href="{{ route('resident.profile') }}" class="btn btn-primary">My Profile</a>
                </div>
            </div>
        @elseif($requests->isEmpty())
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-3">You have no requests yet.</p>
                    <a href="{{ route('resident.request-document') }}" class="btn btn-primary">
                        <i class="fa-solid fa-file-lines"></i> Request a Document
                    </a>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tracking Code</th>
                                    <th>Certificate Type</th>
                                    <th>Purpose</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $req)
                                    <tr>
                                        <td>{{ $req->tracking_code }}</td>
                                        <td>{{ \App\Models\CertificateRequest::TYPES[$req->certificate_type] ?? $req->certificate_type }}</td>
                                        <td>{{ Str::limit($req->purpose, 40) }}</td>
                                        <td><span class="badge bg-secondary">{{ ucfirst($req->status) }}</span></td>
                                        <td>{{ $req->submitted_at?->format('M j, Y g:i A') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('resident.request-document') }}" class="btn btn-primary mt-3">
                        <i class="fa-solid fa-file-lines"></i> Request Another Document
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
