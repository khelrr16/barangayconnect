@extends('layouts.admin')

@section('title', 'Officials')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-3">Officials</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                + NEW
            </button>
        </div>
        
        <div class="card shadow-sm card-custom">
            @if($officials->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" id="sortTable">
                    <thead class="text-center">
                        <tr>
                            <th>NO</th>
                            <th>NAME</th>
                            <th>POSITION</th>
                            <th>COMMITTEE</th>
                            <th>TERM START</th>
                            <th>TERM END</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($officials as $index => $official)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $official->name }}</td>
                                <td>{{ $official->position}}</td>
                                <td>{{ $official->committee->name ?? 'N/A' }}</td>
                                <td>{{ $official->term_start->format('M d, Y') }}</td>
                                <td>{{ $official->term_end->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2 text-center">
                                        <button type="button" class="btn btn-primarybtn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $official->id }}">
                                            EDIT
                                        </button>

                                        <form action="{{ route('admin.official.destroy', $official->id) }}" method="POST" onsubmit="return confirm('Confirm Delete?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">DELETE</button>
                                        </form>
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

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.official.store') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="createModalLabel">NEW OFFICIAL</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <small class="text-muted">NAME</small>
                            <input required type="text" name="name" class="form-control fw-bold">
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">POSITION</small>
                            <input required type="text" name="position" class="form-control fw-bold">
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">COMMITTEE</small>
                            <select name="committee_id" class="form-select mb-3 fw-bold" required>
                                <option value="" selected disabled>SELECT COMMITTEE</option>
                                @foreach($committees as $committee)
                                    <option value="{{ $committee->id }}">{{ $committee->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">TERM START</small>
                            <input required type="date" name="term_start" class="form-control fw-bold">
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">TERM END</small>
                            <input required type="date" name="term_end" class="form-control fw-bold">
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

    @foreach($officials as $official)
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal{{ $official->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $official->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.official.update', $official->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="editModalLabel{{ $official->id }}">EDIT OFFICIALS</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <small class="text-muted">NAME</small>
                                <input required type="text" name="name" class="form-control fw-bold" value="{{ $official->name }}">
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">POSITION</small>
                                <input required type="text" name="position" class="form-control fw-bold" value="{{ $official->position }}">
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">COMMITTEE</small>
                                <select name="committee_id" class="form-select mb-3 fw-bold" required>
                                    <option value="" selected disabled>SELECT COMMITTEE</option>
                                    @foreach($committees as $committee)
                                        <option @if($official->committee_id == $committee->id) selected @endif value="{{ $committee->id }}">{{ $committee->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">TERM START</small>
                                <input required type="date" name="term_start" class="form-control fw-bold" value="{{ optional($official->term_start)->format('Y-m-d') }}">
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">TERM END</small>
                                <input required type="date" name="term_end" class="form-control fw-bold" value="{{ optional($official->term_end)->format('Y-m-d') }}">
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
