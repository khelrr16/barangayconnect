@php
    $tab = 'monitoring';
@endphp

<div class="m-3 px-5 py-3 bg-white border rounded-5">
    <div class="d-flex justify-content-end align-items-center text-center">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#{{ $tab }}-addInfo-modal">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
    </div>

    <div class="mb-5 d-flex align-items-center justify-content-around text-center">
        <div>
            <div class="fw-bold p-1">
                STATUS
            </div>
            <h2 class="text-success fw-bold p-1">{{ $infant->status ?? '--' }}</h2>
            <h5 class="fw-bold p-1">
                @if($infant->malnutrition_type == 1)
                    Moderate Acute Malnutrition (MAM)
                @elseif($infant->malnutrition_type == 2)
                    Severe Acute Malnutrition (SAM) without complications
                @endif
            </h5>
        </div>
    </div>

    <div class="fw-bold p-1">REMARKS</div>

    <div class="p-3 border rounded-3">
        {{ $infant->remarks ?? '--' }}
    </div>
</div>

<!-- Additional Info Model -->
<div class="modal fade" id="{{ $tab }}-addInfo-modal" tabindex="-1" aria-hidden="true">
    <form method="POST" action="{{ route('committee.health.immunization.addInfo.update', $infant) }}">
        @csrf
        @method('PATCH')
        <input type="hidden" name="tab" value="{{ $tab }}">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Additional Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">

                    <div class="mb-4 p-3 border rounded-3 bg-secondary bg-opacity-10">
                        <div class="mb-3">
                            <label class="form-label text-muted">Malnutrition Type</label>
                            <select name="malnutrition_type" class="form-control">
                                <option value="" disabled selected>--</option>
                                <option value="1" {{ old('malnutrition_type', $infant->malnutrition_type) == 1 ? 'selected' : '' }}>Moderate Acute Malnutrition (MAM)</option>
                                <option value="2" {{ old('malnutrition_type', $infant->malnutrition_type) == 2 ? 'selected' : '' }}>Severe Acute Malnutrition (SAM) without complications</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">Status</label>
                            <select name="status" class="form-control">
                                <option value="" disabled selected>--</option>
                                <option value="Cured" {{ old('status', $infant->status) == 'Cured' ? 'selected' : '' }}>Cured</option>
                                <option value="Defaulted" {{ old('status', $infant->status) == 'Defaulted' ? 'selected' : '' }}>Defaulted</option>
                                <option value="Died" {{ old('status', $infant->status) == 'Died' ? 'selected' : '' }}>Died</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="5">{{ old('remarks', $infant->remarks) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
