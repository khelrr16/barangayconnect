<?php

namespace App\Http\Controllers;

use App\Models\ForeignHousehold;
use App\Models\Household;
use App\Models\Infant;
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
        return view('committees.modules.health.immunizations.create');
    }

    public function store(Request $request)
    {
        $validation = [
            'family_serial_number' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'sex' => 'required|string',
            'birthday' => 'required|date',
            'mother_name' => 'required|string|max:255',
            
        ];

        $sanLorenzo = $request->input('address_type') == 'san_lorenzo';

        if($sanLorenzo){
            $validation = array_merge($validation, [
                'block' => 'required|string|max:50',
                'lot' => 'required|string|max:50',
                'unit' => 'nullable|string|max:50',
                'street' => 'required|string|max:255',
                'subdivision' => 'required|string|max:255',
            ]);
        } else {
            $validation = array_merge($validation, [
                'house_number' => 'required|string|max:500',
                'street' => 'nullable|string|max:255',
                'subdivision' => 'nullable|string|max:255',
                'barangay' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'province' => 'required|string|max:255',
            ]);
        }
        
        $validated = $request->validate($validation);

        $infant = DB::transaction(function () use ($validated, $sanLorenzo) {
            
            if($sanLorenzo) {
                $household = Household::firstOrCreate(
                    Arr::only($validated, ['subdivision', 'street', 'block', 'lot', 'unit'])
                );
                $infantData = Arr::except($validated, ['subdivision', 'street', 'block', 'lot', 'unit']);
            } else {
                $household = ForeignHousehold::firstOrCreate(
                    Arr::only($validated, ['house_number', 'street', 'subdivision', 'barangay', 'city', 'province'])
                );
                $infantData = Arr::except($validated, ['house_number', 'street', 'subdivision', 'barangay', 'city', 'province']);
            }

            $infantData['household_id'] = $household->id;
            $infant = Infant::create($infantData);
            return $infant;
        });

        return redirect()->route('committee.immunization.show', $infant->id)
            ->with('success', 'Infant created successfully.');
    }

    public function show($infant_id)
    {
        $infant = Infant::findOrFail($infant_id);
        return view('committees.modules.health.immunizations.profile', compact('infant'));
    }

    public function edit($infant_id)
    {
        $infant = Infant::findOrFail($infant_id);

        return view('committees.modules.health.immunizations.edit', compact('infant'));
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
