{{-- resources/views/components/modals/resident-search-modal.blade.php --}}
@props([
'modalId' => 'residentSearchModal',
'title' => 'Search Resident',
'showAdvanced' => true,
'multiple' => false,
'onSelect' => 'fillResidentData',
'filters' => [], // Optional filters like purok, civil status, etc.
'excludeIds' => [], // Residents to exclude from search
])

<div class="modal fade"
    id="{{ $modalId }}"
    tabindex="-1"
    aria-labelledby="{{ $modalId }}Label"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-column-count="{{ $multiple ? 8 : 7 }}"
    data-multiple="{{ $multiple ? 1 : 0 }}"
    data-exclude-ids='@json($excludeIds)'>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="{{ $modalId }}Label">
                    <i class="fa-solid fa-magnifying-glass me-2"></i>
                    {{ $title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                {{-- Search Form --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fa-solid fa-search"></i>
                            </span>
                            <input type="text"
                                class="form-control"
                                id="{{ $modalId }}_residentSearchInput"
                                placeholder="Search by name, ID, or contact..."
                                autocomplete="off">
                        </div>
                    </div>

                    @if($showAdvanced)
                    <div class="col-md-2">
                        <select class="form-select" id="{{ $modalId }}_civilStatusFilter">
                            <option value="">All Status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Separated">Separated</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select class="form-select" id="{{ $modalId }}_ageRangeFilter">
                            <option value="">All Ages</option>
                            <option value="0-17">0-17 (Minor)</option>
                            <option value="18-30">18-30 (Young Adult)</option>
                            <option value="31-50">31-50 (Adult)</option>
                            <option value="51-100">51+ (Senior)</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-secondary w-100" type="button" id="{{ $modalId }}_resetFilters">
                            <i class="fa-solid fa-rotate"></i> Reset
                        </button>
                    </div>
                    @endif
                </div>

                {{-- Results Count --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted" id="{{ $modalId }}_searchResultsCount"></span>
                    <div>
                        <span class="badge bg-info" id="{{ $modalId }}_selectedCount">0 selected</span>
                    </div>
                </div>

                {{-- Results Table --}}
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-bordered" id="{{ $modalId }}_residentSearchResults">
                        <thead class="table-light sticky-top">
                            <tr>
                                @if($multiple)
                                <th width="50">
                                    <input type="checkbox" id="{{ $modalId }}_selectAllResidents">
                                </th>
                                @endif
                                <th width="80">ID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Contact</th>
                                <th>Age/Sex</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody id="{{ $modalId }}_residentSearchResultsBody">
                            <tr>
                                <td colspan="{{ $multiple ? 8 : 7 }}" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fa-solid fa-search fa-2x mb-2"></i>
                                        <p>Type to search residents...</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Loading Indicator --}}
                <div id="{{ $modalId }}_searchLoading" class="text-center py-3" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Searching...</p>
                </div>
            </div>

            <div class="modal-footer">
                <span class="text-muted small me-auto">
                    <i class="fa-regular fa-keyboard"></i> Type at least 2 characters to search
                </span>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-regular fa-circle-xmark"></i> Close
                </button>
                @if($multiple)
                <button type="button" class="btn btn-primary" id="{{ $modalId }}_selectMultipleBtn">
                    <i class="fa-regular fa-check-circle"></i> Select Selected
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .sticky-top {
        top: 0;
        z-index: 10;
    }

    .resident-row {
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .resident-row:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }

    .resident-row.selected {
        background-color: rgba(40, 167, 69, 0.1);
        border-left: 3px solid #28a745;
    }
</style>
@endpush

@push('scripts')
<script>
    // Store the callback function
    window.residentSearchCallbacks = window.residentSearchCallbacks || {};
    const residentSearchModalId = '{{ $modalId }}';
    const modalEl = document.getElementById(residentSearchModalId);
    const searchInputEl = document.getElementById('{{ $modalId }}_residentSearchInput');
    const searchResultsBodyEl = document.getElementById('{{ $modalId }}_residentSearchResultsBody');
    const searchResultsCountEl = document.getElementById('{{ $modalId }}_searchResultsCount');
    const searchLoadingEl = document.getElementById('{{ $modalId }}_searchLoading');
    const columnCount = Number(modalEl?.dataset.columnCount || 7);
    const multiple = (modalEl?.dataset.multiple || '0') === '1';
    const excludeIds = JSON.parse(modalEl?.dataset.excludeIds || '[]');

    // Initialize when modal is shown
    modalEl?.addEventListener('shown.bs.modal', function() {
        searchInputEl?.focus();
    });

    // Search functionality
    let searchTimeout;
    searchInputEl?.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const searchTerm = e.target.value;

        if (searchTerm.length < 2) {
            searchResultsBodyEl.innerHTML = `
            <tr>
                <td colspan="{{ $multiple ? 8 : 7 }}" class="text-center py-4">
                    <div class="text-muted">
                        <i class="fa-solid fa-search fa-2x mb-2"></i>
                        <p>Type at least 2 characters to search...</p>
                    </div>
                </td>
            </tr>
        `;
            return;
        }

        searchTimeout = setTimeout(() => searchResidents(searchTerm), 300);
    });

    // Search function
    function searchResidents(searchTerm) {
        const civilStatus = document.getElementById('{{ $modalId }}_civilStatusFilter')?.value || '';
        const ageRange = document.getElementById('{{ $modalId }}_ageRangeFilter')?.value || '';

        searchLoadingEl.style.display = 'block';

        fetch(`/api/residents/search?term=${encodeURIComponent(searchTerm)}&civil_status=${civilStatus}&age_range=${ageRange}&exclude=${excludeIds.join(',')}`)
            .then(response => response.json())
            .then(data => {
                displayResults(data);
                searchResultsCountEl.textContent = `${data.length} residents found`;
            })
            .catch(error => {
                console.error('Search error:', error);
                showError('Failed to search residents');
            })
            .finally(() => {
                searchLoadingEl.style.display = 'none';
            });
    }

    // Display results
    function displayResults(residents) {
        const tbody = searchResultsBodyEl;
        const modalId = residentSearchModalId;

        if (residents.length === 0) {
            tbody.innerHTML = `
            <tr>
                <td colspan="${multiple ? 8 : 7}" class="text-center py-4">
                    <div class="text-warning">
                        <i class="fa-solid fa-exclamation-circle fa-2x mb-2"></i>
                        <p>No residents found</p>
                    </div>
                </td>
            </tr>
        `;
            return;
        }

        let html = '';
        residents.forEach(resident => {
            const age = calculateAge(resident.birthday);
            const fullName = `${resident.first_name || ''} ${resident.middle_name || ''} ${resident.last_name || ''} ${resident.extension_name || ''}`.replace(/\s+/g, ' ').trim();
            const address = resident.household?.address || resident.address || 'N/A';
            const contactNumber = resident.contact_number || 'N/A';
            const residentLabel = resident.rbi_no || resident.id;

            html += `
            <tr class="resident-row">
                ${multiple ? `
                    <td class="text-center">
                        <input type="checkbox" class="resident-checkbox" value="${resident.id}">
                    </td>
                ` : ''}
                <td>${residentLabel}</td>
                <td><strong>${fullName}</strong></td>
                <td>${address}</td>
                <td>${contactNumber}</td>
                <td>${age} / ${resident.sex || 'N/A'}</td>
                <td>
                    ${!multiple ? `
                        <button class="btn btn-sm btn-primary" onclick="selectResident(${resident.id}, '${modalId}')">
                            <i class="fa-regular fa-check"></i> Select
                        </button>
                    ` : ''}
                </td>
            </tr>
        `;
        });

        tbody.innerHTML = html;
    }

    // Select single resident
    window.selectResident = function(residentId, modalId) {
        fetch(`/api/residents/${residentId}`)
            .then(response => response.json())
            .then(resident => {
                // Find the callback for this modal
                const callback = window.residentSearchCallbacks[modalId];
                if (callback) {
                    callback(resident);
                }

                // Close modal
                bootstrap.Modal.getOrCreateInstance(document.getElementById(modalId)).hide();
            });
    }

    // Calculate age
    function calculateAge(birthDate) {
        if (!birthDate) return 'N/A';
        const today = new globalThis.Date();
        const birth = new globalThis.Date(birthDate);
        if (Number.isNaN(birth.getTime())) return 'N/A';
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        return age;
    }

    // Show error
    function showError(message) {
        const tbody = searchResultsBodyEl;
        tbody.innerHTML = `
        <tr>
            <td colspan="${columnCount}" class="text-center py-4">
                <div class="text-danger">
                    <i class="fa-solid fa-circle-exclamation fa-2x mb-2"></i>
                    <p>${message}</p>
                </div>
            </td>
        </tr>
    `;
    }
</script>
@endpush