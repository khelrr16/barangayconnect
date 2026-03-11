@extends('layouts.admin')

@section('title', 'Printable RBI Report')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <h4 class="fw-bold mb-0">Printable RBI Report</h4>
            <div>
                <a href="{{ route('admin.rbi.index') }}" class="btn btn-outline-secondary">Back</a>
                <button type="button" class="btn btn-primary" onclick="window.print()">Print</button>
            </div>
        </div>

        <div class="card shadow-sm card-custom mb-3 no-print">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.rbi.printable') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category" id="categorySelect" class="form-select" onchange="this.form.submit()">
                            <option value="">All Residents</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ $selectedCategory === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Value</label>
                        <select name="value" class="form-select" {{ $selectedCategory === '' ? 'disabled' : '' }}>
                            <option value="">All</option>
                            @foreach($values as $value)
                                <option value="{{ $value }}" {{ $selectedValue === $value ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-warning me-2">Apply</button>
                        <a href="{{ route('admin.rbi.printable') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm card-custom printable-card">
            <div class="card-body">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Registry of Brgy. Inhabitants</h5>
                    <div class="text-muted small">
                        @if($selectedCategory !== '')
                            Category: {{ $categories[$selectedCategory] }}
                            @if($selectedValue !== '')
                                | Value: {{ $selectedValue }}
                            @else
                                | Value: All
                            @endif
                        @else
                            Category: All Residents
                        @endif
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead class="text-center">
                            <tr>
                                <th>RBI No.</th>
                                <th>Name</th>
                                <th>Sex</th>
                                <th>Age</th>
                                <th>Civil Status</th>
                                <th>Monthly Income</th>
                                <th>Household</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($residents as $resident)
                                <tr>
                                    <td class="text-center">{{ $resident->rbi_no }}</td>
                                    <td>{{ $resident->full_name }}</td>
                                    <td class="text-center">{{ $resident->sex }}</td>
                                    <td class="text-center">{{ $resident->age }}</td>
                                    <td class="text-center">{{ $resident->civil_status }}</td>
                                    <td>{{ $resident->monthly_income }}</td>
                                    <td>
                                        {{ $resident->household?->blk_lot_unit }}
                                        @if($resident->household)
                                            , {{ $resident->household->street }}, {{ $resident->household->subdivision }}
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No residents found.</td>
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
            .footer {
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
