@extends('layouts.committee')

@section('title', 'Medicine')

@section('content')
    <div class="container">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('committee.health.medicine.index') }}" class="text-decoration-none text-dark">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>

        <h2 class="mb-3">Medicine Inventory</h2>

        <div class="card shadow-sm mb-5">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2 align-items-center">
                    <div class="p-3 d-flex rounded-circle bg-secondary bg-opacity-10">
                        <h1 class="m-0 text-primary"><i class="fa-solid fa-syringe"></i><h1>
                    </div>
                    <div class="fs-5">
                        <h3 class="m-0">{{ $medicine->name }}</h3>
                        <span class="badge rounded-pill bg-primary">{{ $medicine->type }}</span>
                        <span class="badge rounded-pill border bg-light text-primary">{{ $medicine->dose_volume }}</span>
                    </div>
                </div>
                <button type="button" class="btn btn-success {{ old('batch_number') ? 'border border-3 border-danger shadow' : '' }}" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fa-solid fa-plus"></i> Add Batch
                </button>
            </div>

            <div class="card-body">
                {{ $medicine->description }}
            </div>
        </div>

        <h4>Medicine Batches</h4>

        <div class="card shadow-sm">
            <div class="card-body">
                @if($medicine->batches->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-striped table-hover text-center">
                        <thead>
                            <tr class="">
                                <th>Batch</th>
                                <th>Manufacturer</th>
                                <th>Received Date</th>
                                <th>Expiry Date</th>
                                <th>Received Qty</th>
                                <th>Used</th>
                                <th>Wasted</th>
                                <th>Expired</th>
                                <th>Available</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicine->batches as $batch)
                            <tr>
                                <td>{{ $batch->batch_number }}</td>
                                <td class="text-start">{{ $batch->manufacturer }}</td>
                                <td>{{ $batch->received_date->format('M d, Y') }}</td>
                                <td>{{ $batch->expiry_date->format('M d, Y') }}</td>
                                <td>{{ $batch->quantity_received }}</td>
                                <td>{{ $batch->quantity_used }}</td>
                                <td>{{ $batch->quantity_wasted ?? '0' }}</td>
                                <td>{{ $batch->quantity_expired ?? '0' }}</td>
                                <td>
                                    @if($batch->quantity_remaining < 1)
                                        <span class="badge bg-secondary">Empty</span>
                                    @elseif($batch->quantity_remaining <= 5)
                                        <span class="badge bg-danger">{{ $batch->quantity_remaining }}</span>
                                    @elseif($batch->quantity_remaining <= 10)
                                        <span class="badge bg-warning">{{ $batch->quantity_remaining }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $batch->quantity_remaining }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($batch->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $batch->id }}">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="text-center p-5">
                        <h5 class="text-muted">No batches added yet.</h5>
                </div>
                @endif
            </div>
        </div>

    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('committee.health.medicine.batch.store') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="createModalLabel">NEW BATCH</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <small class="text-muted">BATCH NUMBER</small>
                            <input required type="text" name="batch_number" value="{{ old('batch_number') }}" class="form-control fw-bold">
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">MANUFACTURER</small>
                            <input required type="text" name="manufacturer" value="{{ old('manufacturer') }}" class="form-control fw-bold">
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">RECEIVED DATE</small>
                            <input required type="date" name="received_date" value="{{ old('received_date') }}" class="form-control fw-bold">
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">EXPIRY DATE</small>
                            <input required type="date" name="expiry_date" value="{{ old('expiry_date') }}" class="form-control fw-bold">
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">RECEIVED QUANTITY</small>
                            <input required type="number" name="quantity_received" value="{{ old('quantity_received') }}" class="form-control fw-bold">
                        </div>

                        <input type="hidden" name="medicine_id" value="{{ $medicine->id }}" class="form-control fw-bold">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    @foreach($medicine->batches as $batch)
        <div class="modal fade" id="editModal-{{ $batch->id }}" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <form action="{{ route('committee.health.medicine.batch.update', $batch) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="modal-header">
                        <h1 class="modal-title fw-bolder fs-5 " id="createModalLabel">EDIT BATCH</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <small class="text-muted">BATCH NUMBER</small>
                            <input required type="text" name="batch_number" value="{{ old('batch_number', $batch->batch_number) }}" class="form-control fw-bold">
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">MANUFACTURER</small>
                            <input required type="text" name="manufacturer" value="{{ old('manufacturer', $batch->manufacturer) }}" class="form-control fw-bold">
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">RECEIVED DATE</small>
                            <input required type="date" name="received_date" value="{{ old('received_date', $batch->received_date->format('Y-m-d')) }}" class="form-control fw-bold">
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">EXPIRY DATE</small>
                            <input required type="date" name="expiry_date" value="{{ old('expiry_date', $batch->expiry_date->format('Y-m-d')) }}" class="form-control fw-bold">
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">RECEIVED QTY</small>
                            <input required type="number" name="quantity_received" value="{{ old('quantity_received', $batch->quantity_received) }}" class="form-control fw-bold">
                        </div>

                        <div class="d-flex gap-3">
                            <div class="mb-3 flex-grow-1">
                                <small class="text-muted">USED</small>
                                <input required type="number" name="quantity_used" value="{{ old('quantity_used', $batch->quantity_used) }}" class="form-control fw-bold">
                            </div>

                            <div class="mb-3 flex-grow-1">
                                <small class="text-muted">WASTED</small>
                                <input type="number" name="quantity_wasted" value="{{ old('quantity_wasted', $batch->quantity_wasted) }}" class="form-control fw-bold">
                            </div>

                            <div class="mb-3 flex-grow-1">
                                <small class="text-muted">EXPIRED</small>
                                <input type="number" name="quantity_expired" value="{{ old('quantity_expired', $batch->quantity_expired) }}" class="form-control fw-bold">
                            </div>

                        </div>

                        <div class="mb-3">
                            <small class="text-muted">STATUS</small>
                            <select name="status" id="" class="form-control fw-bold">
                                <option value="active" {{ old('status', $batch->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $batch->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <input type="hidden" name="medicine_id" value="{{ $medicine->id }}" class="form-control fw-bold">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

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
