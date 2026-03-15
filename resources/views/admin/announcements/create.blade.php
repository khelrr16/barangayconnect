@extends('layouts.admin')

@section('title', 'Add Announcement')

@section('content')
    <div class="container my-5">
        <h2 class="mb-4">Add Announcement</h2>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.announcements.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="body" class="form-label">Body <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="6" required>{{ old('body') }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="published_at" class="form-label">Publish at (leave empty for draft)</label>
                        <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at') }}">
                        @error('published_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Create Announcement</button>
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
