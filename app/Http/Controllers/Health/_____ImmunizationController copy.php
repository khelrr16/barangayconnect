<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\Health\Immunization;
use App\Models\Health\Infant;
use App\Models\Health\Medicine;
use App\Models\Health\MedicineBatch;
use App\Models\Health\NutritionalAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ImmunizationController extends Controller
{
    public function index()
    {
        $infants = Infant::orderBy('created_at')->get();

        return view('committees.modules.health.immunizations.index', compact('infants'));
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Infant $infant)
    {
        if ($infant->household_id) {
            $infant->load('household');
        } elseif ($infant->foreign_household_id) {
            $infant->load('foreign_household');
        }

        return view('committees.modules.health.immunizations.profile', compact('infant'));
    }

    public function edit(Infant $infant)
    {
        if ($infant->household_id) {
            $infant->load('household');
        } elseif ($infant->foreign_household_id) {
            $infant->load('foreign_household');
        }

        $tabConfigs = $this->tabConfigs();

        $medicineNames = collect($tabConfigs)
            ->flatMap(fn ($config) => collect($config['doses'])->flatten()->values())
            ->unique()
            ->values();

        $medicines = Medicine::query()
            ->whereIn('name', $medicineNames)
            ->orderBy('name')
            ->get();

        $medicineIdsByName = $medicines->pluck('id', 'name');

        $immunizations = Immunization::query()
            ->where('infant_id', $infant->id)
            ->whereIn('medicine_id', $medicines->pluck('id'))
            ->get(['medicine_id', 'dose_number', 'administration_date'])
            ->keyBy(fn (Immunization $item) => $item->medicine_id . '|' . strtolower((string) $item->dose_number));

        $immunizationDates = [];
        foreach ($tabConfigs as $tabKey => $tabConfig) {
            $immunizationDates[$tabKey] = [];

            foreach ($tabConfig['doses'] as $doseNumber => $doseMedicines) {
                foreach ($doseMedicines as $medicineName) {
                    $medicineId = $medicineIdsByName->get($medicineName);
                    if (!$medicineId) {
                        continue;
                    }

                    $recordKey = $medicineId . '|' . strtolower($doseNumber);
                    $administrationDate = optional($immunizations->get($recordKey)?->administration_date)->format('Y-m-d');

                    $immunizationDates[$tabKey][$medicineId][$doseNumber] = $administrationDate;
                }
            }
        }

        dd($immunizationDates);

        $assessments = NutritionalAssessment::query()
            ->where('infant_id', $infant->id)
            ->whereIn('category', array_keys($tabConfigs))
            ->get()
            ->unique('category')
            ->keyBy('category');

        return view('committees.modules.health.immunizations.edit', compact(
            'infant',
            'medicines',
            'tabConfigs',
            'immunizationDates',
            'assessments'
        ));
    }

    public function update(Request $request, Infant $infant)
    {
        $tabConfigs = $this->tabConfigs();
        $tab = (string) $request->input('tab');

        if (!array_key_exists($tab, $tabConfigs)) {
            return back()->withErrors(['tab' => 'Invalid tab submitted.']);
        }

        $config = $tabConfigs[$tab];

        $validated = $request->validate([
            'tab' => ['required', 'string'],
            'assessment.age' => ['nullable', 'string', 'max:50'],
            'assessment.weight' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'assessment.length' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'assessment.status' => ['nullable', 'string', 'max:255'],
            'assessment.assessment_date' => ['nullable', 'date'],
            'immunizations' => ['nullable', 'array'],
            'immunizations.*' => ['nullable', 'array'],
            'immunizations.*.*' => ['nullable', 'date'],
        ]);

        $assessmentInput = Arr::get($validated, 'assessment', []); 
        $hasAssessmentValue = collect($assessmentInput)->filter(fn ($value) => $value !== null && $value !== '')->isNotEmpty();
        
        if ($hasAssessmentValue) {
            NutritionalAssessment::updateOrCreate(
                [
                    'infant_id' => $infant->id,
                    'category' => $tab,
                ],
                [
                    'age' => Arr::get($assessmentInput, 'age'),
                    'weight' => Arr::get($assessmentInput, 'weight'),
                    'length' => Arr::get($assessmentInput, 'length'),
                    'status' => Arr::get($assessmentInput, 'status'),
                    'assessment_date' => Arr::get($assessmentInput, 'assessment_date'),
                ]
            );
        }

        $medicines = Medicine::query()
            ->whereIn('name', collect($config['doses'])->flatten()->unique()->values())
            ->get(['id', 'name']);
        $medicineIdsByName = $medicines->pluck('id', 'name');

        $result = DB::transaction(function () use ($request, $infant, $config, $medicineIdsByName) {
            $errors = [];

            foreach ($config['doses'] as $doseNumber => $doseMedicines) {
                foreach ($doseMedicines as $medicineName) {
                    $medicineId = $medicineIdsByName->get($medicineName);
                    if (!$medicineId) {
                        continue;
                    }

                    $date = $request->input("immunizations.{$medicineId}.{$doseNumber}");

                    if ($date) {
                        
                        $medicineBatch = MedicineBatch::where('medicine_id', $medicineId)->where('quantity_remaining', '>', 0)->oldest()->first();
                        if (!$medicineBatch) {
                            $errors[$medicineName] = 'No available stock for ' . $medicineName;
                            continue;
                        }

                        $newImmunization = Immunization::updateOrCreate(
                            [
                                'infant_id' => $infant->id,
                                'medicine_id' => $medicineId,
                                'dose_number' => $doseNumber,
                            ],
                            [
                                'administration_date' => $date,
                            ]
                        );

                        if ($newImmunization->wasRecentlyCreated) {
                            $medicineBatch->decrement('quantity_remaining');
                            $medicineBatch->increment('quantity_used');
                        }

                    } else {
                        if(Immunization::query()
                            ->where('infant_id', $infant->id)
                            ->where('medicine_id', $medicineId)
                            ->where('dose_number', $doseNumber)
                            ->delete());
                    }
                }
            }

            return $errors;
        });

        if($tab == 'newborn'){
            $infant->update([
                'breastfeed_after_birth' => $request->input('breastfeed_after_birth') ?? $infant->breastfeed_after_birth,
            ]);
        } 
        else if($tab == 'months_1_3') {
            // Get iron medicine once
            $ironMedicine = Medicine::where('name', 'Iron')->first();

            if (!$ironMedicine) {
                return back()->with('error', 'Iron medicine not found in inventory');
            }

            $updates = [];
            $stockErrors = [];
            $usedBatches = [];

            // Define the iron fields to process
            $ironFields = ['iron_1', 'iron_2', 'iron_3'];

            foreach ($ironFields as $field) {
                $newValue = $request->input($field);
                $oldValue = $infant->$field;
                
                // Skip if no change
                if ($newValue == $oldValue) {
                    continue;
                }
                
                // If removing a dose (new value is empty)
                if (empty($newValue) && !empty($oldValue)) {
                    $updates[$field] = null;
                    // Optionally return to stock logic here
                    continue;
                }
                
                // If adding a new dose
                if (!empty($newValue)) {
                    // Find available batch for this dose
                    $batch = MedicineBatch::where('medicine_id', $ironMedicine->id)
                        ->where('quantity_remaining', '>', 0)
                        ->where('expiration_date', '>', now())
                        ->orderBy('expiration_date', 'asc')
                        ->orderBy('created_at', 'asc')
                        ->first();
                    
                    if (!$batch) {
                        $stockErrors[$field] = "No available iron stock for {$field}";
                        continue;
                    }
                    
                    // Use this batch
                    $batch->decrement('quantity_remaining');
                    $batch->increment('quantity_used');
                    
                    $updates[$field] = $newValue;
                    $usedBatches[$field] = [
                        'batch_id' => $batch->id,
                        'batch_number' => $batch->batch_number,
                        'remaining' => $batch->fresh()->quantity_remaining
                    ];
                }
            }

            // Update infant if there are changes
            if (!empty($updates)) {
                $infant->update($updates);
            }

            // Prepare response
            $response = [];
            if (!empty($stockErrors)) {
                $response['errors'] = $stockErrors;
            }
            if (!empty($usedBatches)) {
                $response['used_batches'] = $usedBatches;
            }
            if (!empty($updates)) {
                $response['updated_fields'] = array_keys($updates);
            }
        }


        if (!empty($result)) {
            return redirect()
                ->route('committee.immunization.edit', $infant)
                ->with('success', 'Immunization and nutritional assessment were updated.')
                ->withErrors($result);
        }

        return redirect()
            ->route('committee.immunization.edit', $infant)
            ->with('success', 'Immunization and nutritional assessment were updated.');
    }

    public function destroy(string $id)
    {
        //
    }

    private function tabConfigs(): array
    {
        return [
            'newborn' => [
                'doses' => [
                    '1st' => ['BCG', 'Hepa B-BD'],
                ],
            ],
            'months_1_3' => [
                'doses' => [
                    '1st' => ['DPT-HiB-HepB', 'OPV', 'PCV', 'IPV'],
                    '2nd' => ['DPT-HiB-HepB', 'OPV', 'PCV'],
                    '3rd' => ['DPT-HiB-HepB', 'OPV', 'PCV'],
                ],
            ],
            'months_6_11' => [
                'doses' => [
                    '1st' => ['MMR'],
                    '2nd' => ['IPV'],
                ],
            ],
            'months_12' => [
                'doses' => [
                    '2nd' => ['MMR'],
                ],
            ],
        ];
    }
}
