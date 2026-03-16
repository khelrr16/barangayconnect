<?php

namespace App\Http\Controllers\Committee\Health;

use App\Http\Controllers\Controller;
use App\Models\Health\Immunization;
use App\Models\Health\Infant;
use App\Models\Health\NutritionalAssessment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('committees.modules.health.dashboard', $this->buildReportData($request));
    }

    public function print(Request $request)
    {
        return view('committees.modules.health.dashboard_print', $this->buildReportData($request));
    }

    public function pdf(Request $request)
    {
        $data = $this->buildReportData($request);

        $pdf = Pdf::loadView('committees.modules.health.dashboard_pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('health-dashboard-' . $data['periodSlug'] . '.pdf');
    }

    private function buildReportData(Request $request): array
    {
        $mode = $request->string('mode')->toString();
        $mode = in_array($mode, ['month', 'year'], true) ? $mode : 'month';

        $currentYear = now()->year;
        $year = (int) $request->input('year', $currentYear);
        if ($year < 2000 || $year > $currentYear + 5) {
            $year = $currentYear;
        }

        $month = (int) $request->input('month', now()->month);
        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }

        if ($mode === 'month') {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $periodLabel = $startDate->format('F Y');
            $periodSlug = $startDate->format('Y-m');
        } else {
            $startDate = Carbon::create($year, 1, 1)->startOfYear();
            $endDate = $startDate->copy()->endOfYear();
            $periodLabel = (string) $year;
            $periodSlug = (string) $year;
        }

        $dateStart = $startDate->toDateString();
        $dateEnd = $endDate->toDateString();

        $immunizationUsage = Immunization::query()
            ->join('medicines', 'medicines.id', '=', 'immunizations.medicine_id')
            ->whereBetween('immunizations.administration_date', [$dateStart, $dateEnd])
            ->selectRaw('medicines.name as medicine_name, COUNT(*) as total_used, COUNT(DISTINCT immunizations.infant_id) as infants_covered')
            ->groupBy('medicines.name')
            ->orderBy('medicines.name')
            ->get()
            ->keyBy('medicine_name');

        $directMedicineConfig = [
            ['name' => 'Iron', 'fields' => ['iron_1', 'iron_2', 'iron_3']],
            ['name' => 'Vitamin A', 'fields' => ['vitamin_a']],
            ['name' => 'MNP', 'fields' => ['mnp_start', 'mnp_end']],
        ];

        $directMedicineUsage = collect($directMedicineConfig)
            ->map(function (array $definition) use ($dateStart, $dateEnd) {
                $totalUsed = 0;

                foreach ($definition['fields'] as $field) {
                    $totalUsed += Infant::query()
                        ->whereBetween($field, [$dateStart, $dateEnd])
                        ->count();
                }

                $infantsCovered = Infant::query()
                    ->where(function ($query) use ($definition, $dateStart, $dateEnd) {
                        foreach ($definition['fields'] as $index => $field) {
                            if ($index === 0) {
                                $query->whereBetween($field, [$dateStart, $dateEnd]);
                            } else {
                                $query->orWhereBetween($field, [$dateStart, $dateEnd]);
                            }
                        }
                    })
                    ->count();

                return [
                    'medicine_name' => $definition['name'],
                    'direct_total' => $totalUsed,
                    'direct_infants' => $infantsCovered,
                ];
            })
            ->keyBy('medicine_name');

        $medicineNames = $immunizationUsage->keys()
            ->merge($directMedicineUsage->keys())
            ->unique()
            ->sort()
            ->values();

        $medicineUsage = $medicineNames->map(function (string $medicineName) use ($immunizationUsage, $directMedicineUsage) {
            $immunization = $immunizationUsage->get($medicineName);
            $direct = $directMedicineUsage->get($medicineName);

            $immunizationTotal = (int) ($immunization->total_used ?? 0);
            $immunizationInfants = (int) ($immunization->infants_covered ?? 0);
            $directTotal = (int) ($direct['direct_total'] ?? 0);
            $directInfants = (int) ($direct['direct_infants'] ?? 0);

            return [
                'medicine_name' => $medicineName,
                'immunization_total' => $immunizationTotal,
                'immunization_infants' => $immunizationInfants,
                'direct_total' => $directTotal,
                'direct_infants' => $directInfants,
                'overall_total' => $immunizationTotal + $directTotal,
                'overall_infants' => max($immunizationInfants, $directInfants),
            ];
        })->values();

        $monitoringInfants = Infant::query()
            ->whereNotNull('status')
            ->where('status', '!=', '')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->orderByDesc('updated_at')
            ->get(['id', 'name', 'status', 'updated_at']);

        $monitoringStatusSummary = $monitoringInfants
            ->groupBy(function (Infant $infant) {
                return trim((string) $infant->status) !== '' ? $infant->status : 'Unspecified';
            })
            ->map(function (Collection $group) {
                return $group->count();
            })
            ->sortDesc();

        $nutritionalRecords = NutritionalAssessment::query()
            ->join('infants', 'infants.id', '=', 'nutritional_assessments.infant_id')
            ->whereBetween('nutritional_assessments.assessment_date', [$dateStart, $dateEnd])
            ->orderByDesc('nutritional_assessments.assessment_date')
            ->get([
                'nutritional_assessments.infant_id',
                'infants.name as infant_name',
                'nutritional_assessments.category',
                'nutritional_assessments.status',
                'nutritional_assessments.assessment_date',
            ]);

        $latestNutritionalByInfant = $nutritionalRecords
            ->groupBy('infant_id')
            ->map(function (Collection $group) {
                return $group->first();
            })
            ->values();

        $nutritionalStatusSummary = $latestNutritionalByInfant
            ->groupBy(function ($record) {
                return trim((string) $record->status) !== '' ? $record->status : 'Unspecified';
            })
            ->map(function (Collection $group) {
                return $group->count();
            })
            ->sortDesc();

        $years = range($currentYear, max(2024, $currentYear - 5));

        $months = collect(range(1, 12))->map(function (int $monthNumber) {
            $date = Carbon::create(2000, $monthNumber, 1);

            return [
                'value' => $monthNumber,
                'label' => $date->format('F'),
            ];
        });

        return [
            'mode' => $mode,
            'year' => $year,
            'month' => $month,
            'years' => $years,
            'months' => $months,
            'periodLabel' => $periodLabel,
            'periodSlug' => $periodSlug,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'medicineUsage' => $medicineUsage,
            'monitoringInfants' => $monitoringInfants,
            'monitoringStatusSummary' => $monitoringStatusSummary,
            'latestNutritionalByInfant' => $latestNutritionalByInfant,
            'nutritionalStatusSummary' => $nutritionalStatusSummary,
            'totals' => [
                'overall_medicine_used' => $medicineUsage->sum('overall_total'),
                'monitoring_infants' => $monitoringInfants->count(),
                'nutritional_infants' => $latestNutritionalByInfant->count(),
            ],
        ];
    }
}
