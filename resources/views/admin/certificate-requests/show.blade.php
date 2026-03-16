@extends('layouts.admin')

@section('title', 'Certificate Request')

@section('content')
    <div class="container my-5">
        <a href="{{ route('admin.certificate-requests.index') }}" class="btn btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left"></i> Back to list</a>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Request details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tracking code:</strong> {{ $certificate_request->tracking_code }}</p>
                        <p><strong>Certificate type:</strong> {{ \App\Models\CertificateRequest::TYPES[$certificate_request->certificate_type] ?? $certificate_request->certificate_type }}</p>
                        <p><strong>Priority:</strong> {{ ucfirst($certificate_request->priority) }}</p>
                        <p><strong>Status:</strong>
                            @if($certificate_request->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($certificate_request->status === 'approved')
                                <span class="badge bg-info">Approved</span>
                            @else
                                <span class="badge bg-success">Released</span>
                            @endif
                        </p>
                        <p><strong>Submitted:</strong> {{ $certificate_request->submitted_at?->format('F j, Y g:i A') }}</p>
                        @if($certificate_request->approved_at)
                            <p><strong>Approved:</strong> {{ $certificate_request->approved_at->format('F j, Y') }}</p>
                        @endif
                        @if($certificate_request->released_at)
                            <p><strong>Released:</strong> {{ $certificate_request->released_at->format('F j, Y') }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <p><strong>Purpose:</strong></p>
                        <p class="text-muted">{{ nl2br(e($certificate_request->purpose)) }}</p>
                        @if($certificate_request->remarks)
                            <p><strong>Remarks:</strong> {{ $certificate_request->remarks }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Resident</h5>
            </div>
            <div class="card-body">
                @if($certificate_request->resident)
                    <p><strong>Name:</strong> {{ $certificate_request->resident->full_name }}</p>
                    <p><strong>RBI No:</strong> {{ $certificate_request->resident->rbi_no }}</p>
                    <p><strong>Contact:</strong> {{ $certificate_request->resident->contact_number }}</p>
                    <p><strong>Email:</strong> {{ $certificate_request->resident->email }}</p>
                    @if($certificate_request->resident->household)
                        <p><strong>Household:</strong> {{ $certificate_request->resident->household->household_no }} — {{ $certificate_request->resident->household->first_address ?? $certificate_request->resident->household->blk_lot_unit }}</p>
                    @endif
                @else
                    <p class="text-muted">Resident record not found.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Update status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.certificate-requests.update', $certificate_request) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="pending" {{ $certificate_request->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $certificate_request->status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="released" {{ $certificate_request->status === 'released' ? 'selected' : '' }}>Released</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea name="remarks" id="remarks" class="form-control" rows="2">{{ old('remarks', $certificate_request->remarks) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
