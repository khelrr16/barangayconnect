@extends('layouts.resident')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <h2 class="mb-4">Welcome, {{ auth()->user()->name }}</h2>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary shadow-sm rounded-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-0">{{ $householdMembersCount ?? '—' }}</h3>
                            <small>My Household</small>
                        </div>
                        @if($resident)
                            <a href="{{ route('resident.profile') }}" class="text-white opacity-75 text-decoration-none">View profile</a>
                        @else
                            <span class="opacity-75">Link your profile</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning shadow-sm rounded-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-0">{{ $pendingRequestsCount ?? 0 }}</h3>
                            <small>Pending Requests</small>
                        </div>
                        <a href="{{ route('resident.my-requests') }}" class="text-white opacity-75 text-decoration-none">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success shadow-sm rounded-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-0"><i class="fa-solid fa-link"></i></h3>
                            <small>Quick Links</small>
                        </div>
                        <span class="opacity-75">—</span>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($latestAnnouncement) && $latestAnnouncement)
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">Latest Announcement</h4>
                    <h5>{{ $latestAnnouncement->title }}</h5>
                    <p class="text-muted mb-2">{{ Str::limit(strip_tags($latestAnnouncement->body), 150) }}</p>
                    <a href="{{ route('resident.announcements') }}">View all announcements</a>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Quick Links</h4>
                <p class="text-muted mb-3">Get started with barangay services.</p>
                <a href="{{ route('resident.request-document') }}" class="btn btn-primary me-2">
                    <i class="fa-solid fa-file-lines"></i> Request Document
                </a>
                <a href="{{ route('resident.announcements') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-bullhorn"></i> View Announcements
                </a>
            </div>
        </div>
    </div>
@endsection
