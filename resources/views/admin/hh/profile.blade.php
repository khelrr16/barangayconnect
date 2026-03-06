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

                    <a class="btn btn-outline-secondary me-2" href="{{ route('admin.hh.edit', $household->id) }}">
                        <i class="fa-solid fa-pencil"></i>
                    </a>
                </div>

                <h5 class="text-center section-title mb-4">
                    RECORDS OF BARANGAY INHABITANTS BY HOUSEHOLD
                </h5>

                <!-- Household Info -->
                <div class="row g-3">

                    <div class="col-md-6">
                        <small class="text-muted">Block, Lot & Unit</small>
                        <div class="fw-semibold">{{ $household->blk_lot_unit ?? 'N/A' }}</div>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted">Street</small>
                        <div class="fw-semibold">{{ $household->street ?? 'N/A' }}</div>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted">Subdivision</small>
                        <div class="fw-semibold">{{ $household->subdivision ?? 'N/A' }}</div>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted">Household Head</small>
                        <div class="fw-semibold">{{ $household->household_head ?? 'N/A' }}</div>
                    </div>

                    <div class="col-md-6">
                        <small class="text-muted">No. of Household Members</small>
                        <div class="fw-semibold">{{ $household->residents->count() ?? 'N/A' }}</div>
                    </div>

                </div>

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

                <!-- Add Row Button -->
                <a class="btn btn-warning mt-2" href="{{ route('admin.rbi.create', ['subdivision' => $household->subdivision, 'street' => $household->street, 'block' => $household->block, 'lot' => $household->lot, 'unit' => $household->unit]) }}">
                    <i class="fa-solid fa-user-plus"></i> Add Member Row
                </a>

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
