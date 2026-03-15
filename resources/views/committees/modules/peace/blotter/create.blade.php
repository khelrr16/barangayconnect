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

        <div class="card shadow-sm p-4">
            <div class="card-header bg-white border-0">
                <h2 class="fw-bolder">
                    <i class="fa-solid fa-scale-balanced"></i> Blotter Case
                </h2>
            </div>

            <hr>

            <div class="card-body">
                <form action="{{ route('committee.peace.blotter.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="p-4 rounded-4 bg-secondary bg-opacity-10 flex-fill">
                            <h5 class="fw-bolder">Complainant's Information</h5>
                            <hr>
                            <div class="mb-3">
                                <label for="complainant_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('complainant_name') is-invalid @enderror" id="complainant_name" name="complainant_name" value="{{ old('complainant_name') }}" required>
                                @error('complainant_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="complainant_contact" class="form-label">Contact Number</label>
                                <input type="text" class="form-control @error('complainant_contact') is-invalid @enderror" id="complainant_contact" name="complainant_contact" value="{{ old('complainant_contact') }}">
                                @error('complainant_contact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="complainant_address" class="form-label">Address</label>
                                <textarea class="form-control @error('complainant_address') is-invalid @enderror" id="complainant_address" name="complainant_address" rows="3" required>{{ old('complainant_address') }}</textarea>
                                @error('complainant_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="p-4 rounded-4 bg-secondary bg-opacity-10 flex-fill">
                            <h5 class="fw-bolder">Respondent's Information</h5>
                            <hr>
                            <div class="mb-3">
                                <label for="respondent_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('respondent_name') is-invalid @enderror" id="respondent_name" name="respondent_name" value="{{ old('respondent_name') }}" required>
                                @error('respondent_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="respondent_contact" class="form-label">Contact Number (Optional)</label>
                                <input type="text" class="form-control @error('respondent_contact') is-invalid @enderror" id="respondent_contact" name="respondent_contact" value="{{ old('respondent_contact') }}">
                                @error('respondent_contact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="respondent_address" class="form-label">Address</label>
                                <textarea class="form-control @error('respondent_address') is-invalid @enderror" id="respondent_address" name="respondent_address" rows="3" required>{{ old('respondent_address') }}</textarea>
                                @error('respondent_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="p-4 rounded-4 bg-secondary bg-opacity-10 mb-4">
                        <h5 class="fw-bolder">Case Details</h5>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center gap-5 mb-4">
                            <div class="flex-fill">
                                <div class="mb-3">
                                    <label for="case_type" class="form-label">Case Type</label>
                                    <select name="case_type" id="case_type" class="form-control @error('case_type') is-invalid @enderror" required>
                                        <option value="">Select Case Type</option>
                                        <option value="Property Dispute" @selected(old('case_type') === 'Property Dispute')>Property Dispute</option>
                                        <option value="Boundary Dispute" @selected(old('case_type') === 'Boundary Dispute')>Boundary Dispute</option>
                                        <option value="Noise Complaint" @selected(old('case_type') === 'Noise Complaint')>Noise Complaint</option>
                                        <option value="Debt/Money Claim" @selected(old('case_type') === 'Debt/Money Claim')>Debt/Money Claim</option>
                                        <option value="Family Dispute" @selected(old('case_type') === 'Family Dispute')>Family Dispute</option>
                                        <option value="Physical Injury (Minor)" @selected(old('case_type') === 'Physical Injury (Minor)')>Physical Injury (Minor)</option>
                                        <option value="Verbal Altercation" @selected(old('case_type') === 'Verbal Altercation')>Verbal Altercation</option>
                                        <option value="Animal Concern" @selected(old('case_type') === 'Animal Concern')>Animal Concern</option>
                                        <option value="Tree/Plant Dispute" @selected(old('case_type') === 'Tree/Plant Dispute')>Tree/Plant Dispute</option>
                                        <option value="Water Rights" @selected(old('case_type') === 'Water Rights')>Water Rights</option>
                                        <option value="Business Dispute" @selected(old('case_type') === 'Business Dispute')>Business Dispute</option>
                                        <option value="Neighborhood Quarrel" @selected(old('case_type') === 'Neighborhood Quarrel')>Neighborhood Quarrel</option>
                                        <option value="Others" @selected(old('case_type') === 'Others')>Others</option>
                                    </select>
                                    @error('case_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex-fill">
                                <div class="mb-3">
                                    <label for="date_filled" class="form-label">Date Filled</label>
                                    <input type="date" class="form-control @error('date_filled') is-invalid @enderror" id="date_filled" name="date_filled" value="{{ old('date_filled') }}" required>
                                    @error('date_filled')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="case_description" class="form-label">Case Description</label>
                            <textarea class="form-control @error('case_description') is-invalid @enderror" id="case_description" name="case_description" rows="3" required>{{ old('case_description') }}</textarea>
                            @error('case_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center gap-5 mb-4">
                            <div class="flex-fill">
                                <div class="mb-3">
                                    <label for="witnesses" class="form-label">Witnesses</label>
                                    <input type="text" class="form-control @error('witnesses') is-invalid @enderror" id="witnesses" name="witnesses" value="{{ old('witnesses') }}">
                                    @error('witnesses')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex-fill">
                                <div class="mb-3">
                                    <label for="evidence" class="form-label">Evidence / Attachments (Optional)</label>
                                    <input type="file" class="form-control @error('evidence') is-invalid @enderror @error('evidence.*') is-invalid @enderror" id="evidence" name="evidence[]" multiple>
                                    @error('evidence')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @error('evidence.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="p-4 rounded-4 bg-secondary bg-opacity-10 mb-4">
                        <h5 class="fw-bolder">Assignment & Hearing</h5>
                        <hr>

                        <div class="flex-fill">
                            <div class="mb-3">
                                <label for="assigned_official_id" class="form-label">Assigned Lupon Official</label>
                                <select name="assigned_official_id" id="assigned_official_id" class="form-control @error('assigned_official_id') is-invalid @enderror">
                                    <option value="">Select Official</option>
                                    @foreach($officials as $official)
                                        <option value="{{ $official->id }}" @selected(old('assigned_official_id') == $official->id)>{{ $official->name }}</option>
                                    @endforeach
                                </select>
                                @error('assigned_official_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center gap-5 mb-4">
                            <div class="mb-3 flex-fill">
                                <label for="case_description" class="form-label">Hearing Date Time</label>
                                <input type="datetime-local" class="form-control @error('hearing_datetime') is-invalid @enderror" id="hearing_datetime" name="hearing_datetime" value="{{ old('hearing_datetime') }}">
                                @error('hearing_datetime')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 flex-fill">
                                <label for="hearing_venue" class="form-label">Venue</label>
                                <input type="text" class="form-control @error('hearing_venue') is-invalid @enderror" id="hearing_venue" name="hearing_venue" value="{{ old('hearing_venue') }}">
                                @error('hearing_venue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('committee.peace.blotter.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-paper-plane"></i> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
