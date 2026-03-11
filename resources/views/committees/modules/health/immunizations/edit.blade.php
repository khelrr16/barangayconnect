@extends('layouts.committee')

@section('title', 'Immunization')

@push('scripts')
<style>    
    /* Small text styling */
    .nav-tabs .nav-link small {
        font-size: 0.7rem;
        display: block;
        color: #6c757d;
    }
    
    /* Active state */
    .nav-tabs .nav-link.active{
        background-color: rgba(13, 109, 253, 0.1);
    }

    .nav-tabs .nav-link.active small {
        color: #0d6efd;
    }

    
</style>

@endpush

@section('content')
    <div class="container py-4">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('committee.immunization.index') }}" class="text-decoration-none text-dark">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
        
        <div class="card shadow-sm">

            <!-- Header -->
            <div class="d-flex">

                <!-- Title -->
                <div class="w-25 d-flex align-items-center justify-content-center bg-primary text-white border-end">
                    <h5 class="mb-0">Immunization Profile</h5>
                </div>

                <!-- Tab Panel -->
                <div class="w-75">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">

                            <button id="nav-overview-tab" data-bs-target="#nav-overview"
                                class="nav-link active  flex-fill text-center" data-bs-toggle="tab" type="button" role="tab">
                                Overview
                            </button>

                            <button id="nav-newborn-tab" data-bs-target="#nav-newborn" 
                                class="nav-link flex-fill text-center" data-bs-toggle="tab" type="button" role="tab">
                                Newborn <br>
                                <small>0-28 days old</small>
                            </button>
                            
                            <button id="nav-1-3-tab" data-bs-target="#nav-1-3" 
                                class="nav-link flex-fill text-center" data-bs-toggle="tab" type="button" role="tab">
                                1-3 months old <br>
                                <small>6-14 weeks</small>
                            </button>
                            
                            <button id="nav-6-11-tab" data-bs-target="#nav-6-11"
                                class="nav-link flex-fill text-center" data-bs-toggle="tab" type="button" role="tab">
                                6-11 months old <br>
                                <small>Vitamin, MNP, MMR</small>
                            </button>
                            
                            <button id="nav-12-tab" data-bs-target="#nav-12"
                                class="nav-link flex-fill text-center" data-bs-toggle="tab" type="button" role="tab">
                                12 months old <br>
                                <small>MMR, FIC</small>
                            </button>

                            <button id="nav-monitoring-tab" data-bs-target="#nav-monitoring"
                                class="nav-link flex-fill text-center" data-bs-toggle="tab" type="button" role="tab">
                                Monitoring
                            </button>

                        </div>
                    </nav>
                </div>
            </div>
            
            <!-- Body -->
            <div class="d-flex">
                
                <!-- Details Panel -->
                <div class="w-25 border-end">
                    <div class="p-3 m-3 rounded-5 bg-secondary bg-opacity-10">
                        <div class="mb-4">
                            <small class="text-muted">
                                <i class="fa-solid fa-baby"></i>
                                NAME
                            </small>
                            <h5 class="fw-bold">
                                <input type="text" name="first_name" class="form-control" value="{{ $infant->first_name }}">
                            </h5>
                            <h5 class="fw-bold">
                                <input type="text" name="middle_name" class="form-control" value="{{ $infant->middle_name }}">
                            </h5>
                            <h5 class="fw-bold">
                                <input type="text" name="last_name" class="form-control" value="{{ $infant->last_name }}">
                            </h5>
                            <h5 class="fw-bold">
                                <input type="text" name="extension_name" class="form-control" value="{{ $infant->extension_name }}">
                            </h5>
                        </div>
                        
                        <div class="mb-4">
                            <small class="text-muted">
                                <i class="fa-solid fa-pen-clip"></i>
                                DATE OF REGISTRATION
                            </small>
                            
                            <div class="fw-semibold">
                            <input type="date" name="created_at" class="form-control" value="{{ $infant->created_at->format('Y-m-d') }}">
                            </div>
                        </div>
                        
                        <hr>

                        <div class="mb-4">
                            <small class="text-muted">
                                <i class="fa fa-calendar"></i>
                                BIRTHDAY
                            </small>
                            <div class="fw-semibold">{{ $infant->birthday->format('F j, Y') }}</div>
                        </div>

                        <div class="mb-4">
                            
                            <small class="text-muted">
                                <i class="fa fa-mars-and-venus"></i>
                                SEX
                            </small>
                            <div class="fw-semibold">{{ $infant->sex }}</div>
                        </div>

                        <div class="mb-4">
                            <small class="text-muted">
                                <i class="fa fa-user"></i>
                                MOTHER'S NAME
                            </small>
                            <div class="fw-semibold">{{ $infant->mother_name }}</div>
                        </div>

                        <div class="mb-4">
                            <small class="text-muted">
                                <i class="fa fa-shield"></i>
                                CHILD PROTECTED AT BIRTH
                            </small>
                            <div class="fw-semibold">
                                {{ $infant->cpab ? 'Yes' : 'N/A' }}
                            </div>
                        </div>
                    </div>

                </div>
                
                <!-- Tab Panel -->
                <div class="w-75 ">
                    <div class="tab-content" id="nav-tabContent">
                        
                        <!-- Overview tab -->
                        <div class="tab-pane fade show active" id="nav-overview" role="tabpanel">
                            <div class="m-3 p-3 rounded-5 bg-secondary bg-opacity-10">
                                @include('committees.modules.health.immunizations.profile_tabs.overview')
                            </div>
                        </div>

                        <!-- Newborn tab -->
                        <div class="tab-pane fade" id="nav-newborn" role="tabpanel">
                            <div class="m-3 p-3 rounded-5 bg-secondary bg-opacity-10">
                                @include('committees.modules.health.immunizations.profile_tabs.newborn')
                            </div>
                        </div>

                        <!-- 1-3 months tab -->
                        <div class="tab-pane fade" id="nav-1-3" role="tabpanel">
                            <div class="m-3 p-3 rounded-5 bg-secondary bg-opacity-10">
                                @include('committees.modules.health.immunizations.profile_tabs.months_1_3')
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-6-11" role="tabpanel">
                            <div class="m-3 p-3 rounded-5 bg-secondary bg-opacity-10">
                                @include('committees.modules.health.immunizations.profile_tabs.months_6_11')
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-12" role="tabpanel">
                            <div class="m-3 p-3 rounded-5 bg-secondary bg-opacity-10">
                                @include('committees.modules.health.immunizations.profile_tabs.months_12')
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-monitoring" role="tabpanel">
                            <div class="m-3 p-3 rounded-5 bg-secondary bg-opacity-10">
                                @include('committees.modules.health.immunizations.profile_tabs.monitoring')
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

    </div>
@endsection