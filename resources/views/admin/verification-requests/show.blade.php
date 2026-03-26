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
                <div class="d-flex justify-content-between">
                    <h5 class="mb-0">Link Verification Requests</h5>
                    <div class="fs-5">
                        @if($verification->status === 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($verification->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="ps-5">
                                
                        <div class="mb-3">
                            <small class="text-muted">NAME</small>
                                <div class="fw-semibold">{{ $verification->user->name }}</div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">EMAIL</small>
                                <div class="fw-semibold">{{ $verification->user->email }}</div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">SUBMITTED</small>
                                <div class="fw-semibold">{{ $verification->created_at->format('F j, Y g:i A') }}</div>
                        </div>
                    </div>
                    <div>
                        @php
                            $path = 'storage/' . $verification->document_path;
                            $ext = strtolower(pathinfo($verification->document_path, PATHINFO_EXTENSION));
                        @endphp
                        @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ asset($path) }}" alt="Verification ID" class="img-fluid rounded border" style="max-height: 300px;">
                        @else
                            <p><a href="{{ asset($path) }}" target="_blank" class="btn btn-outline-primary"><i class="fa-solid fa-file-pdf"></i> Open PDF</a></p>
                        @endif
                        <p class="text-muted small mt-2 mb-0">File: {{ basename($verification->document_path) }}</p></div>
                    </div>
                </div>

                @if($verification->status === 'pending')
                <div class="p-5 d-flex justify-content-center gap-5">
                    
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#acceptModal">
                            Accept
                        </button>

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            Reject
                        </button>
                </div>
                @endif
            </div>

        </div>
    </div>

    @if($verification->status === 'pending')
        <!-- Accept -->
        <div class="modal fade" id="acceptModal" tabindex="-1" aria-labelledby="acceptModalLabel" aria-hidden="true">
            <form action="{{ route('admin.verification-requests.approve', $verification) }}" method="POST" class="mb-4">
                @csrf
                @method('PATCH')
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="acceptModalLabel">Accept Verification</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            
                            <div class="mb-3">
                                <label for="resident_id" class="form-label">Select resident to link <span class="text-danger">*</span></label>
                                <select name="resident_id" id="resident_id" class="form-select" required>
                                    <option value="">-- Select resident --</option>
                                    {{-- @foreach($residents as $r)
                                        <option value="{{ $r->id }}">{{ $r->full_name }} ({{ $r->rbi_no }})</option>
                                    @endforeach --}}
                                </select>
                            </div>

                            <div id="resident-info-card" class="border rounded p-3 mb-3 bg-light d-none">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Selected Resident Information</h6>
                                    <span id="resident-info-rbi" class="badge bg-secondary"></span>
                                </div>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <small class="text-muted">NAME:</small>
                                        <div id="resident-info-name" class="fw-semibold"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">SEX:</small>
                                        <div id="resident-info-sex"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">CIVIL STATUS:</small>
                                        <div id="resident-info-civil-status"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">BIRTHDAY:</small>
                                        <div id="resident-info-birthday"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">AGE:</small>
                                        <div id="resident-info-age"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">CONTACT:</small>
                                        <div id="resident-info-contact"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">EMAIL:</small>
                                        <div id="resident-info-email"></div>
                                    </div>
                                    <div class="col-12">
                                        <small class="text-muted">ADDRESS:</small>
                                        <div id="resident-info-address"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="remarks_approve" class="form-label">Remarks (optional)</label>
                                <textarea name="remarks" id="remarks_approve" class="form-control" rows="2"></textarea>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Accept</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Reject -->
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <form action="{{ route('admin.verification-requests.reject', $verification) }}" method="POST" onsubmit="return confirm('Reject this verification? The user may submit a new ID.');">
                @csrf
                @method('PATCH')
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="rejectModalLabel">Reject Verification</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="remarks_reject" class="form-label">Reason for rejection (optional)</label>
                                <textarea name="remarks" id="remarks_reject" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif
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
                var $acceptModal = $('#acceptModal');
                var $residentInfoCard = $('#resident-info-card');
                if ($select.length) {
                    function fillText(selector, value) {
                        $(selector).text(value || 'N/A');
                    }

                    function renderResidentInfo(data) {
                        if (!data || !data.id) {
                            $residentInfoCard.addClass('d-none');
                            $('#resident-info-rbi').text('');
                            fillText('#resident-info-name', '');
                            fillText('#resident-info-sex', '');
                            fillText('#resident-info-civil-status', '');
                            fillText('#resident-info-birthday', '');
                            fillText('#resident-info-age', '');
                            fillText('#resident-info-contact', '');
                            fillText('#resident-info-email', '');
                            fillText('#resident-info-address', '');
                            return;
                        }

                        $('#resident-info-rbi').text(data.rbi_no || 'No RBI');
                        fillText('#resident-info-name', data.full_name || data.text);
                        fillText('#resident-info-sex', data.sex);
                        fillText('#resident-info-civil-status', data.civil_status);
                        fillText('#resident-info-birthday', data.birthday);
                        fillText('#resident-info-age', data.age);
                        fillText('#resident-info-contact', data.contact_number);
                        fillText('#resident-info-email', data.email);
                        fillText('#resident-info-address', data.address);
                        $residentInfoCard.removeClass('d-none');
                    }

                    $select.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        dropdownParent: $acceptModal,
                        placeholder: 'Type to search resident',
                        allowClear: true,
                        minimumInputLength: 0,
                        ajax: {
                            url: "{{ route('admin.certificates.residents-search') }}",
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

                    $select.on('select2:select', function (event) {
                        renderResidentInfo(event.params && event.params.data ? event.params.data : null);
                    });

                    $select.on('select2:clear', function () {
                        renderResidentInfo(null);
                    });

                    $acceptModal.on('shown.bs.modal', function () {
                        $select.select2('open');
                        setTimeout(function () {
                            $('.select2-container--open .select2-search__field').trigger('focus');
                        }, 0);
                    });

                    $acceptModal.on('hidden.bs.modal', function () {
                        renderResidentInfo(null);
                    });
                }
            });
        })();
    </script>
@endpush
