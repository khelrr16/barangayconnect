@extends('layouts.committee')

@section('title', 'Health Dashboard Print')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <div>
                <h4 class="fw-bold mb-0">Health Dashboard Report</h4>
                <small class="text-muted">Period: {{ $periodLabel }}</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('committee.health.dashboard', request()->query()) }}" class="btn btn-outline-secondary">Back</a>
                <button type="button" class="btn btn-primary" onclick="window.print()">Print</button>
            </div>
        </div>

        <div class="card shadow-sm printable-card mb-3">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Overall Medicines Used</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th class="text-center">Immunization</th>
                                <th class="text-center">Direct Infant Entries</th>
                                <th class="text-center">Overall Used</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($medicineUsage as $row)
                                <tr>
                                    <td>{{ $row['medicine_name'] }}</td>
                                    <td class="text-center">{{ $row['immunization_total'] }}</td>
                                    <td class="text-center">{{ $row['direct_total'] }}</td>
                                    <td class="text-center">{{ $row['overall_total'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No medicine records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm printable-card mb-3">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Monitoring Status Per Infant</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Infant</th>
                                <th>Status</th>
                                <th>Updated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monitoringInfants as $infant)
                                <tr>
                                    <td>{{ $infant->name }}</td>
                                    <td>{{ $infant->status }}</td>
                                    <td>{{ optional($infant->updated_at)->format('M d, Y h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No monitoring records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm printable-card">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Nutritional Status Per Infant (Latest in Period)</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Infant</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Assessment Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestNutritionalByInfant as $record)
                                <tr>
                                    <td>{{ $record->infant_name }}</td>
                                    <td>{{ str_replace('_', ' ', (string) $record->category) }}</td>
                                    <td>{{ $record->status ?: '-' }}</td>
                                    <td>{{ optional($record->assessment_date)->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No nutritional records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print,
            .navbar,
            .sidebar,
            .footer,
            .admin-header {
                display: none !important;
            }

            .container-fluid {
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }

            .printable-card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
@endsection
