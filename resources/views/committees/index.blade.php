@extends('layouts.admin')

@section('title', 'Committees')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-3">Committees</h4>
        </div>
        
        <div class="card shadow-sm card-custom">
            @if($committees->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" id="sortTable">
                    <thead class="text-center">
                        <tr>
                            <th>NO</th>
                            <th>NAME</th>
                            <th>DESCRIPTION</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($committees as $index => $committee)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $committee->name }}</td>
                                <td>{{ $committee->description ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2 text-center">
                                        <button type="button" class="btn btn-primarybtn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $committee->id }}">
                                            EDIT
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="card-body text-center text-muted">
                No results.
            </div>
            @endif
        </div>
    </div>

    @foreach($committees as $committee)
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal{{ $committee->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $committee->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.committee.update', $committee->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="editModalLabel{{ $committee->id }}">EDIT COMMITTEE</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <small class="text-muted">NAME</small>
                                <input required type="text" name="name" class="form-control fw-bold" value="{{ $committee->name }}">
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">DESCRIPTION</small>
                                <textarea name="description" class="form-control fw-bold" rows="3">{{ $committee->description }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.DataTable) {
                new window.DataTable('#sortTable', {
                    responsive: true,
                    paging: true,
                    pageLength: 20
                });
            }
        });
    </script>
@endpush
