@extends('layouts.committee')

@section('title', 'Immunization for Infants')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-3">Immunization for Infants</h4>
            <a href="{{ route('committee.infant.create') }}" class="btn btn-primary">+ NEW</a>
        </div>
        
        <div class="card shadow-sm card-custom">
            @if($infants->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" id="sortTable">
                    <thead class="text-center">
                        <tr>
                            <th>SERIAL NO.</th>
                            <th>NAME</th>
                            <th>MOTHER'S NAME</th>
                            <th>BIRTHDAY</th>
                            <th>AGE</th>
                            <th>MEDICINES USED</th>
                            <th>STATUS</th>
                            <th>REGISTERED AT</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        @foreach($infants as $index => $infant )
                        <tr>
                            <td>{{ $infant->family_serial_number }}</td>
                            <td>{{ $infant->name }}</td>
                            <td>{{ $infant->mother_name }}</td>
                            <td>{{ $infant->birthday->format('M j, Y') }}</td>
                            <td>{{ $infant->age }} </td>
                            <td>N/A</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>{{ $infant->created_at->format('M j, Y') }} </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2 text-center">
                                    <a href="{{ route('committee.immunization.show', $infant->id) }}" class="btn btn-primary btn-sm">VIEW</a>
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
