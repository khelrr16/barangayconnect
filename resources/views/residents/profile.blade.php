@extends('layouts.admin')

@section('title', 'Registry of Brgy. Inhabitants')

@section('content')
    <div class="container py-4">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('resident.index') }}" class="text-decoration-none text-dark">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h3 class="fw-bold mb-1">{{ $resident->full_name }}</h3>
                @if($resident->registered_voter == 'Yes - within')
                    <span class="badge bg-primary">Registered Voter</span>
                @elseif($resident->registered_voter == 'Yes - elsewhere')
                    <span class="badge bg-warning">Registered Voter Elsewhere</span>
                @else
                    <span class="badge bg-secondary">Unregistered Voter</span>
                @endif
            </div>

            @can('manage residents')
            <div>
                
                <a class="btn btn-outline-secondary me-2" href="{{ route('resident.edit', $resident->id) }}">
                    <i class="fa-solid fa-pencil"></i>
                </a>
                
                <form method="POST" action="{{ route('resident.destroy', $resident->id) }}" class="d-inline" onsubmit="return confirm('Delete this resident?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
            @endcan
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#personal">
                    Personal ID
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#residency">
                    Residency
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#socioEconomic">
                    Socio-Economic
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#commOrg">
                    Community Org
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#benificiary">
                    Benificiary
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#healthInfo">
                    Health Info
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">

            <!-- Personal ID -->
            <div class="tab-pane fade show active" id="personal">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <h5 class="fw-bold mb-4">Personal Identification</h5>

                        <div class="row g-4">

                            <div class="col-md-6">
                                <small class="text-muted">FULL NAME</small>
                                <div class="fw-semibold">{{ $resident->full_name }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">SEX</small>
                                <div class="fw-semibold">{{ $resident->sex }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">DATE OF BIRTH</small>
                                <div class="fw-semibold">{{ $resident->birthday }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">AGE</small>
                                <div class="fw-semibold">{{ $resident->age }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">CIVIL STATUS</small>
                                <div class="fw-semibold">{{ $resident->civil_status }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">CITIZENSHIP</small>
                                <div class="fw-semibold">{{ $resident->citizenship }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">PLACE OF BIRTH</small>
                                <div class="fw-semibold">{{ $resident->birthplace }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">CONTACT NUMBER</small>
                                <div class="fw-semibold">{{ $resident->contact_number }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">EMAIL ADDRESS</small>
                                <div class="fw-semibold">{{ $resident->email ?: 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Residency -->
            <div class="tab-pane fade" id="residency">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <h5 class="fw-bold mb-4">Residency Information</h5>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <small class="text-muted">HOUSEHOLD ID</small>
                                <div class="fw-semibold">{{ $resident->household->household_no }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">HOUSEHOLD HEAD</small>
                                <div class="fw-semibold">{{ $resident->household->household_head }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">RELATIONSHIP TO HEAD</small>
                                <div class="fw-semibold">{{ $resident->role }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">LENGTH OF STAY</small>
                                <div class="fw-semibold">{{ $resident->length_of_stay }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">BLOCK, LOT & UNIT</small>
                                <div class="fw-semibold">{{$resident->household->blk_lot_unit}}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">STREET</small>
                                <div class="fw-semibold">{{ $resident->household->street }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">SUBDIVISION</small>
                                <div class="fw-semibold">{{ $resident->household->subdivision }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">HOUSE OWNERSHIP</small>
                                <div class="fw-semibold">{{ $resident->ownership }}</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Socio-Economic -->
            <div class="tab-pane fade" id="socioEconomic">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <h5 class="fw-bold mb-4">Socio-Economic Data</h5>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <small class="text-muted">EDUCATIONAL ATTAINMENT</small>
                                <div class="fw-semibold">{{ $resident->educational_attainment }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">EMPLOYMENT STATUS</small>
                                <div class="fw-semibold">{{ $resident->employment_status }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">MONTHLY INCOME</small>
                                <div class="fw-semibold">{{ $resident->total_income }}</div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">RELIGION</small>
                                <div class="fw-semibold">{{ $resident->religion }}</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Community Organization -->
            <div class="tab-pane fade" id="commOrg">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Community Organization</h5>

                        <small class="text-muted">ORGANIZATIONS</small>
                        @if($resident->commOrgs->isNotEmpty())
                            <ul class="list-group list-group-flush">
                                @foreach($resident->commOrgs as $orgs)
                                    <li class="list-group-item fw-semibold">{{ $orgs->organization }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="fw-semibold">N/A</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Benificiary -->
            <div class="tab-pane fade" id="benificiary">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Benificiaries</h5>

                        <small class="text-muted">PROGRAMS</small>
                        @if($resident->programs->isNotEmpty())
                            <ul class="list-group list-group-flush">
                                @foreach($resident->programs as $program)
                                    <li class="list-group-item fw-semibold">{{ $program->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="fw-semibold">N/A</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Health -->
            <div class="tab-pane fade" id="healthInfo">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Health Information</h5>

                        <small class="text-muted">CO-MOresidentDITIES</small>
                        @if($resident->healthProfile->isNotEmpty())
                            <ul class="list-group list-group-flush">
                                @foreach($resident->healthProfile as $condition)
                                    <li class="list-group-item fw-semibold">{{ $condition->health_condition }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="fw-semibold">N/A</div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection