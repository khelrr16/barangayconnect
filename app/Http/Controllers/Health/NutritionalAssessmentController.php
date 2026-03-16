<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\Health\Infant;
use App\Models\Health\NutritionalAssessment;
use Illuminate\Http\Request;

class NutritionalAssessmentController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, Infant $infant)
    {
        $tabs = ['newborn', 'months_1_3', 'months_6_11', 'months_12'];
        $tab = (string) $request->input('tab');
        
        if (!in_array($tab, $tabs)) {
            return back()->withErrors(['tab' => 'Invalid tab submitted.']);
        }

        $validated = $request->validate([
            'age' => ['nullable', 'string', 'max:50'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'length' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'status' => ['nullable', 'string', 'max:255'],
            'assessment_date' => ['nullable', 'date'],
        ]);

        NutritionalAssessment::updateOrCreate(
            [
                'infant_id' => $infant->id,
                'category' => $tab,
            ],
            [
                'age' => $validated['age'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'length' => $validated['length'] ?? null,
                'status' => $validated['status'] ?? null,
                'assessment_date' => $validated['assessment_date'] ?? null,
            ]
        );

        return back()->with([
            'success' => 'Nutritional assessment updated successfully.',
            'activeTab' => $tab
        ]);
    }

    public function destroy(string $id)
    {
        //
    }
}
