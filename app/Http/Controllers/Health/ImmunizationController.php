<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\Health\Immunization;
use App\Models\Health\Infant;
use App\Models\Health\Medicine;
use App\Models\Health\MedicineBatch;
use App\Models\Health\NutritionalAssessment;
use Carbon\Carbon;
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
        $tabs = ['newborn', 'months_1_3', 'months_6_11', 'months_12'];
        $tabConfigs = $this->tabConfigs();
        $birthday = Carbon::parse($infant->birthday)->startOfDay();
        $today = now()->startOfDay();
        $scheduleDefinitions = collect($this->scheduleDefinitions($birthday));
        $currentAgeLabel = $this->formatInfantAge($birthday, $today);

        if ($infant->household_id) {
            $infant->load('household');
        } elseif ($infant->foreign_household_id) {
            $infant->load('foreign_household');
        }

        $assessments = NutritionalAssessment::query()
            ->where('infant_id', $infant->id)
            ->whereIn('category', $tabs)
            ->get()
            ->unique('category')
            ->keyBy('category');

        $medicineNames = collect($tabConfigs)
            ->flatMap(fn ($config) => collect($config)->flatten()->values())
            ->unique()
            ->values();

        $medicines = Medicine::query()
            ->whereIn('name', $medicineNames)
            ->orderBy('name')
            ->get();

        $medicineIdsByName = $medicines->pluck('id', 'name');

        $immunizationRecords = Immunization::query()
            ->where('infant_id', $infant->id)
            ->whereIn('medicine_id', $medicines->pluck('id'))
            ->orderBy('administration_date')
            ->get(['medicine_id', 'dose_number', 'administration_date'])
            ->keyBy(fn (Immunization $item) => $item->medicine_id . '|' . strtolower((string) $item->dose_number));

        $overviewRows = $medicines->map(function (Medicine $medicine) use ($immunizationRecords, $scheduleDefinitions) {
            $records = $immunizationRecords
                ->filter(fn (Immunization $record) => $record->medicine_id === $medicine->id)
                ->sortBy('administration_date')
                ->values();

            $nextSchedule = $scheduleDefinitions->first(function (array $schedule) use ($medicine, $records) {
                if (!in_array($medicine->name, $schedule['medicines'], true)) {
                    return false;
                }

                return !$records->contains(fn (Immunization $record) => strtolower((string) $record->dose_number) === $schedule['dose_key']);
            });

            return [
                'name' => $medicine->name,
                'total_used' => $records->count(),
                'dates_administered' => $records
                    ->pluck('administration_date')
                    ->filter()
                    ->map(fn (Carbon $date) => $date->format('M j, Y'))
                    ->values()
                    ->all(),
                'next_due' => $nextSchedule ? $nextSchedule['window'] : 'Complete',
                'next_due_label' => $nextSchedule ? $nextSchedule['title'] : 'Complete',
            ];
        })->values();

        $scheduledImmunizations = $scheduleDefinitions->map(function (array $schedule) use ($medicineIdsByName, $immunizationRecords, $today) {
            $completedMedicines = [];
            $pendingMedicines = [];

            foreach ($schedule['medicines'] as $medicineName) {
                $medicineId = $medicineIdsByName->get($medicineName);
                $record = $medicineId
                    ? $immunizationRecords->get($medicineId . '|' . $schedule['dose_key'])
                    : null;

                if ($record) {
                    $completedMedicines[] = $medicineName . ' (' . $record->administration_date->format('M j, Y') . ')';
                    continue;
                }

                $pendingMedicines[] = $medicineName;
            }

            if (empty($pendingMedicines)) {
                $status = ['label' => 'Completed', 'class' => 'text-bg-success'];
            } elseif ($today->lt($schedule['due_date'])) {
                $status = ['label' => 'Upcoming', 'class' => 'text-bg-secondary'];
            } elseif ($schedule['deadline_date'] && $today->gt($schedule['deadline_date'])) {
                $status = ['label' => 'Overdue', 'class' => 'text-bg-danger'];
            } else {
                $status = ['label' => 'Due now', 'class' => 'text-bg-warning'];
            }

            return [
                'title' => $schedule['title'],
                'window' => $schedule['window'],
                'medicines' => $schedule['medicines'],
                'completed_medicines' => $completedMedicines,
                'pending_medicines' => $pendingMedicines,
                'status' => $status['label'],
                'status_class' => $status['class'],
                'due_date' => $schedule['due_date']->format('M j, Y'),
                'deadline_date' => $schedule['deadline_date']?->format('M j, Y'),
            ];
        })->values();

        $immunizationDates = [];
        
        foreach ($tabConfigs as $tabKey => $tabConfig) {
            $immunizationDates[$tabKey] = [];
            
            foreach ($tabConfig as $doseNumber => $doseMedicines) {
                foreach ($doseMedicines as $medicineName) {
                    $medicineId = $medicineIdsByName->get($medicineName);
                    if (!$medicineId) {
                        continue;
                    }

                    $recordKey = $medicineId . '|' . strtolower($doseNumber);
                    $administrationDate = optional($immunizationRecords->get($recordKey)?->administration_date)->format('Y-m-d');
                    $immunizationDates[$tabKey][$medicineId][$doseNumber] = $administrationDate;
                }
            }
        }

        return view('committees.modules.health.immunizations.profile', compact(
            'infant',
            'medicines',
            'tabConfigs',
            'medicineIdsByName',
            'immunizationDates',
            'assessments',
            'overviewRows',
            'scheduledImmunizations',
            'currentAgeLabel'
        ));
    }

    public function edit(Infant $infant){

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
            'immunizations' => ['nullable', 'array'],
            'immunizations.*' => ['nullable', 'array'],
            'immunizations.*.*' => ['nullable', 'date'],
        ]);

        $medicines = Medicine::query()
            ->whereIn('name', collect($config)->flatten()->unique()->values())
            ->get(['id', 'name']);

        $medicineIdsByName = $medicines->pluck('id', 'name');

        $result = DB::transaction(function () use ($validated, $infant, $config, $medicineIdsByName) {
            $errors = [];

            foreach ($config as $doseNumber => $doseMedicines) {
                foreach ($doseMedicines as $medicineName) {
                    $medicineId = $medicineIdsByName->get($medicineName);
                    if (!$medicineId) {
                        continue;
                    }

                    $date = Arr::get($validated, "immunizations.{$medicineId}.{$doseNumber}");

                    $existingImmunization = Immunization::query()
                        ->where('infant_id', $infant->id)
                        ->where('medicine_id', $medicineId)
                        ->where('dose_number', $doseNumber)
                        ->first();

                    if ($date) {
                        if (!$existingImmunization && !$this->consumeMedicineStock($medicineId)) {
                            $errors[$medicineName] = 'No available stock for ' . $medicineName;
                            continue;
                        }

                        Immunization::updateOrCreate(
                            [
                                'infant_id' => $infant->id,
                                'medicine_id' => $medicineId,
                                'dose_number' => $doseNumber,
                            ],
                            [
                                'administration_date' => $date,
                            ]
                        );
                    } else {
                        if ($existingImmunization) {
                            $existingImmunization->delete();
                            $this->restoreMedicineStock($medicineId);
                        }
                    }
                }
            }

            return $errors;
        });

        if (!empty($result)) {
            return back()->with([
                'success' => 'Immunization updated successfully.',
                'activeTab' => $tab,
            ])->withErrors($result);
        }

        return back()->with([
            'success' => 'Immunization updated successfully.',
            'activeTab' => $tab
        ]);
    }

    public function addInfo(Request $request, Infant $infant)
    {
        $validated = $request->validate([
            'tab' => ['required', 'string', 'in:newborn,months_1_3,months_6_11,months_12,monitoring'],
            'breastfeed_after_birth' => ['nullable', 'string', 'max:255'],
            'iron_1' => ['nullable', 'date'],
            'iron_2' => ['nullable', 'date'],
            'iron_3' => ['nullable', 'date'],
            'breastfeed_exclusively' => ['nullable', 'string', 'max:255'],
            'breastfeed_exclusively_date' => ['nullable', 'date'],
            'complementary_feeding' => ['nullable', 'string', 'max:255'],
            'complementary_feeding_2' => ['nullable', 'string', 'max:255'],
            'vitamin_a' => ['nullable', 'date'],
            'mnp_start' => ['nullable', 'date'],
            'mnp_end' => ['nullable', 'date'],
            'fic' => ['nullable', 'date'],
            'cic' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        $tab = $validated['tab'];
        $errors = [];

        DB::transaction(function () use ($infant, $validated, $tab, &$errors) {
            switch ($tab) {
                case 'newborn':
                    $infant->update([
                        'breastfeed_after_birth' => Arr::get($validated, 'breastfeed_after_birth'),
                    ]);
                    break;

                case 'months_1_3':
                    $ironMedicine = Medicine::query()->where('name', 'Iron')->first();

                    if (!$ironMedicine) {
                        $errors['iron'] = 'Iron medicine not found in inventory';
                        return;
                    }

                    $updates = [];
                    foreach (['iron_1', 'iron_2', 'iron_3'] as $field) {
                        $newValue = Arr::get($validated, $field);
                        $oldValue = $infant->{$field};

                        if ($newValue == $oldValue) {
                            continue;
                        }

                        if ($newValue !== null && $oldValue === null) {
                            if (!$this->consumeMedicineStock($ironMedicine->id)) {
                                $errors[$field] = 'No available stock for Iron';
                                continue;
                            }
                        }

                        if ($newValue === null && $oldValue !== null) {
                            $this->restoreMedicineStock($ironMedicine->id);
                        }

                        $updates[$field] = $newValue;
                    }

                    if (!empty($updates)) {
                        $infant->update($updates);
                    }
                    break;

                case 'months_6_11':
                    $updates = [];
                    $medicineMap = Medicine::query()
                        ->whereIn('name', ['Vitamin A', 'MNP'])
                        ->get(['id', 'name'])
                        ->keyBy('name');

                    $medicineFields = [
                        ['name' => 'Vitamin A', 'field' => 'vitamin_a'],
                        ['name' => 'MNP', 'field' => 'mnp_start'],
                    ];

                    foreach ($medicineFields as $medicineField) {
                        $medicine = $medicineMap->get($medicineField['name']);
                        $field = $medicineField['field'];

                        if (!$medicine) {
                            $errors[$field] = $medicineField['name'] . ' medicine not found in inventory';
                            continue;
                        }

                        $newValue = Arr::get($validated, $field);
                        $oldValue = $infant->{$field};

                        if ($newValue == $oldValue) {
                            continue;
                        }

                        if ($newValue !== null && $oldValue === null) {
                            if (!$this->consumeMedicineStock($medicine->id)) {
                                $errors[$field] = 'No available stock for ' . $medicineField['name'];
                                continue;
                            }
                        }

                        if ($newValue === null && $oldValue !== null) {
                            $this->restoreMedicineStock($medicine->id);
                        }

                        $updates[$field] = $newValue;
                    }

                    $updates = array_merge($updates, [
                        'breastfeed_exclusively' => Arr::get($validated, 'breastfeed_exclusively'),
                        'breastfeed_exclusively_date' => Arr::get($validated, 'breastfeed_exclusively_date'),
                        'complementary_feeding' => Arr::get($validated, 'complementary_feeding'),
                        'complementary_feeding_2' => Arr::get($validated, 'complementary_feeding_2'),
                        'mnp_end' => Arr::get($validated, 'mnp_end'),
                    ]);

                    $infant->update($updates);
                    break;

                case 'months_12':
                    $infant->update([
                        'fic' => Arr::get($validated, 'fic'),
                        'cic' => Arr::get($validated, 'cic'),
                    ]);
                    break;

                case 'monitoring':
                    $infant->update([
                        'status' => Arr::get($validated, 'status'),
                        'remarks' => Arr::get($validated, 'remarks'),
                    ]);
                    break;
            }
        });

        if (!empty($errors)) {
            return back()->with([
                'success' => 'Immunization updated successfully.',
                'activeTab' => $tab,
            ])->withErrors($errors);
        }

        return back()->with([
            'success' => 'Immunization updated successfully.',
            'activeTab' => $tab,
        ]);
    }

    private function consumeMedicineStock(int $medicineId): bool
    {
        $medicineBatch = MedicineBatch::query()
            ->where('medicine_id', $medicineId)
            ->where('quantity_remaining', '>', 0)
            ->oldest()
            ->first();

        if (!$medicineBatch) {
            return false;
        }

        $medicineBatch->decrement('quantity_remaining');
        $medicineBatch->increment('quantity_used');

        return true;
    }

    private function restoreMedicineStock(int $medicineId): void
    {
        $medicineBatch = MedicineBatch::query()
            ->where('medicine_id', $medicineId)
            ->where('quantity_used', '>', 0)
            ->latest('updated_at')
            ->first();

        if (!$medicineBatch) {
            return;
        }

        $medicineBatch->increment('quantity_remaining');
        $medicineBatch->decrement('quantity_used');
    }

    public function destroy(string $id)
    {
        //
    }

    private function tabConfigs(): array
    {
        return [
            'newborn' => [
                '1st' => ['BCG', 'Hepa B-BD'],
            ],
            'months_1_3' => [
                '1st' => ['DPT-HiB-HepB', 'OPV', 'PCV', 'IPV'],
                '2nd' => ['DPT-HiB-HepB', 'OPV', 'PCV'],
                '3rd' => ['DPT-HiB-HepB', 'OPV', 'PCV'],
            ],
            'months_6_11' => [
                '1st' => ['MMR'],
                '2nd' => ['IPV'],
            ],
            'months_12' => [
                '2nd' => ['MMR'],
            ],
        ];
    }

    private function scheduleDefinitions(Carbon $birthday): array
    {
        return [
            [
                'dose_key' => '1st',
                'title' => 'At birth',
                'window' => '0-28 days old',
                'medicines' => ['BCG', 'Hepa B-BD'],
                'due_date' => $birthday->copy(),
                'deadline_date' => $birthday->copy()->addDays(28),
            ],
            [
                'dose_key' => '1st',
                'title' => '6 weeks',
                'window' => 'First primary series dose',
                'medicines' => ['DPT-HiB-HepB', 'OPV', 'PCV', 'IPV'],
                'due_date' => $birthday->copy()->addWeeks(6),
                'deadline_date' => $birthday->copy()->addWeeks(10)->subDay(),
            ],
            [
                'dose_key' => '2nd',
                'title' => '10 weeks',
                'window' => 'Second primary series dose',
                'medicines' => ['DPT-HiB-HepB', 'OPV', 'PCV'],
                'due_date' => $birthday->copy()->addWeeks(10),
                'deadline_date' => $birthday->copy()->addWeeks(14)->subDay(),
            ],
            [
                'dose_key' => '3rd',
                'title' => '14 weeks',
                'window' => 'Third primary series dose',
                'medicines' => ['DPT-HiB-HepB', 'OPV', 'PCV'],
                'due_date' => $birthday->copy()->addWeeks(14),
                'deadline_date' => $birthday->copy()->addMonthsNoOverflow(9)->subDay(),
            ],
            [
                'dose_key' => '1st',
                'title' => '9 months',
                'window' => '6-11 months old',
                'medicines' => ['MMR'],
                'due_date' => $birthday->copy()->addMonthsNoOverflow(9),
                'deadline_date' => $birthday->copy()->addMonthsNoOverflow(12)->subDay(),
            ],
            [
                'dose_key' => '2nd',
                'title' => '9 months',
                'window' => '6-11 months old',
                'medicines' => ['IPV'],
                'due_date' => $birthday->copy()->addMonthsNoOverflow(9),
                'deadline_date' => $birthday->copy()->addMonthsNoOverflow(12)->subDay(),
            ],
            [
                'dose_key' => '2nd',
                'title' => '12 months',
                'window' => 'Second MMR dose',
                'medicines' => ['MMR'],
                'due_date' => $birthday->copy()->addMonthsNoOverflow(12),
                'deadline_date' => null,
            ],
        ];
    }

    private function formatInfantAge(Carbon $birthday, Carbon $today): string
    {
        $days = $birthday->diffInDays($today);
        $weeks = $birthday->diffInWeeks($today);
        $months = $birthday->diffInMonths($today);

        if ($days < 30) {
            return $days . ' day/s';
        }

        if ($weeks < 16) {
            return $weeks . ' week/s';
        }

        return $months . ' month/s';
    }
}
