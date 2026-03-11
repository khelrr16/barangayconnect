@extends('layouts.admin')

@section('title', 'Registry of Brgy. Inhabitants')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-3">Registry of Brgy. Inhabitants (RBI)</h4>
            <div>
                <!-- <a href="{{ route('resident.printable') }}" class="btn btn-outline-secondary">PRINTABLE</a> -->
                <a href="{{ route('resident.upload.index') }}" class="btn btn-warning">UPLOAD CSV</a>
                <a href="{{ route('resident.create') }}" class="btn btn-primary">+ NEW</a>
            </div>
        </div>
        
        <div class="card shadow-sm card-custom">
            @if($residents->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" id="sortTable">
                    <thead class="text-center">
                        <tr>
                            <th>RBI NO.</th>
                            <th>NAME</th>
                            <th>STATUS</th>
                            <th>SEX</th>
                            <th>AGE</th>
                            <th>HOUSEHOLD</th>
                            <th>ROLE</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        @foreach ($residents as $resident)
                            <tr>
                                <td>{{ $resident->rbi_no }}</td>
                                <td>{{ $resident->full_name }}</td>
                                <td>
                                    @if($resident->trashed())
                                        <span class="badge bg-danger">Deleted</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td>{{ $resident->sex }}</td>
                                <td>{{ $resident->age }}</td>
                                <td>
                                    <a href="{{ route('household.show', $resident->household->id) }}" target="_blank">
                                        {{ $resident->household->household_no }}
                                    </a>
                                </td>
                                <td>{{ $resident->role }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2 text-center">
                                        <a href="{{ route('resident.show', $resident->id) }}" class="btn btn-primary btn-sm">VIEW</a>
                                        @can('manage residents')
                                        @if(!$resident->trashed())
                                            <a href="{{ route('resident.edit', $resident->id) }}" class="btn btn-warning btn-sm">EDIT</a>
                                            <form method="POST" action="{{ route('resident.destroy', $resident->id) }}" class="d-inline" onsubmit="return confirm('Confirm Delete?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">DELETE</button>
                                            </form>
                                        @endif
                                        @endcan
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let progressInterval = null;

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
