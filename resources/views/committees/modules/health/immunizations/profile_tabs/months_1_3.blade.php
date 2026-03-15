@php
    $tab = 'months_1_3';
    $assessment = $assessments->get($tab);
    $rows = ['DPT-HiB-HepB', 'OPV', 'PCV', 'IPV'];
    $doses = ['1st', '2nd', '3rd'];
    $doseMedicines = $tabConfigs[$tab] ?? [];
@endphp

<!-- Nutritional Status Assessment -->
<div class="m-3 bg-white border rounded-5">
    <div class="p-3 bg-warning bg-opacity-10 border border-warning border-3 border-bottom rounded-top-5">
        <h5 class="fw-bold d-flex justify-content-between align-items-center mb-0">
            <div>
                <i class="fa-solid fa-utensils"></i>
                Nutritional Status Assessment
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#{{ $tab }}-assessment-modal">
                <i class="fa-solid fa-pen-to-square"></i>
            </button>
        </h5>
    </div>

    <div class="p-3 border border-warning border-3 border-top-0 rounded-bottom-5 ">
        @if($assessment == null)
            <div class="text-center text-muted">
                No assessment data available.
            </div>
        @else
            <div class="d-flex justify-content-between">
                <div class="text-center">
                    <div class="text-muted">Age in months</div>
                    <h1 class="text-center fw-semibold">{{ $assessment?->age ?? '--' }}</h1>
                </div>

                <div class="">
                    <div class="mb-4">
                        <div class="text-muted">Length (cm)</div>
                        <div class="fw-semibold">{{ optional($assessment)->length ? round(optional($assessment)->length, 2).' cm' : '--' }}</div>
                    </div>

                    <small class="text-muted">{{ optional($assessment)->assessment_date ? optional($assessment)->assessment_date->format('m/d/Y') : '--' }}</small>
                </div>

                <div class="">
                    <div class="mb-4">
                        <div class="text-muted">Weight (kg)</div>
                        <div class="fw-semibold">{{ optional($assessment)->weight ? round(optional($assessment)->weight, 2).' kg' : '--' }}</div>
                    </div>

                    <small class="text-muted">{{ optional($assessment)->assessment_date ? optional($assessment)->assessment_date->format('m/d/Y') : '--' }}</small>
                </div>

                <div class="text-center">
                    <div class="text-muted">Status (Birth Weight)</div>
                    <h4 class="fw-semibold">{{ $assessment?->status ?? '--' }}</h4>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Additional Info -->
<div class="m-3 bg-white border rounded-5">
    <div class="p-3 bg-success bg-opacity-10 border border-success border-3 border-bottom rounded-top-5">
        <h5 class="fw-bold d-flex justify-content-between align-items-center mb-0">
            <div>
                <i class="fa-solid fa-weight-hanging"></i>
                Low birth weight
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#{{ $tab }}-addInfo-modal">
                <i class="fa-solid fa-pen-to-square"></i>
            </button>
        </h5>
    </div>

    <div class="p-3 border border-success border-3 border-top-0 rounded-bottom-5">
        <div class="table-responsive">
            <table class="table table-striped align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>Iron</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $ironData = [
                            ['month' => '1 month', 'date' => $infant->iron_1],
                            ['month' => '2 months', 'date' => $infant->iron_2],
                            ['month' => '3 months', 'date' => $infant->iron_3],
                        ];
                    @endphp
                    @foreach($ironData as $data)
                        <tr>
                            <td>{{ $data['month'] }}</td>
                            @if($data['date'])
                                <td><span class="badge bg-success">{{ $data['date']->format('m-d-Y') }}</span></td>
                            @else
                                <td><span class="badge bg-secondary">UNSET</span></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Immunization -->
<div class="m-3 bg-white border rounded-5">
    <div class="p-3 bg-info bg-opacity-10 border border-info border-3 border-bottom rounded-top-5">
        <h5 class="fw-bold d-flex justify-content-between align-items-center mb-0">
            <div>
                <i class="fa fa-syringe"></i>
                Immunization
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#{{ $tab }}-immunization-modal">
                <i class="fa-solid fa-pen-to-square"></i>
            </button>
        </h5>
    </div>

    <div class="p-3 border border-info border-3 border-top-0 rounded-bottom-5">
        <div class="table-responsive">
            <table class="table table-striped align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>Vaccine</th>
                        <th>1st Dose</th>
                        <th>2nd Dose</th>
                        <th>3rd Dose</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($rows as $medicineName)
                        @php
                            $medicineId = $medicineIdsByName[$medicineName] ?? null;
                        @endphp

                        @if ($medicineId)
                            <tr>
                                <td>{{ $medicineName }}</td>
                                @foreach($doses as $dose)
                                    @if (in_array($medicineName, $doseMedicines[$dose] ?? [], true))
                                        @php
                                            $immunizationData = data_get($immunizationDates, "{$tab}.{$medicineId}.{$dose}") ?? null;
                                        @endphp

                                        @if($immunizationData)
                                            <td><span class="badge bg-success">{{ $immunizationData }}</span></td>
                                        @else
                                            <td><span class="badge bg-danger">MISSING</span></td>
                                        @endif
                                    @else
                                        <td><span class="badge bg-secondary">NOT REQUIRED</span></td>
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

<!-- Assessment Model -->
<div class="modal fade" id="{{ $tab }}-assessment-modal" tabindex="-1" aria-hidden="true">
    <form method="POST" action="{{ route('committee.health.immunization.assessment.update', $infant) }}">
        @csrf
        @method('PATCH')
        <input type="hidden" name="tab" value="{{ $tab }}">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nutritional Status Assessment (1-3 months old)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Age in months</label>
                        <input type="text" name="age" class="form-control" value="{{ old('age', $assessment?->age ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Length (cm)</label>
                        <input type="number" step="0.01" min="0" name="length" class="form-control" value="{{ old('length', $assessment?->length ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Weight (kg)</label>
                        <input type="number" step="0.01" min="0" name="weight" class="form-control" value="{{ old('weight', $assessment?->weight ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Status</label>
                        <select class="form-select mb-2" name="status">
                            <option value="" disabled selected>--</option>
                            <option value="Stunted" {{ old('status', $assessment?->status ?? '') == 'Stunted' ? 'selected' : '' }}>Stunted</option>
                            <option value="Wasted-MAM" {{ old('status', $assessment?->status ?? '') == 'Wasted-MAM' ? 'selected' : '' }}>Wasted-MAM</option>
                            <option value="Wasted-SAM" {{ old('status', $assessment?->status ?? '') == 'Wasted-SAM' ? 'selected' : '' }}>Wasted-SAM</option>
                            <option value="Obese" {{ old('status', $assessment?->status ?? '') == 'Obese' ? 'selected' : '' }}>Obese</option>
                            <option value="Normal" {{ old('status', $assessment?->status ?? '') == 'Normal' ? 'selected' : '' }}>Normal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Assessment date</label>
                        <input type="date" name="assessment_date" class="form-control" value="{{ old('assessment_date', optional($assessment)->assessment_date?->format('Y-m-d') ?? '') }}">
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

<!-- Additional Info Model -->
<div class="modal fade" id="{{ $tab }}-addInfo-modal" tabindex="-1" aria-hidden="true">
    <form method="POST" action="{{ route('committee.health.immunization.addInfo.update', $infant) }}">
        @csrf
        @method('PATCH')
        <input type="hidden" name="tab" value="{{ $tab }}">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Additional Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-striped align-middle text-center">
                            <thead class="table-primary">
                                <tr>
                                    <th>Iron</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $ironData = [
                                        ['month' => '1 month', 'date' => $infant->iron_1, 'name' => 'iron_1'],
                                        ['month' => '2 months', 'date' => $infant->iron_2, 'name' => 'iron_2'],
                                        ['month' => '3 months', 'date' => $infant->iron_3, 'name' => 'iron_3'],
                                    ];
                                @endphp

                                @foreach($ironData as $data)
                                    <tr>
                                        <td>{{ $data['month'] }}</td>
                                        @if($data['date'])
                                            <td>
                                                <input
                                                    type="date"
                                                    class="form-control bg-success bg-opacity-25"
                                                    name="{{ $data['name'] }}"
                                                    value="{{ old("{$data['name']}", $data['date']->format('Y-m-d')) }}"
                                                >
                                            </td>
                                        @else
                                            <td>
                                                <input
                                                    type="date"
                                                    class="form-control"
                                                    name="{{ $data['name'] }}"
                                                    value="{{ old("{$data['name']}", $data['date']) }}"
                                                >
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

<!-- Immunization Model -->
<div class="modal fade" id="{{ $tab }}-immunization-modal" tabindex="-1" aria-hidden="true">
    <form method="POST" action="{{ route('committee.health.immunization.update', $infant) }}">
        @csrf
        @method('PATCH')
        <input type="hidden" name="tab" value="{{ $tab }}">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Immunization Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-striped align-middle text-center">
                            <thead class="table-primary">
                                <tr>
                                    <th>Vaccine</th>
                                    <th>1st Dose</th>
                                    <th>2nd Dose</th>
                                    <th>3rd Dose</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($rows as $medicineName)
                                    @php
                                        $medicineId = $medicineIdsByName[$medicineName] ?? null;
                                    @endphp

                                    @if ($medicineId)
                                        <tr>
                                            <td>{{ $medicineName }}</td>
                                            @foreach($doses as $dose)
                                                @if (in_array($medicineName, $doseMedicines[$dose] ?? [], true))
                                                    @php
                                                        $immunizationData = data_get($immunizationDates, "{$tab}.{$medicineId}.{$dose}") ?? null;
                                                    @endphp

                                                    @if($immunizationData)
                                                        <td>
                                                            <input
                                                                id="months13-{{ $medicineId }}-{{ $dose }}"
                                                                type="date"
                                                                class="form-control bg-success bg-opacity-25"
                                                                name="immunizations[{{ $medicineId }}][{{ $dose }}]"
                                                                value="{{ old("immunizations.{$medicineId}.{$dose}", $immunizationData) }}"
                                                            >
                                                        </td>
                                                    @else
                                                        <td>
                                                            <input
                                                                id="months13-{{ $medicineId }}-{{ $dose }}"
                                                                type="date"
                                                                class="form-control"
                                                                name="immunizations[{{ $medicineId }}][{{ $dose }}]"
                                                                value="{{ old("immunizations.{$medicineId}.{$dose}") }}"
                                                            >
                                                        </td>
                                                    @endif
                                                @else
                                                    <td><span class="badge bg-secondary">-NOT REQUIRED-</span></td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endif

                                @endforeach
                            </tbody>
                        </table>
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
