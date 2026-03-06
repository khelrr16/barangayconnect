@extends('layouts.admin')

@section('title', 'Registry of Brgy. Inhabitants')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-3">Registry of Brgy. Inhabitants (RBI)</h4>
            <div>
                <a href="{{ route('admin.rbi.upload.index') }}" class="btn btn-warning">UPLOAD CSV</a>
                <a href="{{ route('admin.rbi.create') }}" class="btn btn-primary">+ NEW</a>
            </div>
        </div>

        <!-- <div class="card shadow-sm card-custom mb-3">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Search by name or RBI No...">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select">
                            <option>All Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select">
                            <option>All Status</option>
                            <option>Single</option>
                            <option>Married</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select">
                            <option>Sort by RBI No.</option>
                            <option>Sort by Last Name</option>
                            <option>Sort by Age</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="#" class="btn btn-new w-100">
                            + NEW
                        </a>
                    </div>
                </div>
            </div>
        </div> -->
        
        <div class="card shadow-sm card-custom">
            @if($residents->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" id="sortTable">
                    <thead class="text-center">
                        <tr>
                            <th>RBI NO.</th>
                            <th>NAME</th>
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
                                <td>{{ $resident->sex }}</td>
                                <td>{{ $resident->age }}</td>
                                <td>
                                    <a href="{{ route('admin.hh.show', $resident->household->id) }}" target="_blank">
                                        {{ $resident->household->household_no }}
                                    </a>
                                </td>
                                <td>{{ $resident->role }}</td>
                                <td>
                                    <a href="{{ route('admin.rbi.show', $resident->id) }}" class="btn btn-warning btn-sm">VIEW</a>
                                    <a href="{{ route('admin.rbi.edit', $resident->id) }}" class="btn btn-warning btn-sm">EDIT</a>
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
