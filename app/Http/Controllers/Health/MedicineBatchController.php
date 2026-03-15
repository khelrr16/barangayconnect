<?php

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Http\Requests\Health\MedicineBatchRequest;
use App\Models\Health\MedicineBatch;
use Illuminate\Http\Request;

class MedicineBatchController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(MedicineBatchRequest $request)
    {
        $validated = $request->validated();
        $validated['quantity_remaining'] = $validated['quantity_received'];
        
        $medicineBatch = MedicineBatch::create($validated);

        return redirect()->route('committee.medicine.show', $medicineBatch->medicine_id)
            ->with('success', 'Medicine batch created successfully.');
    }

    public function show(string $id)
    {
        //
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
