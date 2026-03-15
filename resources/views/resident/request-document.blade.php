@extends('layouts.resident')

@section('title', 'Request Document')

@section('content')
    <div class="container">
        <h2 class="mb-4">Request Document</h2>

        @if(!$resident)
            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-3">Your account is not linked to a resident record. Contact the barangay office to link your profile before requesting documents.</p>
                    <a href="{{ route('resident.profile') }}" class="btn btn-primary">My Profile</a>
                </div>
            </div>
        @else
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Submit a Request</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('resident.request-document.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="certificate_type" class="form-label">Certificate Type <span class="text-danger">*</span></label>
                            <select name="certificate_type" id="certificate_type" class="form-select @error('certificate_type') is-invalid @enderror" required>
                                <option value="">Select type...</option>
                                @foreach($certificateTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('certificate_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('certificate_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose <span class="text-danger">*</span></label>
                            <textarea name="purpose" id="purpose" class="form-control @error('purpose') is-invalid @enderror" rows="3" maxlength="1000" required>{{ old('purpose') }}</textarea>
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select name="priority" id="priority" class="form-select">
                                <option value="normal" {{ old('priority', 'normal') === 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </form>
                </div>
            </div>

            <h5 class="mb-3">Available Documents</h5>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa-solid fa-file-certificate"></i> Barangay Clearance</h5>
                    <p class="card-text text-muted mb-0">Certificate issued by the barangay for various transactions (e.g. employment, business, travel).</p>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa-solid fa-hand-holding-heart"></i> Certificate of Indigency</h5>
                    <p class="card-text text-muted mb-0">Certifies that the resident belongs to an indigent family for social welfare or assistance programs.</p>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa-solid fa-house-chimney"></i> Certificate of Residency</h5>
                    <p class="card-text text-muted mb-0">Confirms that the person is a bonafide resident of the barangay.</p>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa-solid fa-award"></i> Certificate of Good Moral Character</h5>
                    <p class="card-text text-muted mb-0">Attests to the resident's good moral character, often required for employment or school.</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa-solid fa-id-card"></i> Barangay ID</h5>
                    <p class="card-text text-muted mb-0">Application or renewal of barangay-issued identification.</p>
                </div>
            </div>
        @endif
    </div>
@endsection
