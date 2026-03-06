@extends('layouts.admin')

@section('title', 'Registry of Brgy. Inhabitants')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-3">Registry of Brgy. Inhabitants (RBI)</h4>
        </div>

        <div class="card shadow-sm card-custom mb-3">
            <div class="card-body">
                <form id="csvImportForm" method="POST" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-md-6">
                        <label for="csv_file" class="form-label mb-1">Upload Residents CSV</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" id="csvImportBtn" class="btn btn-warning w-100">Upload CSV</button>
                    </div>
                </form>

                <div id="csvImportProgressWrapper" class="mt-3 d-none">
                    <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                        <div id="csvImportProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%">0%</div>
                    </div>
                    <div id="csvImportProgressText" class="small mt-2 text-muted">Waiting...</div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm card-custom">
            @if($imports->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" id="sortTable">
                    <thead class="text-center">
                        <tr>
                            <th>NO.</th>
                            <th>UPLOADED BY</th>
                            <th>TOTAL ROWS</th>
                            <th>SUCCESS ROWS</th>
                            <th>FAILED ROWS</th>
                            <th>ERROR MESSAGE</th>
                            <th>UPLOADED ON</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        @foreach ($imports as $index => $import)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $import->createdByUser->name }}</td>
                                <td>{{ $import->total_rows }}</td>
                                <td>{{ $import->success_rows }}</td>
                                <td>{{ $import->failed_rows }}</td>
                                <td>{{ $import->error_message ?? 'N/A' }}</td>
                                <td>{{ $import->created_at->format('Y-m-d H:i:s') }}</td></td>
                                <td>
                                    @if($import->status === 'queued')
                                        <span class="badge bg-secondary">Queued</span>
                                    @elseif($import->status === 'processing')
                                        <span class="badge bg-primary">Processing</span>
                                    @elseif($import->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($import->status === 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($import->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($import->failed_rows > 0)
                                        <a href="{{ route('admin.rbi.upload.failed', $import->id) }}" class="btn btn-danger btn-sm">FAILED RECORDS</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="card-body text-center text-muted">
                No results.
            </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let progressInterval = null;

            if (window.DataTable) {
                new window.DataTable('#sortTable', {
                    responsive: true,
                    paging: true,
                    pageLength: 20
                });
            }

            const form = document.getElementById('csvImportForm');
            const uploadButton = document.getElementById('csvImportBtn');
            const progressWrapper = document.getElementById('csvImportProgressWrapper');
            const progressBar = document.getElementById('csvImportProgressBar');
            const progressText = document.getElementById('csvImportProgressText');
            const statusUrlTemplate = "{{ route('admin.rbi.upload.status', ['importId' => '__IMPORT_ID__']) }}";
            const uploadUrl = "{{ route('admin.rbi.upload.csv') }}";

            const setProgress = (percentage, text, isError = false) => {
                const safePercentage = Math.max(0, Math.min(100, Number(percentage) || 0));
                progressBar.style.width = `${safePercentage}%`;
                progressBar.textContent = `${safePercentage.toFixed(0)}%`;
                progressText.classList.toggle('text-danger', isError);
                progressText.classList.toggle('text-muted', !isError);
                progressText.textContent = text;
            };

            const startPolling = (importId) => {
                if (progressInterval) {
                    clearInterval(progressInterval);
                }

                const statusUrl = statusUrlTemplate.replace('__IMPORT_ID__', String(importId));

                const loadStatus = async () => {
                    try {
                        const response = await fetch(statusUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Unable to fetch import status.');
                        }

                        const data = await response.json();
                        const text = `Status: ${data.status} | Processed: ${data.processed_rows}/${data.total_rows} | Success: ${data.success_rows} | Failed: ${data.failed_rows}`;

                        setProgress(data.progress, text, data.status === 'failed');

                        if (data.status === 'completed' || data.status === 'failed') {
                            clearInterval(progressInterval);
                            progressInterval = null;
                            uploadButton.disabled = false;

                            if (data.status === 'completed') {
                                setProgress(100, `${text} | Refreshing page...`);
                                setTimeout(() => window.location.reload(), 800);
                                return;
                            }

                            if (data.status === 'failed' && data.error_message) {
                                setProgress(100, `${text} | Error: ${data.error_message}`, true);
                            }
                        }
                    } catch (error) {
                        clearInterval(progressInterval);
                        progressInterval = null;
                        uploadButton.disabled = false;
                        setProgress(0, error.message || 'Import status polling failed.', true);
                    }
                };

                loadStatus();
                progressInterval = setInterval(loadStatus, 2000);
            };

            form?.addEventListener('submit', async function (event) {
                event.preventDefault();

                const fileInput = document.getElementById('csv_file');
                if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                    setProgress(0, 'Please choose a CSV file first.', true);
                    progressWrapper.classList.remove('d-none');
                    return;
                }

                uploadButton.disabled = true;
                progressWrapper.classList.remove('d-none');
                setProgress(0, 'Uploading CSV...');

                const formData = new window.FormData(form);

                try {
                    const response = await fetch(uploadUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        throw new Error(errorData.message || 'CSV upload failed.');
                    }

                    const data = await response.json();
                    setProgress(0, 'Upload successful. Import queued...');
                    startPolling(data.import_id);
                } catch (error) {
                    uploadButton.disabled = false;
                    setProgress(0, error.message || 'Failed to upload CSV.', true);
                }
            });
        });
    </script>
@endpush
