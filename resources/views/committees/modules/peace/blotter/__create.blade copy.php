{{-- resources/views/blotter/create.blade.php --}}
@extends('layouts.committee')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">File a Blotter Report</h4>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('committee.peace.blotter.store') }}" 
                          method="POST" 
                          enctype="multipart/form-data"
                          id="blotterForm">
                        @csrf
                        
                        {{-- Case Type --}}
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Case Type <span class="text-danger">*</span></label>
                                <select name="case_type" class="form-select" required>
                                    <option value="">Select Case Type</option>
                                    <option value="criminal">Criminal Case</option>
                                    <option value="civil">Civil Case</option>
                                    <option value="administrative">Administrative Case</option>
                                    <option value="minor_offense">Minor Offense</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            {{-- Complainant Information --}}
                            <div class="col-md-6">
                                <h5 class="border-bottom pb-2">Complainant Information</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="complainant_name" 
                                           class="form-control @error('complainant_name') is-invalid @enderror"
                                           value="{{ old('complainant_name') }}"
                                           required>
                                    @error('complainant_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="complainant_address" 
                                           class="form-control @error('complainant_address') is-invalid @enderror"
                                           value="{{ old('complainant_address') }}"
                                           required>
                                    @error('complainant_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="complainant_contact" 
                                               class="form-control @error('complainant_contact') is-invalid @enderror"
                                               value="{{ old('complainant_contact') }}"
                                               required>
                                        @error('complainant_contact')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Age</label>
                                        <input type="number" 
                                               name="complainant_age" 
                                               class="form-control"
                                               value="{{ old('complainant_age') }}">
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Sex</label>
                                        <select name="complainant_sex" class="form-select">
                                            <option value="">Select</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Valid ID (Optional)</label>
                                    <input type="file" 
                                           name="complainant_id" 
                                           class="form-control"
                                           accept=".jpg,.jpeg,.png,.pdf">
                                </div>
                            </div>
                            
                            {{-- Respondent Information --}}
                            <div class="col-md-6">
                                <h5 class="border-bottom pb-2">Respondent Information</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="respondent_name" 
                                           class="form-control @error('respondent_name') is-invalid @enderror"
                                           value="{{ old('respondent_name') }}"
                                           required>
                                    @error('respondent_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="respondent_address" 
                                           class="form-control @error('respondent_address') is-invalid @enderror"
                                           value="{{ old('respondent_address') }}"
                                           required>
                                    @error('respondent_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Contact Number</label>
                                        <input type="text" 
                                               name="respondent_contact" 
                                               class="form-control"
                                               value="{{ old('respondent_contact') }}">
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Age</label>
                                        <input type="number" 
                                               name="respondent_age" 
                                               class="form-control"
                                               value="{{ old('respondent_age') }}">
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Sex</label>
                                        <select name="respondent_sex" class="form-select">
                                            <option value="">Select</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Incident Details --}}
                        <h5 class="border-bottom pb-2 mt-4">Incident Details</h5>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of Incident <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="incident_date" 
                                       class="form-control @error('incident_date') is-invalid @enderror"
                                       value="{{ old('incident_date') }}"
                                       required>
                                @error('incident_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Time of Incident</label>
                                <input type="time" 
                                       name="incident_time" 
                                       class="form-control"
                                       value="{{ old('incident_time') }}">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="incident_location" 
                                       class="form-control @error('incident_location') is-invalid @enderror"
                                       value="{{ old('incident_location') }}"
                                       required>
                                @error('incident_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Incident Description <span class="text-danger">*</span></label>
                            <textarea name="incident_description" 
                                      rows="5" 
                                      class="form-control @error('incident_description') is-invalid @enderror"
                                      required>{{ old('incident_description') }}</textarea>
                            @error('incident_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Witnesses --}}
                        <div class="mb-3">
                            <label class="form-label">Witnesses (Separate names with commas)</label>
                            <input type="text" 
                                   name="witnesses" 
                                   class="form-control"
                                   placeholder="e.g., Juan Dela Cruz, Maria Santos"
                                   value="{{ old('witnesses') }}">
                        </div>
                        
                        {{-- Evidence Upload --}}
                        <div class="mb-3">
                            <label class="form-label">Upload Evidence (Photos, Documents, etc.)</label>
                            <input type="file" 
                                   name="evidence[]" 
                                   class="form-control"
                                   multiple
                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                            <small class="text-muted">You can select multiple files</small>
                        </div>
                        
                        {{-- Terms and Agreement --}}
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" 
                                       name="affirm" 
                                       id="affirm"
                                       class="form-check-input @error('affirm') is-invalid @enderror"
                                       required>
                                <label class="form-check-label" for="affirm">
                                    I affirm that the information provided is true and correct to the best of my knowledge. 
                                    I understand that making a false report may subject me to legal consequences.
                                </label>
                                @error('affirm')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger" id="submitBtn">
                                <span class="normal-state">File Blotter Report</span>
                                <span class="loading-state d-none">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Submitting...
                                </span>
                            </button>
                            <a href="{{ route('committee.peace.blotter.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('blotterForm').addEventListener('submit', function() {
    document.getElementById('submitBtn').disabled = true;
    document.querySelector('.normal-state').classList.add('d-none');
    document.querySelector('.loading-state').classList.remove('d-none');
});
</script>
@endpush
@endsection