@extends('layouts.admin')

@section('title', 'Registry of Brgy. Inhabitants')

@section('content')
    @php
        $subdivisions = ['Conpil I Village', 'Conpil III Executive', 'Console 1 Village', 'Greatland Village', 'Guevara Subdivision', 'Pacita 2A', 'Pacita 2B'];
        $c_statuses = ['Single', 'Married', 'Widow/Widower', 'Legally Separated'];
        $registered_voter = ['Yes - within', 'Yes - elsewhere', 'No'];
        $roles = ['Head', 'Spouse', 'Co-habiting Couple', 'Child', 'Child in law', 'Parent', 'Parent in law', 'Sibling', 'Sibling in law', 'Grandparent', 'Grandparent in law', 'Grandchildren', 'Uncle/Auntie', 'Cousin', 'Nephew/Niece'];
        $ownerships = ['Owner', 'Co-owner', 'Tenant', 'Co-tenant', 'Living with the owner', 'Living with the tenant'];
        $e_attainments = ['Post Graduate', 'College Graduate', 'College Level', 'Technical-Vocational', 'High School Graduate', 'High School Level', 'Elementary Graduate', 'Elementary Level', 'No Schooling'];
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

            <div>
                <a class="btn btn-outline-secondary me-2" href="{{ route('resident.show', $resident->id) }}">
                    <i class="fa-solid fa-user"></i>
                </a>
                <form method="POST" action="{{ route('resident.destroy', $resident->id) }}" class="d-inline" onsubmit="return confirm('Delete this resident?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
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
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#beneficiary">
                    Beneficiary
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

                        <form method="POST" action="{{ route('resident.update', $resident->id) }}">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="updateTab" value="personal">
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <small class="text-muted">FIRST NAME</small>
                                    <input type="text" name="first_name" class="form-control w-75 fw-bold" value="{{ $resident->first_name }}">
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">MIDDLE NAME</small>
                                    <input type="text" name="middle_name" class="form-control w-75 fw-bold" value="{{ $resident->middle_name }}">
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">LAST NAME</small>
                                    <input type="text" name="last_name" class="form-control w-75 fw-bold" value="{{ $resident->last_name }}">
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">EXTENSION NAME</small>
                                    <input type="text" name="extension_name" class="form-control w-75 fw-bold" value="{{ $resident->extension_name }}">
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">SEX</small>
                                    <select name="sex" class="form-select w-75 fw-bold">
                                        <option selected disabled>--</option>
                                        <option value="Male" {{ $resident->sex == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $resident->sex == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">DATE OF BIRTH</small>
                                    <input type="date" name="birthday" class="form-control w-75 fw-bold" value="{{ $resident->birthday }}">
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">CIVIL STATUS</small>
                                    <select name="civil_status" class="form-select w-75 fw-bold">
                                        <option selected disabled>--</option>
                                        @foreach ($c_statuses as $status)
                                            <option value="{{ $status }}" {{ $resident->civil_status == $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">CITIZENSHIP</small>
                                    <input type="text" name="citizenship" class="form-control w-75 fw-bold" value="{{ $resident->citizenship }}">
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">PLACE OF BIRTH</small>
                                    <input type="text" name="birthplace" class="form-control w-75 fw-bold" value="{{ $resident->birthplace }}">
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">CONTACT NUMBER</small>
                                    <input type="text" name="contact_number" class="form-control w-75 fw-bold" value="{{ $resident->contact_number }}">
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">EMAIL ADDRESS</small>
                                    <input type="text" name="email" class="form-control w-75 fw-bold" value="{{ $resident->email }}">
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">REGISTERED VOTER</small>
                                    <select name="registered_voter" class="form-select w-75 fw-bold">
                                        <option selected disabled>--</option>
                                        @foreach ($registered_voter as $voter)
                                            <option value="{{ $voter }}" {{ $resident->registered_voter == $voter ? 'selected' : '' }}>{{ $voter }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Save Personal ID</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Residency -->
            <div class="tab-pane fade" id="residency">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <h5 class="fw-bold mb-4">Residency Information</h5>

                        <form method="POST" action="{{ route('resident.update', $resident->id) }}">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="updateTab" value="residency">

                            <div class="row g-4">
                                <div class="col-md-6 d-flex align-items-center gap-2">
                                    <div class="col-3">
                                        <small class="text-muted">BLOCK</small>
                                        <input required type="number" name="block" class="form-control w-75 fw-bold" value="{{ $resident->household->block ?? '' }}">
                                    </div>
                                    <div class="col-3">
                                        <small class="text-muted">LOT</small>
                                        <input required type="number" name="lot" class="form-control w-75 fw-bold" value="{{ $resident->household->lot ?? '' }}">
                                    </div>
                                    <div class="col-3">
                                        <small class="text-muted">UNIT</small>
                                        <input type="number" name="unit" class="form-control w-75 fw-bold" value="{{ $resident->household->unit ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">STREET</small>
                                    <input required type="text" name="street" class="form-control w-75 fw-bold" value="{{ $resident->household->street ?? '' }}">
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">SUBDIVISION</small>
                                    <select required name="subdivision" class="form-select w-75 fw-bold">
                                        <option selected disabled>--</option>
                                        @foreach ($subdivisions as $subdivision)
                                            <option value="{{ $subdivision }}" {{ $resident->household->subdivision == $subdivision ? 'selected' : '' }}>{{ $subdivision }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">RELATIONSHIP TO HEAD</small>
                                    <select name="role" class="form-select w-75 fw-bold">
                                        <option selected disabled>--</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role }}" {{ $resident->role == $role ? 'selected' : '' }}>{{ $role }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">RESIDENCE SINCE</small>
                                    <input type="number" name="residence_since" class="form-control w-75 fw-bold" value="{{ $resident->residence_since }}">
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">HOUSE OWNERSHIP</small>
                                    <select name="ownership" class="form-select w-75 fw-bold">
                                        <option selected disabled>--</option>
                                        @foreach ($ownerships as $ownership)
                                            <option value="{{ $ownership }}" {{ $resident->ownership == $ownership ? 'selected' : '' }}>{{ $ownership }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Save Residency Information</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Socio-Economic -->
            <div class="tab-pane fade" id="socioEconomic">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <h5 class="fw-bold mb-4">Socio-Economic Data</h5>

                        <form method="POST" action="{{ route('resident.update', $resident->id) }}">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="updateTab" value="socioEconomic">

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <small class="text-muted">EDUCATIONAL ATTAINMENT</small>
                                    <select name="educational_attainment" class="form-select w-75 fw-bold">
                                        <option selected disabled>--</option>
                                        @foreach ($e_attainments as $attainment)
                                            <option value="{{ $attainment }}" {{ $resident->educational_attainment == $attainment ? 'selected' : '' }}>{{ $attainment }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">OCCUPATION</small>
                                    <input type="text" name="occupation" class="form-control w-75 fw-bold" value="{{ $resident->occupation }}">
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">EMPLOYMENT STATUS</small>
                                    <select name="employment_status" class="form-select w-75 fw-bold">
                                        <option selected disabled>--</option>
                                        @foreach ($e_statuses as $status)
                                            <option value="{{ $status }}" {{ $resident->employment_status == $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">MONTHLY INCOME</small>
                                    <select name="monthly_income" class="form-select w-75 fw-bold">
                                        <option selected disabled>--</option>
                                        @foreach ($monthly_incomes as $income)
                                            <option value="{{ $income }}" {{ $resident->monthly_income == $income ? 'selected' : '' }}>{{ $income }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <small class="text-muted">RELIGION</small>
                                    <select name="religion" class="form-select w-75 fw-bold">
                                        <option selected disabled>--</option>
                                        @foreach ($religions as $religion)
                                            <option value="{{ $religion }}" {{ $resident->religion == $religion ? 'selected' : '' }}>{{ $religion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Save Socio-Economic Data</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Community Organization -->
            <div class="tab-pane fade" id="commOrg">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Community Organization</h5>

                        <form method="POST" action="{{ route('resident.update', $resident->id) }}">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="updateTab" value="commOrg">

                            <small class="text-muted">ORGANIZATIONS</small>
                            
                            <ul class="list-group list-group-flush">
                                @foreach($orgs as $key => $org)
                                    @php
                                        $hasOrg = $resident->commOrgs->contains('organization', $org);
                                    @endphp
                                    <li class="list-group-item fw-semibold">
                                        <input 
                                            type="checkbox" 
                                            id="org_{{ $key }}" 
                                            name="org[]" 
                                            value="{{ $org }}"
                                            {{ $hasOrg ? 'checked' : '' }}>

                                        <label for="org_{{ $key }}">
                                            {{ $org }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>

                            <button type="submit" class="btn btn-primary mt-3">Save Organizations</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Beneficiary -->
            <div class="tab-pane fade" id="beneficiary">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Benificiaries</h5>

                        <form method="POST" action="{{ route('resident.update', $resident->id) }}">
                            @csrf
                            @method('PATCH')
                            
                            <input type="hidden" name="updateTab" value="beneficiary">
                        
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
                                            // Check if resident is already enrolled in this program
                                            $isEnrolled = isset($residentPrograms[$program->id]);
                                            $currentStatus = $isEnrolled ? $residentPrograms[$program->id]->pivot->status : 'pending';
                                            $currentRemarks = $isEnrolled ? $residentPrograms[$program->id]->pivot->remarks : '';
                                        @endphp
                                        
                                        <tr class="{{ $isEnrolled ? 'table-primary' : '' }}">
                                            <td>
                                                <input type="checkbox" 
                                                    name="programs[]" 
                                                    value="{{ $program->id }}"
                                                    id="program_{{ $program->id }}"
                                                    class="form-check-input program-checkbox"
                                                    {{ $isEnrolled ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <label for="program_{{ $program->id }}" class="fw-bold">
                                                    {{ $program->name }}
                                                </label>
                                                <br>
                                                <small class="text-muted">{{ $program->agency }}</small>
                                                @if($isEnrolled)
                                                    <br>
                                                    <small class="text-success">
                                                        <i class="fas fa-check-circle"></i> Currently enrolled
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <select name="status[{{ $program->id }}]" 
                                                        class="form-select form-select-sm status-select"
                                                        {{ !$isEnrolled ? 'disabled' : '' }}>
                                                    <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="active" {{ $currentStatus == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="completed" {{ $currentStatus == 'completed' ? 'selected' : '' }}>Completed</option>
                                                    <option value="withdrawn" {{ $currentStatus == 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" 
                                                    name="remarks[{{ $program->id }}]" 
                                                    class="form-control form-control-sm remarks-input"
                                                    placeholder="Add remarks"
                                                    value="{{ $currentRemarks }}"
                                                    {{ !$isEnrolled ? 'disabled' : '' }}>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>

                                <button type="submit" class="btn btn-primary">Save Benificiaries</button>
                            </form>
                            @else
                                <div class="fw-semibold">N/A</div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <!-- Health Info -->
            <div class="tab-pane fade" id="healthInfo">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Health Information</h5>

                        <form method="POST" action="{{ route('resident.update', $resident->id) }}">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="updateTab" value="healthInfo">

                            <small class="text-muted">CO-MORBIDITIES</small>
                            
                            <ul class="list-group list-group-flush">
                                @foreach($conditions as $key => $condition)
                                    @php
                                        $hasCondition = $resident->healthProfile->contains('health_condition', $condition);
                                    @endphp
                                    <li class="list-group-item fw-semibold">
                                        <input 
                                            type="checkbox" 
                                            id="condition_{{ $key }}" 
                                            name="condition[]" 
                                            value="{{ $condition }}"
                                            {{ $hasCondition ? 'checked' : '' }}>

                                        <label for="condition_{{ $key }}">
                                            {{ $condition }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>

                            <button type="submit" class="btn btn-primary mt-3">Save Health Info</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const tabMatch = window.location.search.match(/[?&]tab=([^&]+)/);
        const activeTab = tabMatch ? decodeURIComponent(tabMatch[1]) : null;
        if (activeTab && window.bootstrap?.Tab) {
            const activeTabButton = document.querySelector(`[data-bs-target="#${activeTab}"]`);
            if (activeTabButton) {
                new window.bootstrap.Tab(activeTabButton).show();
            }
        }

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