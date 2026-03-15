@extends('layouts.admin')

@section('title', 'Verification Request')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container my-5">
        <a href="{{ route('admin.verification-requests.index') }}" class="btn btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left"></i> Back to list</a>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">User requesting link</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $verification->user->name }}</p>
                <p><strong>Email:</strong> {{ $verification->user->email }}</p>
                <p><strong>Submitted:</strong> {{ $verification->created_at->format('F j, Y g:i A') }}</p>
                <p><strong>Status:</strong>
                    @if($verification->status === 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($verification->status === 'approved')
                        <span class="badge bg-success">Approved</span>
                    @else
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </p>
                @if($verification->remarks)
                    <p><strong>Remarks:</strong> {{ $verification->remarks }}</p>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Uploaded verification ID</h5>
            </div>
            <div class="card-body">
                @php
                    $path = 'storage/' . $verification->document_path;
                    $ext = strtolower(pathinfo($verification->document_path, PATHINFO_EXTENSION));
                @endphp
                @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif']))
                    <img src="{{ asset($path) }}" alt="Verification ID" class="img-fluid rounded border" style="max-height: 400px;">
                @else
                    <p><a href="{{ asset($path) }}" target="_blank" class="btn btn-outline-primary"><i class="fa-solid fa-file-pdf"></i> Open PDF</a></p>
                @endif
                <p class="text-muted small mt-2 mb-0">File: {{ basename($verification->document_path) }}</p>
            </div>
        </div>

        @if($verification->status === 'pending')
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Link to resident</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.verification-requests.approve', $verification) }}" method="POST" class="mb-4">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="resident_id" class="form-label">Select resident to link <span class="text-danger">*</span></label>
                            <select name="resident_id" id="resident_id" class="form-select" required>
                                <option value="">-- Select resident --</option>
                                {{-- @foreach($residents as $r)
                                    <option value="{{ $r->id }}">{{ $r->full_name }} ({{ $r->rbi_no }})</option>
                                @endforeach --}}
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="remarks_approve" class="form-label">Remarks (optional)</label>
                            <textarea name="remarks" id="remarks_approve" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fa-solid fa-link"></i> Approve & link to resident</button>
                    </form>

                    <hr>
                    <form action="{{ route('admin.verification-requests.reject', $verification) }}" method="POST" onsubmit="return confirm('Reject this verification? The user may submit a new ID.');">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="remarks_reject" class="form-label">Reason for rejection (optional)</label>
                            <textarea name="remarks" id="remarks_reject" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-times"></i> Reject</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        (function () {
            var $ = window.jQuery || window.$;
            if (typeof $ === 'undefined') return;
            $(function () {
                var $select = $('#resident_id');
                if ($select.length) {
                    $select.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder: 'Type to search resident',
                        allowClear: true,
                        minimumInputLength: 0,
                        ajax: {
                            url: '{{ route('admin.certificates.residents-search') }}',
                            dataType: 'json',
                            delay: 200,
                            data: function (params) {
                                return { q: params.term, limit: 5 };
                            },
                            processResults: function (data) {
                                return { results: data.results };
                            }
                        }
                    });
                }
            });
        })();
    </script>
@endpush
