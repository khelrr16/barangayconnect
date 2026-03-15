<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Http\Requests\Health\InfantRequest;
use App\Models\ForeignHousehold;
use App\Models\Household;
use App\Models\Health\Infant;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class InfantController extends Controller
{
    public function index()
    {
        $infants = Infant::orderBy('created_at')->get();

        return view('committees.modules.health.immunizations.index', compact('infants'));
    }

    public function create()
    {
        return view('committees.modules.health.immunizations.create');
    }

    public function store(InfantRequest $request)
    {
        $validated = $request->validated();

        $newInfant = DB::transaction(function () use ($validated) {

            if($validated['address_type'] == 'san_lorenzo') {
                $household = Household::firstOrCreate(
                    Arr::only($validated, ['subdivision', 'street', 'block', 'lot', 'unit'])
                );
                $infantData = Arr::except($validated, ['address_type', 'subdivision', 'street', 'block', 'lot', 'unit']);
                $infantData['household_id'] = $household->id;
            } else {
                $household = ForeignHousehold::firstOrCreate(
                    Arr::only($validated, ['house_number', 'street', 'subdivision', 'barangay', 'city', 'province'])
                );
                $infantData = Arr::except($validated, ['address_type', 'house_number', 'street', 'subdivision', 'barangay', 'city', 'province']);
                $infantData['foreign_household_id'] = $household->id;
            }

            return Infant::create($infantData);
        });

        return redirect()->route('committee.health.immunization.show', $newInfant->id)
            ->with('success', 'Infant created successfully.');
    }

    public function show()
    {
        //
    }

    public function edit(Infant $infant)
    {
        //
    }

    public function update(InfantRequest $request, Infant $infant)
    {
        $validated = $request->validated();

        $newInfant = DB::transaction(function () use ($validated, $infant) {

            if($validated['address_type'] == 'san_lorenzo') {
                $household = Household::firstOrCreate(
                    Arr::only($validated, ['subdivision', 'street', 'block', 'lot', 'unit'])
                );
                $infantData = Arr::except($validated, ['address_type', 'subdivision', 'street', 'block', 'lot', 'unit']);
                $infantData['household_id'] = $household->id;
            } else {
                $household = ForeignHousehold::firstOrCreate(
                    Arr::only($validated, ['house_number', 'street', 'subdivision', 'barangay', 'city', 'province'])
                );
                $infantData = Arr::except($validated, ['address_type', 'house_number', 'street', 'subdivision', 'barangay', 'city', 'province']);
                $infantData['foreign_household_id'] = $household->id;
            }

            return $infant->update($infantData);
        });

        return redirect()->route('committee.immunization.edit', $newInfant)
            ->with('success', 'Infant updated successfully.');
    }

    public function destroy(Infant $infant)
    {
        //
    }
}
