@extends('layouts.committee')

@section('title', 'Immunization')

@push('styles')
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

    /* Selected */
    .form-check-input:checked + .form-check-label {
        color: #0d6efd;
    }

    .card.border-primary {
        border-width: 2px;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>
@endpush

@section('content')
    @php
        $medicineIdByName = $medicines->pluck('id', 'name');
    @endphp

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
                            <button id="nav-newborn-tab" data-bs-target="#nav-newborn" 
                                class="nav-link active flex-fill text-center" data-bs-toggle="tab" type="button" role="tab">
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
                        <div class="mb-4 d-flex justify-content-center">
                            <button data-bs-target="#personalInfo" type="button" class="btn btn-primary" data-bs-toggle="modal">
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                        </div>
                        
                        <div class="mb-4">
                            <small class="text-muted">
                                <i class="fa-solid fa-baby"></i>
                                NAME
                            </small>
                            <h5 class="fw-bold">{{ $infant->name }}</h5>
                        </div>
                        
                        <div class="mb-4">
                            <small class="text-muted">
                                <i class="fa-solid fa-pen-clip"></i>
                                DATE OF REGISTRATION
                            </small>
                            <div class="fw-semibold">{{ $infant->created_at->format('F j, Y') }}</div>
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
                                @if($infant->cpab == 1)
                                    TTd2
                                @elseif($infant->cpab == 2)
                                    TT3/Td3 to TT5/Td5 <br>
                                    (or TT1/Td1 to TT5/Td5)
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <small class="text-muted">
                                <i class="fa-solid fa-house"></i>
                                ADDRESS
                            </small>
                            <div class="fw-bold">
                                @if($infant->household_id)
                                    {{ $infant->household->first_address }} <br>
                                    {{ $infant->household->second_address }}
                                @elseif($infant->foreign_household_id)
                                    {{ $infant->foreign_household->first_address }} <br>
                                    {{ $infant->foreign_household->second_address }}
                                @else
                                    N/A
                                @endif
                            </div>
                            
                        </div>
                    </div>

                </div>
                
                <!-- Tab Panel -->
                <div class="w-75 ">
                    <div class="tab-content" id="nav-tabContent">
                        <!-- Newborn tab -->
                        <div class="tab-pane fade show active" id="nav-newborn" role="tabpanel">
                            <div class="m-3 p-3 rounded-5 bg-secondary bg-opacity-10">
                                @include('committees.modules.health.immunizations.edit_tabs.newborn')
                            </div>
                        </div>

                        <!-- 1-3 months tab -->
                        <div class="tab-pane fade" id="nav-1-3" role="tabpanel">
                            <div class="m-3 p-3 rounded-5 bg-secondary bg-opacity-10">
                                @include('committees.modules.health.immunizations.edit_tabs.months_1_3')
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-6-11" role="tabpanel">
                            <div class="m-3 p-3 rounded-5 bg-secondary bg-opacity-10">
                                @include('committees.modules.health.immunizations.edit_tabs.months_6_11')
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-12" role="tabpanel">
                            <div class="m-3 p-3 rounded-5 bg-secondary bg-opacity-10">
                                @include('committees.modules.health.immunizations.edit_tabs.months_12')
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-monitoring" role="tabpanel">
                            <div class="m-3 p-3 rounded-5 bg-secondary bg-opacity-10">
                                @include('committees.modules.health.immunizations.edit_tabs.monitoring')
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Personal Info Modal -->
    <div class="modal fade" id="personalInfo" tabindex="-1" aria-labelledby="personalInfoLabel" aria-hidden="true">
        <form method="POST" action="{{ route('committee.infant.update', $infant) }}">
            @csrf
            @method('PATCH')

            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="personalInfoLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('committees.modules.health.immunizations.edit_tabs.personalInfo', ['infant' => $infant])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    function applyDoseSelection(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) {
            return;
        }

        const dateInput = modal.querySelector('[data-dose-date]');
        const selectedDate = dateInput ? dateInput.value : '';

        if (!selectedDate) {
            alert('Please select a date first.');
            return;
        }

        const checkboxes = modal.querySelectorAll('input[type="checkbox"][data-target-input]');

        checkboxes.forEach((checkbox) => {
            const targetSelector = checkbox.getAttribute('data-target-input');
            const targetInput = targetSelector ? document.querySelector(targetSelector) : null;

            if (!targetInput) {
                return;
            }

            if (checkbox.checked) {
                targetInput.value = selectedDate;
            }
        });

        const modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.hide();
        }
    }
</script>

@endpush