@extends('layouts.admin')

@section('title', 'Generate Certificate of Indigency')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container my-5">
        <a href="{{ route('admin.certificate-requests.index') }}" class="btn btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left"></i> Back to Certificate Requests</a>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Generate Certificate of Indigency</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Select a resident and enter the purpose. The certificate will be generated as a Word document (.docx) for download.</p>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('admin.certificates.indigency.generate') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="resident_id" class="form-label">Resident <span class="text-danger">*</span></label>
                        <select name="resident_id" id="resident_id" class="form-select @error('resident_id') is-invalid @enderror" required style="width: 100%;">
                            <option value="">-- Search or select resident --</option>
                            @if($preselectedResident ?? null)
                                <option value="{{ $preselectedResident->id }}" selected>
                                    {{ $preselectedResident->full_name }}@if($preselectedResident->rbi_no) ({{ $preselectedResident->rbi_no }})@endif
                                </option>
                            @endif
                        </select>
                        @error('resident_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="purpose" class="form-label">Purpose <span class="text-danger">*</span></label>
                        <input type="text" name="purpose" id="purpose" class="form-control @error('purpose') is-invalid @enderror" value="{{ old('purpose') }}" placeholder="e.g. Hospital Expenses, Medical/Financial Assistance" maxlength="500" required>
                        @error('purpose')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-file-word"></i> Generate and download .docx
                    </button>
                </form>
            </div>
        </div>
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
