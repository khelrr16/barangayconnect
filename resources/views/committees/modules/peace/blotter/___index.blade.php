@extends('layouts.committee')

@section('title', 'Immunization for Infants')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-3">Blotter Records</h4>
            <a href="{{ route('committee.peace.blotter.create') }}" class="btn btn-primary">+ NEW</a>
        </div>
        
        <div class="card shadow-sm card-custom">
            @if($infants->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" id="sortTable">
                    <thead class="text-center">
                        <tr>
                            <th>RECORD ID</th>
                            <th>DATE REPORTED</th>
                            <th>STATUS</th>
                            <th>COMPLAINANTS</th>
                            <th>RESPONDENTS</th>
                            <th>INCIDENT TYPE</th>
                            <th>INCIDENT DATE</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        
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
