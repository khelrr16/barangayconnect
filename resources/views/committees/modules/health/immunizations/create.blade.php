@extends('layouts.committee')

@section('title', 'Immunization')

@section('content')
    @php
        $subdivisions = ['Conpil I Village', 'Conpil III Executive', 'Console 1 Village', 'Greatland Village', 'Guevara Subdivision', 'Pacita 2A', 'Pacita 2B'];
    @endphp

    <div class="container py-4">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('committee.immunization.index') }}" class="text-decoration-none text-dark">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>

        <form action="{{ route('committee.immunization.store') }}" method="POST">
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
                                    <small class="text-muted">FIRST NAME</small>
                                    <input required type="text" name="first_name" class="form-control fw-bold" value="{{ old('first_name') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">MIDDLE NAME</small>
                                    <input type="text" name="middle_name" class="form-control fw-bold" value="{{ old('middle_name') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">LAST NAME</small>
                                    <input required type="text" name="last_name" class="form-control fw-bold" value="{{ old('last_name') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">EXTENSION NAME</small>
                                    <input type="text" name="extension_name" class="form-control fw-bold" value="{{ old('extension_name') }}">
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
                            </div>
                        </div>

                        <div class="col-6">  
                            <ul class="nav nav-pills mb-3 gap-3 justify-content-center" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button id="pills-address1-tab" data-bs-target="#pills-address1" aria-controls="pills-address1"
                                        class="nav-link active" data-bs-toggle="pill" type="button" role="tab" aria-selected="true">
                                        San Lorenzo Resident
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button id="pills-address2-tab" data-bs-target="#pills-address2" aria-controls="pills-address2"
                                        class="nav-link" data-bs-toggle="pill" type="button" role="tab" aria-selected="false">
                                        Non-San Lorenzo Resident
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="pills-tabContent">
                                {{-- San Lorenzo Resident Tab --}}
                                <div class="tab-pane fade show active" id="pills-address1" aria-labelledby="pills-address1-tab" role="tabpanel">
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
                                <div class="tab-pane fade" id="pills-address2" aria-labelledby="pills-address2-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">HOUSE NUMBER</small>
                                            <input type="number" name="house_number" class="form-control fw-bold address2-field" value="{{ old('house_number') }}">
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
                            <input type="hidden" name="address_type" id="address_type" value="san_lorenzo">
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
            
            // Initial setup
            toggleFields('san_lorenzo');
            
            // Update on tab change
            tab1.addEventListener('shown.bs.tab', function() {
                addressType.value = 'san_lorenzo';
                toggleFields('san_lorenzo');
            });
            
            tab2.addEventListener('shown.bs.tab', function() {
                addressType.value = 'non_san_lorenzo';
                toggleFields('non_san_lorenzo');
            });
        });
    </script>
@endpush