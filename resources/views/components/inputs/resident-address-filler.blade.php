{{-- resources/views/components/inputs/resident-address-filler.blade.php --}}
@props([
'residentId' => null,
'showComplete' => true,
'showStreet' => true,
'showPurok' => true,
'showBarangay' => true,
'prefix' => 'address',
])

<div class="resident-address-filler" data-prefix="{{ $prefix }}">
    @if($showComplete)
    <div class="mb-2">
        <label class="form-label">Complete Address</label>
        <input type="text"
            name="{{ $prefix }}[complete]"
            class="form-control"
            id="{{ $prefix }}_complete"
            readonly
            value="{{ old($prefix . '.complete') }}">
    </div>
    @endif

    <div class="row g-2">
        @if($showStreet)
        <div class="col-md-4">
            <label class="form-label">Street/Block/Lot</label>
            <input type="text"
                name="{{ $prefix }}[street]"
                class="form-control"
                id="{{ $prefix }}_street"
                value="{{ old($prefix . '.street') }}">
        </div>
        @endif

        @if($showPurok)
        <div class="col-md-3">
            <label class="form-label">Purok</label>
            <input type="text"
                name="{{ $prefix }}[purok]"
                class="form-control"
                id="{{ $prefix }}_purok"
                value="{{ old($prefix . '.purok') }}">
        </div>
        @endif

        @if($showBarangay)
        <div class="col-md-3">
            <label class="form-label">Barangay</label>
            <input type="text"
                name="{{ $prefix }}[barangay]"
                class="form-control"
                id="{{ $prefix }}_barangay"
                value="San Jose" readonly>
        </div>
        @endif

        <div class="col-md-2 d-flex align-items-end">
            <button type="button"
                class="btn btn-sm btn-outline-primary w-100"
                onclick="fillFromResident('{{ $residentId }}', '{{ $prefix }}')">
                <i class="fa-regular fa-rotate"></i> Refresh
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.fillFromResident = function(residentId, prefix) {
        if (!residentId) {
            residentId = document.querySelector('input[name="resident_id"]')?.value || '';
        }

        if (!residentId) return;

        fetch(`/api/residents/${residentId}/address`)
            .then(response => response.json())
            .then(data => {
                if (data.complete) {
                    document.getElementById(`${prefix}_complete`).value = data.complete;
                }
                if (data.street) {
                    document.getElementById(`${prefix}_street`).value = data.street;
                }
                if (data.purok) {
                    document.getElementById(`${prefix}_purok`).value = data.purok;
                }
            });
    }
</script>
@endpush