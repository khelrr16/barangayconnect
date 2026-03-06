@extends('layouts.admin')

@section('title', 'Registry of Brgy. Inhabitants')

@section('styles')
    <style>
        .form-header {
            background-color: #1f2f5a;
            color: white;
            font-weight: 600;
        }

        .section-title {
            font-weight: 700;
            letter-spacing: 1px;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="container my-4">

        <!-- Top Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold">
                <i class="fa-solid fa-file-lines"></i> HOUSEHOLDS
            </h4>

            <!-- <div>
                <button class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-print"></i> Print
                </button>

                <button class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </button>

                <button class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-file-word"></i> DOCX
                </button>
            </div> -->
        </div>

        <!-- Card Container -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="fs-4 fw-semibold">
                        {{ $household->household_no ?? '--' }}
                    </div>

                    <a class="btn btn-outline-secondary me-2" href="{{ route('admin.hh.show', $household->id) }}">
                        <i class="fa-solid fa-user"></i>
                    </a>
                </div>

                <h5 class="text-center section-title mb-4">
                    RECORDS OF BARANGAY INHABITANTS BY HOUSEHOLD
                </h5>

                <!-- Household Info --> 
                <form action="{{ route('admin.hh.update', $household->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <div class="col-md-6 d-flex align-items-center gap-2">
                            <div class="col-2">
                                <small class="text-muted">Block</small>
                                <input type="text" name="block" class="form-control w-75 fw-bold" value="{{ $household->block ?? '' }}">
                            </div>
                            <div class="col-2">
                                <small class="text-muted">Lot</small>
                                <input type="text" name="lot" class="form-control w-75 fw-bold" value="{{ $household->lot ?? '' }}">
                            </div>
                            <div class="col-2">
                                <small class="text-muted">Unit</small>
                                <input type="text" name="unit" class="form-control w-75 fw-bold" value="{{ $household->unit ?? '' }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Street</small>
                            <input type="text" name="street" class="form-control w-75 fw-bold" value="{{ $household->street ?? '' }}">
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Subdivision</small>
                            <input type="text" name="subdivision" class="form-control w-75 fw-bold" value="{{ $household->subdivision ?? '' }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary mt-2">UPDATE HOUSEHOLD</button>                    
                    </div>
                </form>


                <!-- Table -->
                <div class="table-responsive mt-4">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="form-header">
                            <tr>
                                <th>RBI No.</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Sex</th>
                                <th>Citizenship</th>
                                <th>Occupation</th>
                                <th>Role</th>
                                <th>Ownership</th>
                                <th>Indicators</th>
                            </tr>
                        </thead>

                        <tbody id="memberTable">
                            @foreach ($household->residents as $resident)
                                <tr>
                                    <td>{{ $resident->rbi_no ?? 'N/A' }}</td>
                                    <td>{{ $resident->full_name ?? 'N/A' }}</td>
                                    <td>{{ $resident->age ?? 'N/A' }}</td>
                                    <td>{{ $resident->sex ?? 'N/A' }}</td>
                                    <td>{{ $resident->citizenship ?? 'N/A' }}</td>
                                    <td>{{ $resident->occupation ?? 'N/A' }}</td>
                                    <td>{{ $resident->role ?? 'N/A' }}</td>
                                    <td>{{ $resident->ownership ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('admin.rbi.show', $resident->id) }}" class="btn btn-sm btn-outline-warning link-warning">
                                            <i class="fa-solid fa-user"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
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
