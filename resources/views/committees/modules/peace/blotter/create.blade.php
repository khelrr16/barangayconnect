@extends('layouts.committee')

@section('title', 'Blotter')

@section('content')
    <div class="container py-4">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('committee.peace.blotter.index') }}" class="text-decoration-none text-dark">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>

        <form action="{{ route('committee.peace.blotter.store') }}" method="POST">
            @csrf
            
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="card-title fw-bold ">Barangay Blotter Record</h4>
                </div>

                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <div class="d-flex flex-fill justify-content-between">
                            <button id="nav-basic_info-tab" data-bs-target="#nav-basic_info"
                                class="nav-link {{ session('activeTab') ? '' : 'active' }}  flex-fill text-center" data-bs-toggle="tab" type="button" role="tab">
                                Basic Info
                            </button>

                            <button id="nav-newborn-tab" data-bs-target="#nav-newborn" 
                                class="nav-link {{ session('activeTab') == 'newborn' ? 'active' : '' }} flex-fill text-center" data-bs-toggle="tab" type="button" role="tab">
                                Parties Involved
                            </button>
                            
                            <button id="nav-1-3-tab" data-bs-target="#nav-1-3" 
                                class="nav-link {{ session('activeTab') == 'months_1_3' ? 'active' : '' }} flex-fill text-center" data-bs-toggle="tab" type="button" role="tab">
                                Incident Details
                            </button>
                            
                            <button id="nav-6-11-tab" data-bs-target="#nav-6-11"
                                class="nav-link {{ session('activeTab') == 'months_6_11' ? 'active' : '' }} flex-fill text-center" data-bs-toggle="tab" type="button" role="tab">
                                Actions & Resolutions
                            </button>
                            
                        </div>
                    </div>
                </nav>

                <div class="card-body p-4">
                    <div class="tab-content" id="nav-tabContent">
                        
                        <!-- Basic Info Tab -->
                        <div id="nav-basic_info" class="tab-pane fade {{ session('activeTab') ? '' : 'show active' }}" role="tabpanel">

                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">STATUS</small>
                                    <select required name="status" class="form-select fw-bold">
                                        <option selected disabled>--</option>
                                        <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Under Investigation" {{ old('status') == 'Under Investigation' ? 'selected' : '' }}>Under Investigation</option>
                                        <option value="Resolved" {{ old('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="Dismissed" {{ old('status') == 'Dismissed' ? 'selected' : '' }}>Dismissed</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">INCIDENT DATE</small>
                                    <input required type="datetime-local" name="incident_date" class="form-control fw-bold" value="{{ old('incident_date') }}">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="fw-bold mb-0">
                                        <i class="fa fa-users"></i> Complainant
                                    </h5>
                                </div>

                                <div>
                                    <button class="btn btn-sm btn-primary" type="button">
                                        <i class="fa fa-plus"></i> ADD
                                    </button>
                                </div>
                            </div>

                            <div>
                                <div class="p-3 border rounded-5">
                                    <h6 class="fw-bold mb-3">Respondents 1</h6>

                                    <div class="col-md-6 mb-3">
                                        <div class="mb-3">
                                            <small class="text-muted">FULL NAME</small>
                                            <div class="input-group mb-3">
                                                <button class="input-group-text" id="basic-addon1">
                                                    TEST
                                                </button>
                                                <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Parties Involved Tab -->
                        <div id="nav-parties_involved" class="tab-pane fade {{ session('activeTab') == 'parties_involved' ? 'show active' : '' }}" role="tabpanel">
                            <div class="m-3 p-3 rounded-5 bg-secondary bg-opacity-10">
                                Test
                            </div>
                        </div>

                        <!-- Incident Details Tab -->
                        <div id="nav-incident_details" class="tab-pane fade {{ session('activeTab') == 'incident_details' ? 'show active' : '' }}" role="tabpanel">
                            <div class="m-3 p-3 rounded-5 bg-secondary bg-opacity-10">
                                Test
                            </div>
                        </div>

                        <!-- Actions & Resolution Tab -->
                        <div id="nav-actions_resolutions" class="tab-pane fade {{ session('activeTab') == 'actions_resolutions' ? 'show active' : '' }}" role="tabpanel">
                            <div class="m-3 p-3 rounded-5 bg-secondary bg-opacity-10">
                                Test
                            </div>
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