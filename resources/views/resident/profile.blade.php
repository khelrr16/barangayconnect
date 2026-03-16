@extends('layouts.resident')

@section('title', 'My Profile')

@section('content')
    <div class="container">
        <h2 class="mb-4">My Profile</h2>

        @if($resident)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Resident Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>RBI No:</strong> {{ $resident->rbi_no }}</p>
                            <p><strong>Full Name:</strong> {{ $resident->full_name }}</p>
                            <p><strong>Contact:</strong> {{ $resident->contact_number }}</p>
                            <p><strong>Email:</strong> {{ $resident->email }}</p>
                            <p><strong>Civil Status:</strong> {{ $resident->civil_status }}</p>
                            <p><strong>Occupation:</strong> {{ $resident->occupation }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Sex:</strong> {{ $resident->sex }}</p>
                            <p><strong>Birthday:</strong> {{ $resident->birthday?->format('F j, Y') }}</p>
                            <p><strong>Registered Voter:</strong> {{ $resident->registered_voter }}</p>
                            <p><strong>Residence Since:</strong> {{ $resident->length_of_stay }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($household)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Household Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Household No:</strong> {{ $household->household_no }}</p>
                        <p><strong>Address:</strong> {{ $household->first_address ?? $household->blk_lot_unit }}, {{ $household->street ?? '' }}, {{ $household->subdivision ?? '' }}</p>
                        <p><strong>Household Head:</strong> {{ $household->household_head }}</p>
                        <p><strong>Members:</strong> {{ $household->residents()->count() }}</p>
                    </div>
                </div>
            @endif
        @else
            <div class="card mb-4">
                <div class="card-body">
                    <p class="text-muted">Your account is not linked to a resident record. Upload a valid ID (e.g. Philippine National ID) so we can verify and link your profile.</p>
                    <p class="mb-0"><strong>Account:</strong> {{ auth()->user()->name }} &mdash; {{ auth()->user()->email }}</p>
                </div>
            </div>

            @if($pendingVerification)
                <div class="card border-warning">
                    <div class="card-body">
                        <p class="mb-0 text-success"><i class="fa-solid fa-circle-check"></i> You've sent a verification ID. Please wait for the admin to review and link your profile.</p>
                        <p class="text-muted small mt-2 mb-0">Submitted on {{ $pendingVerification->created_at->format('F j, Y g:i A') }}</p>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Submit Verification ID</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="badge bg-danger fw-bold">REJECTED</span>
                            <div>
                                {{ $pendingVerification->remarks ?? 'Your previous verification ID was rejected by the admin.' }}
                            </div>
                        </div>

                        <p class="text-muted small mb-3">Upload a clear photo or scan of your Philippine National ID or any valid government-issued ID. Accepted: JPEG, PNG, PDF (max 5MB).</p>
                        <form action="{{ route('resident.profile.send-verification') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="verification_id" class="form-label">Verification ID (e.g. Philippine National ID) <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('verification_id') is-invalid @enderror" id="verification_id" name="verification_id" accept=".jpg,.jpeg,.png,.pdf" required>
                                @error('verification_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Send Verification ID</button>
                        </form>

                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection
