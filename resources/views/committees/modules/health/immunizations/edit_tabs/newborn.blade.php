@php
    $tabKey = 'newborn';
    $assessment = $assessments->get($tabKey);
    $rows = ['BCG', 'Hepa B-BD'];
    $doseNumber = '1st';
@endphp

<form method="POST" action="{{ route('committee.health.immunization.update', $infant) }}">
    @csrf
    @method('PATCH')
    <input type="hidden" name="tab" value="{{ $tabKey }}">

    <div class="m-3 bg-white border rounded-5">
        <div class="p-3 bg-warning bg-opacity-10 border border-warning border-3 border-bottom rounded-top-5">
            <h5 class="fw-bold mb-0">
                <i class="fa-solid fa-utensils"></i>
                Nutritional Status Assessment
            </h5>
        </div>

        <div class="p-3 border border-warning border-3 border-top-0 rounded-bottom-5">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-muted">Age in weeks</label>
                    <input type="text" name="assessment[age]" class="form-control" value="{{ old('assessment.age', $assessment?->age) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted">Length (cm)</label>
                    <input type="number" step="0.01" min="0" name="assessment[length]" class="form-control" value="{{ old('assessment.length', $assessment?->length) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted">Weight (kg)</label>
                    <input type="number" step="0.01" min="0" name="assessment[weight]" class="form-control" value="{{ old('assessment.weight', $assessment?->weight) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted">Status</label>
                    <select name="assessment[status]" class="form-control">
                        <option value="">--</option>
                        <option value="Low" {{ old('assessment.status', $assessment?->status) === 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Normal" {{ old('assessment.status', $assessment?->status) === 'Normal' ? 'selected' : '' }}>Normal</option>
                        <option value="Unknown" {{ old('assessment.status', $assessment?->status) === 'Unknown' ? 'selected' : '' }}>Unknown</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted">Assessment date</label>
                    <input type="date" name="assessment[assessment_date]" class="form-control" value="{{ old('assessment.assessment_date', optional($assessment?->assessment_date)->format('Y-m-d')) }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="m-3 p-3 bg-white border rounded-5">
        <div class="d-block align-items-center text-center">
            <h1 class="p-1">
                <i class="fa-solid fa-person-breastfeeding"></i>
            </h1>
            <h5 class="fw-bold p-1">Initiated breast feeding after birth</h5>
            <input type="date" name="breastfeed_after_birth" class="form-control w-auto mx-auto" value="{{ old('breastfeed_after_birth', optional($infant->breastfeed_after_birth)->format('Y-m-d')) }}">
        </div>
    </div>

    <div class="m-3 bg-white border rounded-5">
        <div class="p-3 bg-info bg-opacity-10 border border-info border-3 border-bottom rounded-top-5">
            <h5 class="fw-bold mb-0">
                <i class="fa fa-syringe"></i>
                Immunization
            </h5>
        </div>

        <div class="p-3 border border-info border-3 border-top-0 rounded-bottom-5">
            <div class="table-responsive">
                <table class="table table-striped align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-start">Vaccine</th>
                            <th>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newborn1stDoseModal">
                                    1st Dose
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $medicineName)
                            @php
                                $medicineId = $medicineIdByName[$medicineName] ?? null;
                            @endphp
                            @if ($medicineId)
                                <tr>
                                    <td class="text-start">{{ $medicineName }}</td>
                                    <td>
                                        <input
                                            id="newborn-{{ $medicineId }}-{{ $doseNumber }}"
                                            type="date"
                                            class="form-control"
                                            name="immunizations[{{ $medicineId }}][{{ $doseNumber }}]"
                                            value="{{ old("immunizations.{$medicineId}.{$doseNumber}", data_get($immunizationDates, "{$tabKey}.{$medicineId}.{$doseNumber}")) }}"
                                        >
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-end mt-3">
        <button type="submit" class="btn btn-success">Save Newborn Tab</button>
    </div>
</form>

<div class="modal fade" id="newborn1stDoseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select 1st Dose Medicines</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Administration date</label>
                <input type="date" class="form-control" data-dose-date value="{{ now()->format('Y-m-d') }}">
                <hr>
                @foreach ($rows as $medicineName)
                    @php
                        $medicineId = $medicineIdByName[$medicineName] ?? null;
                    @endphp
                    @if ($medicineId)
                        <div class="form-check mb-2">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="newborn-modal-{{ $medicineId }}-{{ $doseNumber }}"
                                data-target-input="#newborn-{{ $medicineId }}-{{ $doseNumber }}"
                            >
                            <label class="form-check-label" for="newborn-modal-{{ $medicineId }}-{{ $doseNumber }}">
                                {{ $medicineName }}
                            </label>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="applyDoseSelection('newborn1stDoseModal')" data-bs-dismiss="modal">Apply selection</button>
            </div>
        </div>
    </div>
</div>
