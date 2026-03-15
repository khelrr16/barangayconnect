{{-- resources/views/components/inputs/resident-selector.blade.php --}}
@props([
'name' => 'resident_id',
'label' => 'Resident',
'required' => false,
'resident' => null,
'modalId' => 'residentSearchModal',
'onSelect' => 'fillResidentData',
'showAddress' => true,
'showContact' => true,
'inputId' => null,
])

@php
$inputId = $inputId ?? 'resident_' . uniqid();
$containerId = 'container_' . $inputId;
@endphp

<div class="resident-selector"
    id="{{ $containerId }}"
    data-show-address="{{ $showAddress ? 1 : 0 }}"
    data-show-contact="{{ $showContact ? 1 : 0 }}"
    data-on-select="{{ $onSelect }}"
    data-input-id="{{ $inputId }}"
    data-container-id="{{ $containerId }}">
    <label class="form-label fw-bold">
        {{ $label }}
        @if($required)
        <span class="text-danger">*</span>
        @endif
    </label>

    <div class="row g-2">
        <div class="col-md-8">
            <div class="input-group">
                <input type="text"
                    id="{{ $inputId }}_display"
                    class="form-control bg-light"
                    value="{{ $resident ? $resident->full_name : '' }}"
                    placeholder="Select a resident"
                    readonly>
                <button type="button"
                    class="btn btn-primary"
                    onclick="openResidentSearch('{{ $modalId }}', '{{ $containerId }}', '{{ $onSelect }}')">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Browse
                </button>
                <button type="button"
                    class="btn btn-outline-secondary"
                    onclick="clearResident('{{ $containerId }}')">
                    <i class="fa-regular fa-circle-xmark"></i>
                </button>
            </div>
        </div>

        <div class="col-md-4">
            <input type="hidden"
                name="{{ $name }}"
                id="{{ $inputId }}_id"
                value="{{ $resident->id ?? '' }}">
            <input type="hidden"
                id="{{ $inputId }}_data"
                value="{{ $resident ? json_encode($resident) : '' }}">
        </div>
    </div>

    {{-- Resident Details Preview --}}
    @if($showAddress || $showContact)
    <div class="resident-preview mt-2 p-2 bg-light rounded"
        id="{{ $inputId }}_preview"
        @if(!$resident) style="display: none;" @endif>
        <div class="row small">
            @if($showAddress)
            <div class="col-md-6">
                <span class="text-muted">
                    <i class="fa-regular fa-location-dot"></i>
                    <span id="{{ $inputId }}_address">
                        {{ $resident->household->address ?? '' }}
                    </span>
                </span>
            </div>
            @endif

            @if($showContact)
            <div class="col-md-6">
                <span class="text-muted">
                    <i class="fa-regular fa-phone"></i>
                    <span id="{{ $inputId }}_contact">{{ $resident->contact_number ?? '' }}</span>
                </span>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Store component instances
    window.residentSelectors = window.residentSelectors || {};

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        const root = document.getElementById('{{ $containerId }}');
        if (!root) return;

        window.residentSelectors['{{ $containerId }}'] = {
            inputId: root.dataset.inputId,
            containerId: root.dataset.containerId,
            onSelectCallback: root.dataset.onSelect,
            showAddress: root.dataset.showAddress === '1',
            showContact: root.dataset.showContact === '1'
        };
    });

    // Open resident search
    window.openResidentSearch = function(modalId, containerId, callbackName) {
        const selector = window.residentSelectors[containerId];

        // Store callback for this modal
        window.residentSearchCallbacks = window.residentSearchCallbacks || {};
        window.residentSearchCallbacks[modalId] = function(resident) {
            fillResidentFields(containerId, resident, callbackName);
        };

        // Open modal
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    // Fill resident fields
    window.fillResidentFields = function(containerId, resident, callbackName) {
        const selector = window.residentSelectors[containerId];
        if (!selector) return;

        const inputId = selector.inputId;

        // Update display
        document.getElementById(`${inputId}_display`).value =
            `${resident.first_name} ${resident.middle_name || ''} ${resident.last_name} ${resident.suffix || ''}`.trim();
        document.getElementById(`${inputId}_id`).value = resident.id;
        document.getElementById(`${inputId}_data`).value = JSON.stringify(resident);

        // Update preview
        if (selector.showAddress) {
            const addressEl = document.getElementById(`${inputId}_address`);
            if (addressEl) {
                addressEl.textContent = `${resident.household.address || ''}`;
            }
        }

        if (selector.showContact) {
            const contactEl = document.getElementById(`${inputId}_contact`);
            if (contactEl) {
                contactEl.textContent = resident.contact_number || '';
            }
        }

        // Show preview
        const previewEl = document.getElementById(`${inputId}_preview`);
        if (previewEl) {
            previewEl.style.display = 'block';
        }

        // Call custom callback if provided
        if (callbackName && callbackName !== 'fillResidentData') {
            try {
                window[callbackName](resident, containerId);
            } catch (e) {
                console.warn('Custom callback not found:', callbackName);
            }
        }
    }

    // Clear resident
    window.clearResident = function(containerId) {
        const selector = window.residentSelectors[containerId];
        if (!selector) return;

        const inputId = selector.inputId;

        document.getElementById(`${inputId}_display`).value = '';
        document.getElementById(`${inputId}_id`).value = '';
        document.getElementById(`${inputId}_data`).value = '';

        const previewEl = document.getElementById(`${inputId}_preview`);
        if (previewEl) {
            previewEl.style.display = 'none';
        }
    }

    // Default callback - can be overridden
    window.fillResidentData = function(resident, containerId) {
        console.log('Resident selected:', resident);
        // You can add custom logic here
    }
</script>
@endpush