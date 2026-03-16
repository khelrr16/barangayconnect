@extends('layouts.committee')

@section('title', 'Medicines')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-3">Medicines</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                + NEW
            </button>
        </div>

        @if($medicines->isNotEmpty())
        <div class="row">
            @foreach($medicines as $medicine)
            <div class="col-6 mb-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary bg-opacity-10">
                        <button class="accordion-button container d-flex justify-content-between" data-bs-target="#medicinePanel{{ $medicine->id }}" aria-controls="medicinePanel{{ $medicine->id }}" aria-expanded="true" type="button" data-bs-toggle="collapse">
                            <div class="container d-flex justify-content-between align-items-center">
                                <h3 class="fw-bolder m-0">
                                    @if($medicine->type == 'Vaccine')
                                    <i class="fa-solid fa-syringe me-2"></i>
                                    @else
                                    <i class="fa-solid fa-prescription-bottle-medical"></i>
                                    @endif
                                    {{ $medicine->name }}
                                </h3>
                                <h4 class="fw-bold m-0">
                                    @if($medicine->overall->total_remaining < 1)
                                    <span class="badge bg-danger">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        No Stock
                                    </span>
                                    @elseif($medicine->overall->total_remaining < 10)
                                    <span class="badge bg-warning">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        Needs Restock
                                    </span>
                                    @endif
                                </h4 class="fw-bold m-0">
                            </div>
                        </button>
                    </div>
                    <div id="medicinePanel{{ $medicine->id }}" class="accordion-collapse collapse show">
                        <div class="card-body">
                            @if($medicine->currentBatch)
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">AVAILABLE STOCK</small>
                                    <h4 class="fw-bold">{{ $medicine->overall->total_remaining }} doses</h4>
                                </div>

                                <div class="mb-3 progress">
                                    <div class="progress-bar {{ $medicine->stockStatus }}" role="progressbar" style="width: {{ $medicine->stockPercent }}%">
                                        {{ $medicine->stockPercent }}%
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <small class="text-muted">Type</small>
                                    <div class="fw-semibold">{{ $medicine->type }}</div>
                                </div>

                                <div class="col-6 mb-3">
                                    <small class="text-muted">Dosage</small>
                                    <div class="fw-semibold">{{ $medicine->dose_volume }}</div>
                                </div>

                                @if($medicine->currentBatch)
                                <div class="col-6 mb-3">
                                    <small class="text-muted">Batch Number</small>
                                    <div class="fw-semibold">{{ $medicine->currentBatch->batch_number }}</div>
                                </div>

                                <div class="col-6 mb-3">
                                    <small class="text-muted">Batch Qty Remaining</small>
                                    <div class="fw-semibold">{{ $medicine->currentBatch->quantity_remaining }}</div>
                                </div>

                                <div class="col-6 mb-3">
                                    <small class="text-muted">Batch Qty Used</small>
                                    <div class="fw-semibold">{{ $medicine->currentBatch->quantity_used }}</div>
                                </div>

                                <div class="col-6 mb-3">
                                    <small class="text-muted">Batch Qty Received</small>
                                    <div class="fw-semibold">{{ $medicine->currentBatch->quantity_received }}</div>
                                </div>


                                <div class="col-6 mb-3">
                                    <small class="text-muted">Expiry Date</small>
                                    <div class="fw-semibold">{{ $medicine->currentBatch->expiry_date->format('M d, Y') }}</div>
                                </div>
                                @endif
                            </div>

                            @if($medicine->currentBatch)
                            <div class="rounded-2 bg-secondary bg-opacity-10 p-2">
                                <small class="text-muted">Manufacturer</small>
                                <div class="fw-semibold">{{ $medicine->currentBatch->manufacturer }}</div>
                            </div>
                            @endif
                        </div>

                        <div class="card-footer">
                            <div class="d-flex justify-content-between gap-2">
                                <a class="w-100 btn btn-sm btn-primary text-white fw-bold" href="{{ route('committee.health.medicine.show', $medicine) }}">
                                    <i class="fa-regular fa-eye"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
        @else
        No medicines found.
        @endif
    </div>
@endsection
