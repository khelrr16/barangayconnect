<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\Health\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::with(['currentBatch', 'overall'])->get();
        $medicines->each(function ($medicine) {
            if ($medicine->overall && $medicine->overall->total_received > 0) {
                $totalReceived = $medicine->overall->total_received;
                $totalRemaining = $medicine->overall->total_remaining;
                
                $medicine->stockPercent = ceil(($totalRemaining / $totalReceived) * 100);
                
                // Set stock status based on percentage
                if ($medicine->stockPercent <= 20) {
                    $medicine->stockStatus = 'bg-danger';
                } elseif ($medicine->stockPercent <= 50) {
                    $medicine->stockStatus = 'bg-warning';
                } else {
                    $medicine->stockStatus = 'bg-success';
                }
            } else {
                // No batches found
                $medicine->stockPercent = 0;
                $medicine->stockStatus = 'bg-secondary'; // or 'bg-danger' for out of stock
            }
        });

        return view('committees.modules.health.medicines.index', compact('medicines'));
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
            'dose_volume' => 'required|string|max:255',
        ]);

        $medicine = Medicine::create($validated);

        return redirect()->route('committee.medicine.show', $medicine)
            ->with('success', 'Medicine created successfully.');
    }

    public function show(Medicine $medicine)
    {
        $medicine->load('batches');

        return view('committees.modules.health.medicines.show', compact('medicine'));
    }

    public function edit(string $id)
    {
        //
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
