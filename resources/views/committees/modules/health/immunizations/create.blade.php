@extends('layouts.committee')

@section('title', 'Immunization')

@push('scripts')
    <style>
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
        $subdivisions = ['Conpil I Village', 'Conpil III Executive', 'Console 1 Village', 'Greatland Village', 'Guevara Subdivision', 'Pacita 2A', 'Pacita 2B'];
    @endphp

    <div class="container py-4">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('committee.health.immunization.index') }}" class="text-decoration-none text-dark">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>

        <form action="{{ route('committee.health.infant.store') }}" method="POST">
            @csrf

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="fw-bold mb-4 text-center">NEW INFANT</h4>

                    <div class="row">

                        <div class="col-6 border-end">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <small class="text-muted">FAMILY SERIAL NUMBER</small>
                                    <input required type="text" name="family_serial_number" class="form-control fw-bold" value="{{ old('family_serial_number') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">NAME</small>
                                    <input required type="text" name="name" class="form-control fw-bold" value="{{ old('name') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">SEX</small>
                                    <select required name="sex" class="form-select fw-bold">
                                        <option selected disabled>--</option>
                                        <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">DATE OF BIRTH</small>
                                    <input required type="date" name="birthday" class="form-control fw-bold" value="{{ old('birthday') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">MOTHER'S FULL NAME</small>
                                    <input required type="text" name="mother_name" class="form-control fw-bold" value="{{ old('mother_name') }}">
                                </div>

                                <div class="col-12 mb-3">
                                    <small class="text-muted d-block mb-2 fw-bold">Child Protected at Birth (CPAB)</small>
                                    <div class="row g-3">
                                        {{-- Option 1: TT2/Td2 --}}
                                        <div class="col-md-6">
                                            <div class="card h-100 {{ old('cpab') == '1' ? 'border-primary' : '' }}">
                                                <div class="card-body">
                                                    <div class="form-check mb-2 d-flex justify-content-center gap-2">
                                                        <input type="radio" name="cpab" id="cpab-1" value="1" class="form-check-input" {{ old('cpab') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold" for="cpab-1">
                                                            TT2/Td2
                                                        </label>
                                                    </div>
                                                    <small class="text-muted d-block">
                                                        Given to the mother a month prior to delivery
                                                        (for mothers pregnant for the first time)
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Option 2: TT3/Td3 to TT5/Td5 --}}
                                        <div class="col-md-6">
                                            <div class="card h-100 {{ old('cpab') == '2' ? 'border-primary' : '' }}">
                                                <div class="card-body">
                                                    <div class="form-check mb-2 d-flex justify-content-center gap-2">
                                                        <input type="radio" name="cpab" id="cpab-2" value="2" class="form-check-input" {{ old('cpab') == '2' ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold" for="cpab-2">
                                                            TT3/Td3 to TT5/Td5 <br class="d-none d-md-inline">
                                                            <span class="text-secondary">(or TT1/Td1 to TT5/Td5)</span>
                                                        </label>
                                                    </div>
                                                    <small class="text-muted d-block">
                                                        Given to the mother anytime prior to delivery
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Additional Info (Optional) --}}
                                    <div class="mt-2 text-info small">
                                        <i class="fa-solid fa-info-circle me-1"></i>
                                        CPAB status determines if the newborn is protected against neonatal tetanus.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <ul class="nav nav-pills mb-3 gap-3 justify-content-center" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button id="pills-address1-tab" data-bs-target="#pills-address1"
                                        class="nav-link {{ old('address_type') == 'san_lorenzo' ? 'active' : '' }}" data-bs-toggle="pill" type="button" role="tab">
                                        San Lorenzo Resident
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button id="pills-address2-tab" data-bs-target="#pills-address2"
                                        class="nav-link {{ old('address_type') == 'non_san_lorenzo' ? 'active' : '' }}" data-bs-toggle="pill" type="button" role="tab">
                                        Non-San Lorenzo Resident
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="pills-tabContent">
                                {{-- San Lorenzo Resident Tab --}}
                                <div class="tab-pane fade {{ old('address_type') == 'san_lorenzo' ? 'active show' : '' }}" id="pills-address1" aria-labelledby="pills-address1-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <small class="text-muted">BLOCK</small>
                                            <input type="number" name="block" class="form-control fw-bold address1-field" value="{{ old('block') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <small class="text-muted">LOT</small>
                                            <input type="number" name="lot" class="form-control fw-bold address1-field" value="{{ old('lot') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <small class="text-muted">UNIT</small>
                                            <input type="number" name="unit" class="form-control fw-bold address1-field" value="{{ old('unit') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">STREET</small>
                                            <input type="text" name="street" class="form-control fw-bold address1-field" value="{{ old('street') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">SUBDIVISION</small>
                                            <select name="subdivision" class="form-select fw-bold address1-field">
                                                <option value="" selected disabled>--</option>
                                                @foreach ($subdivisions as $subdivision)
                                                    <option value="{{ $subdivision }}" {{ old('subdivision') == $subdivision ? 'selected' : '' }}>{{ $subdivision }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- Non-San Lorenzo Resident Tab --}}
                                <div class="tab-pane fade {{ old('address_type') == 'non_san_lorenzo' ? 'active show' : '' }}" id="pills-address2" aria-labelledby="pills-address2-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">HOUSE NUMBER</small>
                                            <input type="text" name="house_number" class="form-control fw-bold address2-field" value="{{ old('house_number') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">STREET</small>
                                            <input type="text" name="street" class="form-control fw-bold address2-field" value="{{ old('street') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">SUBDIVISION</small>
                                            <input type="text" name="subdivision" class="form-control fw-bold address2-field" value="{{ old('subdivision') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">BARANGAY</small>
                                            <input type="text" name="barangay" class="form-control fw-bold address2-field" value="{{ old('barangay') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">CITY</small>
                                            <input type="text" name="city" class="form-control fw-bold address2-field" value="{{ old('city') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">PROVINCE</small>
                                            <input type="text" name="province" class="form-control fw-bold address2-field" value="{{ old('province') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Hidden field to identify which address type --}}
                            <input type="hidden" name="address_type" id="address_type" value="{{ old('address_type') ?? '' }}">
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary mx-auto">ADD INFANT</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addressType = document.getElementById('address_type');
            const address1Fields = document.querySelectorAll('.address1-field');
            const address2Fields = document.querySelectorAll('.address2-field');
            const tab1 = document.getElementById('pills-address1-tab');
            const tab2 = document.getElementById('pills-address2-tab');

            // Function to enable/disable fields based on active tab
            function toggleFields(activeTab) {
                if (activeTab === 'san_lorenzo') {
                    // Enable address1 fields, disable address2 fields
                    address1Fields.forEach(field => {
                        field.disabled = false;
                    });
                    address2Fields.forEach(field => {
                        field.disabled = true;
                    });
                } else {
                    // Enable address2 fields, disable address1 fields
                    address1Fields.forEach(field => {
                        field.disabled = true;
                    });
                    address2Fields.forEach(field => {
                        field.disabled = false;
                    });
                }
            }

            // Update on tab change
            tab1.addEventListener('shown.bs.tab', function() {
                addressType.value = 'san_lorenzo';
                toggleFields('san_lorenzo');
            });

            tab2.addEventListener('shown.bs.tab', function() {
                addressType.value = 'non_san_lorenzo';
                toggleFields('non_san_lorenzo');
            });

            toggleFields(addressType.value);
        });
    </script>
@endpush
