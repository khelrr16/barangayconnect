@php
    $tab = 'months_6_11';
    $assessment = $assessments->get($tab);
    $rows = ['MMR', 'IPV'];
    $doses = ['1st', '2nd'];
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
<div class="m-3 p-3 bg-white border rounded-5">
    <div class="d-flex justify-content-end align-items-center text-center">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#{{ $tab }}-addInfo-modal">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>
    </div> 
    <div class="d-flex align-items-top justify-content-around gap-3 text-center">

        <!-- Exclusively breast fed -->
        <div>
            <h1 class="p-1">
                <i class="fa-solid fa-person-breastfeeding"></i>
            </h1>
            <div class="fw-bold p-1">
                Exclusively breast fed up to 5 months and 29 days
            </div>
            @if($infant->breastfeed_exclusively)
                <h4 class="text-success fw-bold p-1">{{ $infant->breastfeed_exclusively ?? '--' }}</h4>
                <div class="text-muted p-1">{{ optional($infant->breastfeed_exclusively_date)->format('M d, Y') ?? '--' }}</div>
            @else
                <h4 class="fw-bold text-secondary p-1">UNSET</h4>
            @endif
            
        </div>

        <!-- Vitamin A -->
        <div class="w-50 border-start border-end">
            <h1 class="p-1">
                <i class="fa-solid fa-prescription-bottle-medical"></i>
            </h1>
            <div class="fw-bold p-1 mb-4">
                Vitamin A
            </div>
            @if($infant->vitamin_a)
                <h4 class="text-success fw-bold p-1">{{ optional($infant->vitamin_a)->format('M d, Y') ?? '--' }}</h4>
            @else
                <h4 class="text-secondary fw-bold p-1">UNSET</h4>
            @endif
            
        </div>

        <!-- Complementary Feeding -->
        <div>
            <h1 class="p-1">
                <i class="fa-solid fa-bowl-rice"></i>
            </h1>
            <div class="fw-bold p-1">
                Introduction of Complementary Feeding at 6 months old
            </div>
            @if($infant->complementary_feeding_2)
                <h4 class="text-success fw-bold p-1">{{ $infant->complementary_feeding ?? '--' }}</h4>
                <div class="text-muted p-1">
                    @if($infant->complementary_feeding_2 == 1)
                        (With continuous breastfeeding)
                    @elseif($infant->complementary_feeding_2 == 2)
                        (No longer breastfeeding or never breastfed)
                    @else
                        --
                    @endif
                </div>
            @else
                <h4 class="text-secondary fw-bold p-1">UNSET</h4>
            @endif
        </div>
    </div>

    <hr>

    <h4 class="text-center">
        <i class="fa-solid fa-bandage"></i> 
        Microneedle Patches (MNP)
    </h4>
    
    <div class="d-flex p-3 align-items-center justify-content-around text-center">
        <div>
            <div class="fw-bold p-1">Date when 90 sachets given</div>
            <h4 class="text-success fw-bold p-1">{{ optional($infant->mnp_start)->format('M d, Y') ?? '--' }}</h4>
        </div>

        <div>
            <div class="fw-bold p-1">Date of Completion</div>
            <h4 class="text-success fw-bold p-1">{{ optional($infant->mnp_end)->format('M d, Y') ?? '--' }}</h4>
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
    <form method="POST" action="{{ route('committee.assessment.update', $infant) }}">
        @csrf
        @method('PATCH')
        <input type="hidden" name="tab" value="{{ $tab }}">
        
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nutritional Status Assessment (6-11 months old)</h5>
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
    <form method="POST" action="{{ route('committee.addInfo.update', $infant) }}">
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
                        <h3 class="mb-3">
                            <i class="fa-solid fa-person-breastfeeding"></i>
                            Exclusively breast fed up to 5 months and 29 days
                        </h3>

                        <div class="mb-3">
                            <label class="form-label text-muted">Exclusively breast fed up to 5 months and 29 days</label>
                            <select name="breastfeed_exclusively" class="form-control">
                                <option value="" disabled selected>--</option>
                                <option value="Yes" {{ old('breastfeed_exclusively', $infant->breastfeed_exclusively) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('breastfeed_exclusively', $infant->breastfeed_exclusively) == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">Date</label>
                            <input type="date" name="breastfeed_exclusively_date" class="form-control" value="{{ old('breastfeed_exclusively_date', optional($infant->breastfeed_exclusively_date)->format('Y-m-d') ?? '') }}">
                        </div>
                    </div>

                    <div class="mb-4 p-3 border rounded-3 bg-secondary bg-opacity-10">
                        <h4 class="mb-3">
                            <i class="fa-solid fa-bowl-rice"></i>
                            Introduction of Complementary Feeding at 6 months old
                        </h4>       

                        <div class="mb-3">
                            <label class="form-label text-muted">Yes or No</label>
                            <select name="complementary_feeding" class="form-control">
                                <option value="" disabled selected>--</option>
                                <option value="Yes" {{ old('complementary_feeding', $infant->complementary_feeding) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('complementary_feeding', $infant->complementary_feeding) == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">Situation</label>
                            <select name="complementary_feeding_2" class="form-control">
                                <option value="" disabled selected>--</option>
                                <option value="1" {{ old('complementary_feeding_2', $infant->complementary_feeding_2) == '1' ? 'selected' : '' }}>With continuous breastfeeding</option>
                                <option value="2" {{ old('complementary_feeding_2', $infant->complementary_feeding_2) == '2' ? 'selected' : '' }}>No longer breastfeeding or never breastfed</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4 p-3 border rounded-3 bg-secondary bg-opacity-10">

                        <h4 class="mb-3">
                            <i class="fa-solid fa-prescription-bottle-medical"></i>
                            Vitamin A
                        </h4>

                        <div class="mb-3">
                            <label class="form-label text-muted">Date given</label>
                            <input type="date" name="vitamin_a" class="form-control" value="{{ old('vitamin_a', optional($infant->vitamin_a)->format('Y-m-d') ?? '') }}">
                        </div>
                    </div>

                    <div class="mb-4 p-3 border rounded-3 bg-secondary bg-opacity-10">

                        <h4 class="mb-3">
                            <i class="fa-solid fa-bandage"></i> 
                            Microneedle Patches (MNP)
                        </h4>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Date when 90 sachets given</label>
                            <input type="date" name="mnp_start" class="form-control" value="{{ old('mnp_start', optional($infant->mnp_start)->format('Y-m-d') ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">Date completed</label>
                            <input type="date" name="mnp_end" class="form-control" value="{{ old('mnp_end', optional($infant->mnp_end)->format('Y-m-d') ?? '') }}">
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

<!-- Immunization Model -->
<div class="modal fade" id="{{ $tab }}-immunization-modal" tabindex="-1" aria-hidden="true">
    <form method="POST" action="{{ route('committee.immunization.update', $infant) }}">
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