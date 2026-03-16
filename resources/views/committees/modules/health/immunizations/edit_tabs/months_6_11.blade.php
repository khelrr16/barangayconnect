@php
    $tabKey = 'months_6_11';
    $assessment = $assessments->get($tabKey);
    $rows = ['MMR', 'IPV'];
    $doses = ['1st', '2nd'];
    $doseMedicines = $tabConfigs[$tabKey]['doses'] ?? [];
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
                    <label class="form-label text-muted">Age in months</label>
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
                    <input type="text" name="assessment[status]" class="form-control" value="{{ old('assessment.status', $assessment?->status) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted">Assessment date</label>
                    <input type="date" name="assessment[assessment_date]" class="form-control" value="{{ old('assessment.assessment_date', optional($assessment?->assessment_date)->format('Y-m-d')) }}">
                </div>
            </div>
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
                            <th>Vaccine</th>
                            @foreach ($doses as $dose)
                                <th>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#months611-{{ $dose }}-modal">
                                        {{ $dose }} Dose
                                    </button>
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($rows as $medicineName)
                            @php
                                $medicineId = $medicineIdByName[$medicineName] ?? null;
                            @endphp
                            @if ($medicineId)
                                <tr>
                                    <td>{{ $medicineName }}</td>
                                    @foreach ($doses as $dose)
                                        @if (in_array($medicineName, $doseMedicines[$dose] ?? [], true))
                                            <td>
                                                <input
                                                    id="months611-{{ $medicineId }}-{{ $dose }}"
                                                    type="date"
                                                    class="form-control"
                                                    name="immunizations[{{ $medicineId }}][{{ $dose }}]"
                                                    value="{{ old("immunizations.{$medicineId}.{$dose}", data_get($immunizationDates, "{$tabKey}.{$medicineId}.{$dose}")) }}"
                                                >
                                            </td>
                                        @else
                                            <td><span class="badge bg-secondary">N/A</span></td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-end mt-3">
        <button type="submit" class="btn btn-success">Save 6-11 Months Tab</button>
    </div>
</form>

@foreach ($doses as $dose)
    <div class="modal fade" id="months611-{{ $dose }}-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select {{ $dose }} Dose Medicines</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Administration date</label>
                    <input type="date" class="form-control" data-dose-date value="{{ now()->format('Y-m-d') }}">
                    <hr>
                    @foreach ($doseMedicines[$dose] ?? [] as $medicineName)
                        @php
                            $medicineId = $medicineIdByName[$medicineName] ?? null;
                        @endphp
                        @if ($medicineId)
                            <div class="form-check mb-2">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="months611-modal-{{ $medicineId }}-{{ $dose }}"
                                    data-target-input="#months611-{{ $medicineId }}-{{ $dose }}"
                                >
                                <label class="form-check-label" for="months611-modal-{{ $medicineId }}-{{ $dose }}">
                                    {{ $medicineName }}
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="applyDoseSelection('months611-{{ $dose }}-modal')" data-bs-dismiss="modal">Apply selection</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
