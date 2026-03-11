@extends('layouts.admin')

@section('title', 'Registry of Brgy. Inhabitants')

@section('content')
    @php
        $prefillAddress = $prefillAddress ?? [];
    @endphp
    @php
        $subdivisions = ['Conpil I Village', 'Conpil III Executive', 'Console 1 Village', 'Greatland Village', 'Guevara Subdivision', 'Pacita 2A', 'Pacita 2B'];
        $c_statuses = ['Single', 'Married', 'Widow/Widower', 'Legally Separated'];
        $registered_voter = ['Yes - within', 'Yes - elsewhere', 'No'];
        $roles = ['Head', 'Spouse', 'Co-habiting Couple', 'Child', 'Child in law', 'Parent', 'Parent in law', 'Sibling', 'Sibling in law', 'Grandparent', 'Grandparent in law', 'Grandchildren', 'Uncle/Auntie', 'Cousin', 'Nephew/Niece'];
        $ownerships = ['Owner', 'Co-owner', 'Tenant', 'Co-tenant', 'Living with the owner', 'Living with the tenant'];
        $e_attainments = ['Post Graduate', 'College Graduate', 'College Level', 'Technical-Vocational', 'High School Graduate', 'High School Level', 'Elementary Graduate', 'Elementary Level' ,'No Schooling'];
        $e_statuses = ['Employed Full-time', 'Employed Part-time', 'Self-employed', 'Unemployed', 'Student', 'Out of School Children', 'Out of School Youth', 'Homemaker', 'Retired'];
        $monthly_incomes = ['Less than 12,000', '12,001 to 24,000', '24,001 to 48,000', '48,001 to 84,000', '84,001 to 145,000', '145,001 to 240,000', '240,001 and above'];
        $religions = ['Catholic', 'Islam', 'Iglesia ni Cristo', 'Evangelicals', 'Protestant', 'Seventh-day Adventist', 'Bible Baptist Church', 'Aglipayan', 'UCCP', 'Jehovah\'s Witnesses', 'Others'];
        $orgs = ['LGBTQ+', 'PWD', 'Senior Citizen', 'Solo Parent'];
        $conditions = ['Hypertension', 'Diabetes', 'Asthma', 'Heart Disease', 'Chronic Kidney Disease', 'Cancer', 'Tuberculosis', 'Pregnant', 'Other'];
    @endphp

    <div class="container py-4">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('resident.index') }}" class="text-decoration-none text-dark">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>

        <h4 class="fw-bold mb-3">New Resident for RBI</h4>
        <form action="{{ route('resident.store') }}" method="POST">
            @csrf
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Personal Info -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-4">Personal Identification</h5>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <small class="text-muted">FIRST NAME</small>
                                <input required type="text" name="first_name" class="form-control w-75 fw-bold" value="{{ old('first_name') }}">
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">MIDDLE NAME</small>
                                <input type="text" name="middle_name" class="form-control w-75 fw-bold" value="{{ old('middle_name') }}">
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">LAST NAME</small>
                                <input required type="text" name="last_name" class="form-control w-75 fw-bold" value="{{ old('last_name') }}">
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">EXTENSION NAME</small>
                                <input type="text" name="extension_name" class="form-control w-75 fw-bold" value="{{ old('extension_name') }}">
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">SEX</small>
                                <select required name="sex" class="form-select w-75 fw-bold">
                                    <option selected disabled>--</option>
                                    <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">DATE OF BIRTH</small>
                                <input required type="date" name="birthday" class="form-control w-75 fw-bold" value="{{ old('birthday') }}">
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">CIVIL STATUS</small>
                                <select required name="civil_status" class="form-select w-75 fw-bold">
                                    @foreach ($c_statuses as $status)
                                        <option value="{{ $status }}" {{ old('civil_status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">CITIZENSHIP</small>
                                <input required type="text" name="citizenship" class="form-control w-75 fw-bold" value="{{ old('citizenship') }}">
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">PLACE OF BIRTH</small>
                                <input required type="text" name="birthplace" class="form-control w-75 fw-bold" value="{{ old('birthplace') }}">
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">CONTACT NUMBER</small>
                                <input type="text" name="contact_number" class="form-control w-75 fw-bold" value="{{ old('contact_number') }}">
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">EMAIL ADDRESS</small>
                                <input type="text" name="email" class="form-control w-75 fw-bold" value="{{ old('email') }}">
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">REGISTERED VOTER</small>
                                <select required name="registered_voter" class="form-select w-75 fw-bold">
                                    <option selected disabled>--</option>
                                    @foreach ($registered_voter as $voter)
                                        <option value="{{ $voter }}" {{ old('registered_voter') == $voter ? 'selected' : '' }}>{{ $voter }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Residence Info -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-4">Residency Information</h5>

                        <div class="row g-4">
                            <div class="col-md-6 d-flex align-items-center gap-2">
                                <div class="col-3">
                                    <small class="text-muted">BLOCK</small>
                                    <input required type="number" name="block" class="form-control w-75 fw-bold" value="{{ old('block', $prefillAddress['block'] ?? '') }}">
                                </div>
                                <div class="col-3">
                                    <small class="text-muted">LOT</small>
                                    <input required type="number" name="lot" class="form-control w-75 fw-bold" value="{{ old('lot', $prefillAddress['lot'] ?? '') }}">
                                </div>
                                <div class="col-3">
                                    <small class="text-muted">UNIT</small>
                                    <input type="number" name="unit" class="form-control w-75 fw-bold" value="{{ old('unit', $prefillAddress['unit'] ?? '') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">STREET</small>
                                <input required type="text" name="street" class="form-control w-75 fw-bold" value="{{ old('street', $prefillAddress['street'] ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">SUBDIVISION</small>
                                <select required name="subdivision" class="form-select w-75 fw-bold">
                                    <option selected disabled>--</option>
                                    @foreach ($subdivisions as $subdivision)
                                        <option value="{{ $subdivision }}" {{ old('subdivision', $prefillAddress['subdivision'] ?? '') == $subdivision ? 'selected' : '' }}>{{ $subdivision }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">RELATIONSHIP TO HEAD</small>
                                <select name="role" class="form-select w-75 fw-bold">
                                    <option selected disabled>--</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>{{ $role }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">RESIDENCE SINCE (YEAR)</small>
                                <input type="number" name="residence_since" class="form-control w-75 fw-bold" value="{{ old('residence_since') }}">
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">HOUSE OWNERSHIP</small>
                                <select name="ownership" class="form-select w-75 fw-bold">
                                    <option selected disabled>--</option>
                                    @foreach ($ownerships as $ownership)
                                        <option value="{{ $ownership }}" {{ old('ownership') == $ownership ? 'selected' : '' }}>{{ $ownership }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Socio-Economic -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-4">Socio-Economic Data</h5>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <small class="text-muted">EDUCATIONAL ATTAINMENT</small>
                                <select name="educational_attainment" class="form-select w-75 fw-bold">
                                    <option selected disabled>--</option>
                                    @foreach ($e_attainments as $attainment)
                                        <option value="{{ $attainment }}" {{ old('educational_attainment') == $attainment ? 'selected' : '' }}>{{ $attainment }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">OCCUPATION</small>
                                <input type="text" name="occupation" class="form-control w-75 fw-bold" value="{{ old('occupation') }}">
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">EMPLOYMENT STATUS</small>
                                <select name="employment_status" class="form-select w-75 fw-bold">
                                    <option selected disabled>--</option>
                                    @foreach ($e_statuses as $status)
                                        <option value="{{ $status }}" {{ old('employment_status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">MONTHLY INCOME</small>
                                <select name="monthly_income" class="form-select w-75 fw-bold">
                                    <option selected disabled>--</option>
                                    @foreach ($monthly_incomes as $income)
                                        <option value="{{ $income }}" {{ old('monthly_income') == $income ? 'selected' : '' }}>{{ $income }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">RELIGION</small>
                                <select name="religion" class="form-select w-75 fw-bold">
                                    <option selected disabled>--</option>
                                    @foreach ($religions as $religion)
                                        <option value="{{ $religion }}" {{ old('religion') == $religion ? 'selected' : '' }}>{{ $religion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Community Organization -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-4">Community Organization</h5>

                        <small class="text-muted">ORGANIZATIONS</small>
                        
                        <ul class="list-group list-group-flush">
                            @foreach($orgs as $key => $org)
                                <li class="list-group-item fw-semibold">
                                    <input 
                                        type="checkbox" 
                                        id="org_{{ $key }}" 
                                        name="org[]" 
                                        value="{{ $org }}"
                                        {{ in_array($org, old('org', [])) ? 'checked' : '' }}>

                                    <label for="org_{{ $key }}">
                                        {{ $org }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Beneficiary -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-4">Benificiaries</h5>
                    
                        <small class="text-muted">PROGRAMS</small>
                        
                        @if($programs->isNotEmpty())
                            <table class="table table-hover">
                                <tr>
                                    <th width="5%"></th>
                                    <th width="30%">Program</th>
                                    <th width="20%">Status</th>
                                    <th width="45%">Remarks</th>
                                </tr>
                                @foreach($programs as $program)
                                    @php
                                        $oldPrograms = old('programs', []);
                                    @endphp
                                    
                                    <tr class="{{ in_array($program->id, $oldPrograms) ? 'table-primary' : '' }}">
                                        <td>
                                            <input type="checkbox" 
                                                name="programs[]" 
                                                value="{{ $program->id }}"
                                                id="program_{{ $program->id }}"
                                                class="form-check-input program-checkbox"
                                                {{ in_array($program->id, $oldPrograms) ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <label for="program_{{ $program->id }}" class="fw-bold">
                                                {{ $program->name }}
                                            </label>
                                            <br>
                                            <small class="text-muted">{{ $program->agency }}</small>
                                        </td>
                                        <td>
                                            <select name="status[{{ $program->id }}]" 
                                                    class="form-select form-select-sm status-select"
                                                    {{ !in_array($program->id, $oldPrograms) ? 'disabled' : '' }}>
                                                <option value="pending" {{ old("status.$program->id") == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="active" {{ old("status.$program->id") == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="completed" {{ old("status.$program->id") == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="withdrawn" {{ old("status.$program->id") == 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                name="remarks[{{ $program->id }}]" 
                                                class="form-control form-control-sm remarks-input"
                                                placeholder="Add remarks"
                                                value="{{ old("remarks.$program->id") }}"
                                                {{ !in_array($program->id, $oldPrograms) ? 'disabled' : '' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                    </div>

                    <!-- Health Info -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-4">Health Information</h5>

                        <small class="text-muted">CO-MORBIDITIES</small>
                        
                        <ul class="list-group list-group-flush">
                            @foreach($conditions as $key => $condition)
                                <li class="list-group-item fw-semibold">
                                    <input 
                                        type="checkbox" 
                                        id="condition_{{ $key }}" 
                                        name="condition[]" 
                                        value="{{ $condition }}"
                                        {{ in_array($condition, old('condition', [])) ? 'checked' : '' }}>

                                    <label for="condition_{{ $key }}">
                                        {{ $condition }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Add Resident</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        function toggleProgramFields(checkbox) {
            const row = checkbox.closest('tr');
            const statusSelect = row.querySelector('.status-select');
            const remarksInput = row.querySelector('.remarks-input');
            
            statusSelect.disabled = !checkbox.checked;
            remarksInput.disabled = !checkbox.checked;
            
            if (checkbox.checked) {
                row.classList.add('table-primary');
            } else {
                row.classList.remove('table-primary');
            }
        }
        
        const programCheckboxes = document.querySelectorAll('.program-checkbox');
        
        programCheckboxes.forEach(checkbox => {
            toggleProgramFields(checkbox);
        });
        
        programCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                toggleProgramFields(this);
            });
        });
    });
</script>
@endpush