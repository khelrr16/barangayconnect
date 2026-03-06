@extends('layouts.admin')

@section('title', 'Registry of Brgy. Inhabitants')

@section('content')
    <div class="container-fluid mt-4">
        <h4 class="fw-bold mb-3">Household List</h4>
        
        <div class="card shadow-sm card-custom">
            @if($households->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" id="sortTable">
                    <thead class="text-center">
                        <tr>
                            <th>HH No.</th>
                            <th>BLK, LOT & UNIT</th>
                            <th>STREET</th>
                            <th>SUBDIVISION</th>
                            <th>HOUSEHOLD HEAD</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        @foreach ($households as $household)
                            <tr>
                                <td>{{ $household->household_no }}</td>
                                <td>{{ $household->blk_lot_unit }}</td>
                                <td>{{ $household->street }}</td>
                                <td>{{ $household->subdivision }}</td>
                                <td>{{ $household->household_head }}</td>
                                <td>
                                    <a href="{{ route('admin.hh.show', $household->id) }}" class="btn btn-warning btn-sm">VIEW</a>
                                    <a href="{{ route('admin.hh.edit', $household->id) }}" class="btn btn-warning btn-sm">EDIT</a>
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
