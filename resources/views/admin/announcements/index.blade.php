@extends('layouts.admin')

@section('title', 'Announcements')

@section('content')
    <div class="container my-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="mb-0">Announcements</h2>
            <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Add Announcement
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Published</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $index => $announcement)
                        <tr>
                            <td>{{ $announcements->firstItem() + $index }}</td>
                            <td>{{ Str::limit($announcement->title, 50) }}</td>
                            <td>{{ $announcement->published_at ? $announcement->published_at->format('M j, Y') : 'Draft' }}</td>
                            <td>{{ $announcement->created_at->format('M j, Y') }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-sm btn-warning">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Delete this announcement?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No announcements yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $announcements->links() }}
        </div>
    </div>
@endsection
