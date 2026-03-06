<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResidentRequest;
use App\Models\Household;
use App\Models\Program;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RBIController extends Controller
{
    public function index()
    {
        $residents = Resident::with('household')->get();
        return view('admin.rbi.index', compact('residents'));
    }

    public function printable(Request $request)
    {
        $categories = [
            'sex' => 'Sex',
            'civil_status' => 'Civil Status',
            'registered_voter' => 'Registered Voter',
            'employment_status' => 'Employment Status',
            'monthly_income' => 'Monthly Income',
            'role' => 'Relationship to Head',
        ];

        $selectedCategory = (string) $request->input('category', '');
        if (!array_key_exists($selectedCategory, $categories)) {
            $selectedCategory = '';
        }

        $selectedValue = (string) $request->input('value', '');

        $query = Resident::query()->with('household');

        $values = [];
        if ($selectedCategory !== '') {
            $values = Resident::query()
                ->whereNotNull($selectedCategory)
                ->where($selectedCategory, '!=', '')
                ->distinct()
                ->orderBy($selectedCategory)
                ->pluck($selectedCategory)
                ->values()
                ->all();

            if ($selectedValue !== '') {
                $query->where($selectedCategory, $selectedValue);
            }
        }

        $residents = $query
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('admin.rbi.printable', compact(
            'residents',
            'categories',
            'values',
            'selectedCategory',
            'selectedValue'
        ));
    }

    public function show($resident_id)
    {
        $resident = Resident::with(['household','household.head','commOrgs','programs','healthProfile'])->findOrFail($resident_id);
        return view('admin.rbi.profile', compact('resident'));
    }

    public function edit($resident_id)
    {
        $resident = Resident::with(['household','household.head','commOrgs','healthProfile'])->findOrFail($resident_id);
        $programs = Program::where('is_active', 1)->get();
        $residentPrograms = $resident->programs()
            ->withPivot('status', 'remarks', 'encoded_by')
            ->get()
            ->keyBy('id');

        return view('admin.rbi.edit', compact('resident', 'programs', 'residentPrograms'));
    }

    public function update(Request $request, $resident_id)
    {
        $resident = Resident::with(['commOrgs', 'healthProfile'])->findOrFail($resident_id);
        $updateTab = $request->input('updateTab');

        if ($updateTab === 'personal') {
            $resident->update($request->only([
                'first_name',
                'middle_name',
                'last_name',
                'extension_name',
                'sex',
                'birthday',
                'civil_status',
                'citizenship',
                'birthplace',
                'contact_number',
                'email',
                'registered_voter',
            ]));
        } else if ($updateTab === 'residency') {
            DB::beginTransaction();

            try {
                $address = [
                    'block' => $request->input('block'),
                    'lot' => $request->input('lot'),
                    'unit' => $request->filled('unit') ? (string) $request->input('unit') : null,
                    'street' => trim((string) $request->input('street')),
                    'subdivision' => trim((string) $request->input('subdivision')),
                ];

                $household = Household::firstOrCreate($address);

                $resident->update([
                    'household_id' => $household->id,
                    'role' => $request->input('role'),
                    'residence_since' => $request->input('residence_since'),
                    'ownership' => $request->input('ownership'),
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();

                return redirect()->back()
                    ->with('error', 'Failed to update residency information. ' . $e->getMessage())
                    ->withInput();
            }
        } else if ($updateTab === 'socioEconomic') {
            $resident->update($request->only([
                'educational_attainment',
                'occupation',
                'employment_status',
                'monthly_income',
                'religion',
            ]));
        }

        if($request->updateTab == 'beneficiary')
        {
            DB::beginTransaction();

            try {
                // Get current program IDs
                $currentProgramIds = $resident->programs()->pluck('programs.id')->toArray();
                
                // Get submitted program IDs
                $submittedProgramIds = $request->input('programs', []);
                
                // Programs to detach (in current but not in submitted)
                $programsToDetach = array_diff($currentProgramIds, $submittedProgramIds);
                if (!empty($programsToDetach)) {
                    $resident->programs()->detach($programsToDetach);
                }
                
                // Programs to attach or update
                foreach ($submittedProgramIds as $programId) {
                    $status = $request->input("status.{$programId}", 'pending');
                    $remarks = $request->input("remarks.{$programId}");
                    
                    $pivotData = [
                        'status' => $status,
                        'remarks' => $remarks,
                        'encoded_by' => Auth::id(),
                        'updated_at' => now()
                    ];
                    
                    // Check if already attached
                    if (in_array($programId, $currentProgramIds)) {
                        // Update existing pivot
                        $resident->programs()->updateExistingPivot($programId, $pivotData);
                    } else {
                        // Attach new program
                        $pivotData['created_at'] = now();
                        $resident->programs()->attach($programId, $pivotData);
                    }
                }
                
                DB::commit();
                    
            } catch (\Exception $e) {
                DB::rollBack();
                
                return redirect()->back()
                    ->with('error', 'Failed to update program assignments. ' . $e->getMessage())
                    ->withInput();
            }
        }

        else if($request->updateTab == 'commOrg'){

            $selectedOrgs = $request->input('org', []);
            $currentOrgs = $resident->commOrgs->pluck('organization')->toArray();
            
            $orgsToAdd = array_diff($selectedOrgs, $currentOrgs);
            $orgsToRemove = array_diff($currentOrgs, $selectedOrgs);

            DB::beginTransaction();

            try {

                foreach ($orgsToAdd as $org) {
                    $resident->commOrgs()->create([
                        'organization' => $org
                    ]);
                }
                
                if (!empty($orgsToRemove)) {
                    $resident->commOrgs()
                        ->whereIn('organization', $orgsToRemove)
                        ->delete();
                }
                
                DB::commit();
                
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Failed to update organizations: ' . $e->getMessage())
                    ->withInput();
            }
        }

        else if($request->updateTab == 'healthInfo'){

            $selectedCondition = $request->input('condition', []);
            $currentCondition = $resident->healthProfile->pluck('health_condition')->toArray();
            
            $conditionsToAdd = array_diff($selectedCondition, $currentCondition);
            $conditionsToRemove = array_diff($currentCondition, $selectedCondition);

            DB::beginTransaction();

            try {

                foreach ($conditionsToAdd as $condition) {
                    $resident->healthProfile()->create([
                        'health_condition' => $condition
                    ]);
                }
                
                if (!empty($conditionsToRemove)) {
                    $resident->healthProfile()
                        ->whereIn('health_condition', $conditionsToRemove)
                        ->delete();
                }
                
                DB::commit();
                
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Failed to update health profile: ' . $e->getMessage())
                    ->withInput();
            }
        }

        return redirect()->route('admin.rbi.edit', ['rbi' => $resident_id, 'tab' => $updateTab])
            ->with('success', 'Resident updated successfully.');
    }

    public function create(Request $request)
    {
        $programs = Program::where('is_active', 1)->get();
        $prefillAddress = $request->only(['subdivision', 'street', 'block', 'lot', 'unit']);

        return view('admin.rbi.create', compact('programs', 'prefillAddress'));
    }

    public function store(StoreResidentRequest $request)
    {
        $validated = $request->validated();

        $resident = DB::transaction(function () use ($request, $validated) {
            $household = Household::firstOrCreate(
                Arr::only($validated, ['subdivision', 'street', 'block', 'lot', 'unit'])
            );

            $residentData = Arr::except($validated, ['subdivision', 'street', 'block', 'lot', 'unit']);
            $residentData['household_id'] = $household->id;
            $residentData['contact_number'] = $residentData['contact_number'] ?? '';
            $residentData['email'] = $residentData['email'] ?? '';

            $resident = Resident::create($residentData);

            $programsSyncData = [];
            foreach (array_unique($request->input('programs', [])) as $programId) {
                $programsSyncData[$programId] = [
                    'status' => $request->input("status.{$programId}", 'pending'),
                    'remarks' => $request->input("remarks.{$programId}"),
                    'encoded_by' => Auth::id(),
                ];
            }

            if (!empty($programsSyncData)) {
                $resident->programs()->sync($programsSyncData);
            }

            $orgs = array_unique($request->input('org', []));
            if (!empty($orgs)) {
                $resident->commOrgs()->createMany(array_map(
                    fn ($org) => ['organization' => $org],
                    $orgs
                ));
            }

            $conditions = array_unique($request->input('condition', []));
            if (!empty($conditions)) {
                $resident->healthProfile()->createMany(array_map(
                    fn ($condition) => ['health_condition' => $condition],
                    $conditions
                ));
            }

            return $resident;
        });

        return redirect()->route('admin.rbi.show', $resident->id)
            ->with('success', 'Resident created successfully.');
    }
}
