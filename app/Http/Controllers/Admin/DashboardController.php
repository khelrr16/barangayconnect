<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $civilStatuses = ['Single', 'Married', 'Widow/Widower', 'Separated'];
    protected $sexes = ['Male', 'Female'];
    protected $ages = ['0-17', '18-30', '31-45', '46-60', '60+'];

    protected $employmentCategories = [
        'Student' => ['Student', 'Out of School Children', 'Out of School Youth'],
        'Employed' => ['Employment Full-time', 'Employment Part-time', 'Self-Employed'],
        'Retired' => ['Retired'],
        'Unemployed' => ['Unemployed'],
    ];
    
    protected $subdivisions = [
        'Conpil I Village',
        'Conpil III Executive', 
        'Console 1 Village',
        'Greatland Village',
        'Guevara Subdivision',
        'Pacita 2A',
        'Pacita 2B'
    ];

    public function index()
    {
        return view('admin.dashboard', [
            'subdivisions' => $this->subdivisions,
            'selectedSubdivision' => request('subdivision', '')
        ]);
    }

    public function getChartData(Request $request)
    {
        $subdivision = $request->query('subdivision');
        
        $r_query = Resident::query();
        $h_query = Household::query();
        
        if ($subdivision) {
            $r_query->whereHas('household', function ($q) use ($subdivision) {
                $q->where('subdivision', $subdivision);
            });

            $h_query->where('subdivision', $subdivision);
        }

        return response()->json([
            'statistics' => [
                'residents' => $this->getStatistics($r_query, $h_query)['residents'],
                'households' => $this->getStatistics($r_query, $h_query)['households'],
                'laborForce' => $this->getEmploymentStats($r_query)['labor_force'],
                'registeredVoters' => $this->getStatistics($r_query, $h_query)['registered_voters']
            ],
            'sex' => [
                'labels' => $this->sexes,
                'data' => array_values($this->getSexStats($r_query)),
                'total' => array_sum($this->getSexStats($r_query)),
                'colors' => [
                    '#36A2EB',
                    '#FF6384'
                ]
            ],
            'age' => [
                'labels' => $this->ages,
                'data' => array_values($this->getAgeStats($r_query)),
                'total' => array_sum($this->getAgeStats($r_query)),
                'colors' => [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#FF6384',
                ]
            ],
            'civil_status' => [
                'labels' => $this->civilStatuses,
                'data' => array_values($this->getCivilStatusStats($r_query)),
                'total' => array_sum($this->getCivilStatusStats($r_query)),
                'colors' => [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4bc04b',
                ]
            ],
            'employment' => [
                'labels' => array_keys($this->getEmploymentStats($r_query)['percentages']),
                'data' => array_values($this->getEmploymentStats($r_query)['categories']),
                'total' => array_sum($this->getEmploymentStats($r_query)['categories']),
                'colors' => [
                    '#FF6384',
                    '#36A2EB',
                    '#f837ff',
                    '#FFCE56',
                    '#4bc04b',
                ],
            ],
        ]);
    }

    protected function getStatistics($rQuery, $hQuery)
    {
        $totalResidents = (clone $rQuery)->count();
        $totalHouseholds = (clone $hQuery)->count();

        // Get registered voters (assuming age >= 18 or has voter_id)
        $registeredVoters = (clone $rQuery)
            ->whereNot('registered_voter', 'No') // or wherever your voter flag is
            ->count();

        return [
            'residents' => $totalResidents,
            'households' => $totalHouseholds,
            'registered_voters' => $registeredVoters,
        ];
    }

    protected function getSexStats($baseQuery)
    {
        $stats = (clone $baseQuery)
            ->select('sex')
            ->selectRaw('count(*) as total')
            ->whereIn('sex', $this->sexes)
            ->groupBy('sex')
            ->pluck('total', 'sex')
            ->toArray();

        return collect($this->sexes)
            ->mapWithKeys(fn($sex) => [$sex => $stats[$sex] ?? 0])
            ->toArray();
    }

    protected function getAgeStats($baseQuery)
    {
        $stats = (clone $baseQuery)
            ->selectRaw("
                CASE 
                    WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 0 AND 17 THEN '0-17'
                    WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 18 AND 30 THEN '18-30'
                    WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 31 AND 45 THEN '31-45'
                    WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 46 AND 60 THEN '46-60'
                    WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) > 60 THEN '60+'
                END as age_group,
                COUNT(*) as total
            ")
            ->whereNotNull('birthday')
            ->groupBy('age_group')
            ->pluck('total', 'age_group')
            ->toArray();

        return collect($this->ages)
            ->mapWithKeys(fn($ages) => [$ages => $stats[$ages] ?? 0])
            ->toArray();
    }
    protected function getCivilStatusStats($baseQuery)
    {
        $stats = (clone $baseQuery)
            ->select('civil_status')
            ->selectRaw('count(*) as total')
            ->whereIn('civil_status', $this->civilStatuses)
            ->groupBy('civil_status')
            ->pluck('total', 'civil_status')
            ->toArray();

        return collect($this->civilStatuses)
            ->mapWithKeys(fn($status) => [$status => $stats[$status] ?? 0])
            ->toArray();
    }

    private function getEmploymentStats($baseQuery)
    {
        $percentages = [];
        $categoriesResult = [];

        // Build CASE statement dynamically
        $caseSql = "CASE ";
        foreach ($this->employmentCategories as $category => $statuses) {
            $statusList = "'" . implode("', '", $statuses) . "'";
            $caseSql .= "WHEN employment_status IN ($statusList) THEN '$category' ";
        }
        $caseSql .= "ELSE 'Others' END as category";
        
        $results = (clone $baseQuery)
            ->selectRaw("$caseSql, COUNT(*) as total")
            ->whereNotNull('employment_status')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();
        
        foreach (array_keys($this->employmentCategories) as $category) {
            $categoriesResult[$category] = $results[$category] ?? 0;
        }
        $categoriesResult['Others'] = $results['Others'] ?? 0;
        
        // Calculate statistics
        $total = array_sum($categoriesResult);
        foreach ($categoriesResult as $key => $value) {
            $percentages[$key] = round(($value / $total) * 100);
        }
        $laborForce = ($categoriesResult['Employed'] ?? 0) + ($categoriesResult['Unemployed'] ?? 0);
        
        return [
            'categories' => $categoriesResult,
            'percentages' => $percentages,
            'labor_force' => $laborForce,
            'employment_rate' => $laborForce > 0
                ? round(($categoriesResult['Employed'] ?? 0) / $laborForce * 100)
                : 0,
            'unemployment_rate' => $laborForce > 0 
                ? round(($categoriesResult['Unemployed'] ?? 0) / $laborForce * 100) 
                : 0
        ];
    }
    
}