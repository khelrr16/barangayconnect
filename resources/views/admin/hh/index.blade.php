@extends('layouts.admin')

@section('title', 'Registry of Brgy. Inhabitants')

@section('content')
    <div class="container-fluid mt-4">
        <h4 class="fw-bold mb-3">Household List</h4>
        
        <div class="card shadow-sm card-custom">
            <form
                action="{{ route('admin.hh.index') }}"
                method="GET"
                class="p-2"
                id="householdFilterForm"
                data-subdivision-street-map='@json($subdivisionStreetMap)'
                data-selected-street="{{ $selectedStreet }}"
            >
                <div class="d-flex align-items-end gap-1 flex-nowrap mx-1">
                    <div class="d-flex align-items-center gap-2">
                        <label for="subdivision" class="form-label mb-0 fw-bold">SUBDIVISION</label>
                        <select class="form-select" name="subdivision" id="subdivision">
                            <option value="">ALL SUBDIVISIONS</option>
                            @foreach ($subdivisions as $subdivision)
                                <option value="{{ $subdivision }}" {{ $selectedSubdivision === $subdivision ? 'selected' : '' }}>
                                    {{ $subdivision }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <label for="street" class="form-label mb-0 fw-bold">STREET</label>
                        <select class="form-select" name="street" id="street">
                            <option value="">ALL STREETS</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">SORT</button>
                    </div>
                </div>
            </form>
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
            const filterForm = document.getElementById('householdFilterForm');
            const subdivisionStreetMap = filterForm
                ? JSON.parse(filterForm.dataset.subdivisionStreetMap || '{}')
                : {};
            const selectedStreet = filterForm ? (filterForm.dataset.selectedStreet || '') : '';

            const subdivisionSelect = document.getElementById('subdivision');
            const streetSelect = document.getElementById('street');

            const renderStreetOptions = (subdivision, selected = '') => {
                if (!streetSelect) {
                    return;
                }

                streetSelect.innerHTML = '';

                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'ALL STREETS';
                streetSelect.appendChild(defaultOption);

                const streets = subdivision ? (subdivisionStreetMap[subdivision] || []) : [];

                streets.forEach((street) => {
                    const option = document.createElement('option');
                    option.value = street;
                    option.textContent = street;
                    if (selected && selected === street) {
                        option.selected = true;
                    }
                    streetSelect.appendChild(option);
                });
            };

            if (subdivisionSelect && streetSelect) {
                renderStreetOptions(subdivisionSelect.value, selectedStreet);

                subdivisionSelect.addEventListener('change', function () {
                    renderStreetOptions(this.value);
                });
            }

            const tableEl = document.getElementById('sortTable');
            if (window.DataTable && tableEl) {
                new window.DataTable('#sortTable', {
                    responsive: true,
                    paging: true,
                    pageLength: 20
                });
            }
        });
    </script>
@endpush
