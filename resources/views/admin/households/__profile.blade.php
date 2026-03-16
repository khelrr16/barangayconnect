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

            <div>
                <button class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-print"></i> Print
                </button>

                <button class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </button>

                <button class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-file-word"></i> DOCX
                </button>
            </div>
        </div>

        <!-- Card Container -->
        <div class="card shadow-sm">
            <div class="card-body">

                <h5 class="text-center section-title mb-4">
                    RECORDS OF BARANGAY INHABITANTS BY HOUSEHOLD
                </h5>

                <!-- Household Info -->
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Region</label>
                        <input type="text" class="form-control" value="IV-A">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Province</label>
                        <input type="text" class="form-control" value="Laguna">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">City/Municipality</label>
                        <input type="text" class="form-control" value="San Pedro">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Barangay</label>
                        <input type="text" class="form-control" value="San Lorenzo Ruiz">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Household Address</label>
                        <input type="text" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">No. of Household Members</label>
                        <input type="number" class="form-control">
                    </div>

                </div>

                <!-- Table -->
                <div class="table-responsive mt-4">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="form-header">
                            <tr>
                                <th colspan="4">Name</th>
                                <th rowspan="2">Place of Birth</th>
                                <th rowspan="2">Date of Birth</th>
                                <th rowspan="2">Age</th>
                                <th rowspan="2">Sex</th>
                                <th rowspan="2">Civil Status</th>
                                <th rowspan="2">Citizenship</th>
                                <th rowspan="2">Occupation</th>
                                <th rowspan="2">Indicators</th>
                            </tr>
                            <tr>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Ext</th>
                            </tr>
                        </thead>

                        <tbody id="memberTable">
                            <tr>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="date" class="form-control"></td>
                                <td><input type="number" class="form-control"></td>
                                <td>
                                    <select class="form-select">
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control" placeholder="Labor/PWD/OFW/Solo Parent"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Add Row Button -->
                <button class="btn btn-warning mt-2">
                    <i class="fa-solid fa-user-plus"></i> Add Member Row
                </button>

                <!-- Signatures -->
                <div class="row text-center mt-5">

                    <div class="col-md-4">
                        <p>Prepared by:</p>
                        <div class="signature-line"></div>
                        <small>Name of Household/Head Member</small>
                    </div>

                    <div class="col-md-4">
                        <p>Certified Correct:</p>
                        <div class="signature-line"></div>
                        <small>Barangay Secretary</small>
                    </div>

                    <div class="col-md-4">
                        <p>Validated by:</p>
                        <div class="signature-line"></div>
                        <small>Punong Barangay</small>
                    </div>

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
