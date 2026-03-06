<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateHouseholdRequest;
use App\Models\Household;
use Illuminate\Http\Request;

class HHController extends Controller
{
    public function index(Request $request)
    {
        $selectedSubdivision = trim((string) $request->query('subdivision', ''));
        $selectedStreet = trim((string) $request->query('street', ''));

        $query = Household::query();

        if ($selectedSubdivision !== '') {
            $query->where('subdivision', $selectedSubdivision);
        }

        if ($selectedStreet !== '') {
            $query->where('street', $selectedStreet);
        }

        $households = $query->get();

        $subdivisionStreetMap = Household::query()
            ->select('subdivision', 'street')
            ->whereNotNull('subdivision')
            ->where('subdivision', '!=', '')
            ->get()
            ->groupBy('subdivision')
            ->map(function ($items) {
                return $items->pluck('street')
                    ->filter(fn ($street) => $street !== null && $street !== '')
                    ->unique()
                    ->sort()
                    ->values();
            })
            ->sortKeys();

        $subdivisions = $subdivisionStreetMap->keys()->values();

        return view('admin.hh.index', compact(
            'households',
            'subdivisionStreetMap',
            'subdivisions',
            'selectedSubdivision',
            'selectedStreet'
        ));
    }

    public function show($household_id)
    {
        $household = Household::with('residents')->findOrFail($household_id);
        return view('admin.hh.profile', compact('household'));
    }

    public function edit($household_id)
    {
        $household = Household::with('residents')->findOrFail($household_id);
        return view('admin.hh.edit', compact('household'));
    }

    public function update(UpdateHouseholdRequest $request, $household_id)
    {
        $household = Household::findOrFail($household_id);
        $household->update($request->validated());

        return redirect()->route('admin.hh.edit', $household_id)->with('success', 'Household updated successfully.');
    }

    public function create()
    {
        return view('admin.hh.create');
    }
}
