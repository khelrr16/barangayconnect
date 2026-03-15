@extends('layouts.resident')

@section('title', 'Announcements')

@section('content')
    <div class="container">
        <h2 class="mb-4">Announcements</h2>

        @if($announcements->isEmpty())
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fa-solid fa-bullhorn fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">No announcements at this time.</p>
                </div>
            </div>
        @else
            @foreach($announcements as $announcement)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $announcement->title }}</h5>
                        <p class="text-muted small mb-2">{{ $announcement->published_at?->format('F j, Y') }}</p>
                        <p class="card-text">{{ nl2br(e($announcement->body)) }}</p>
                    </div>
                </div>
            @endforeach
            <div class="d-flex justify-content-center">
                {{ $announcements->links() }}
            </div>
        @endif
    </div>
@endsection
