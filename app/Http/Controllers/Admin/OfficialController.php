<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Official;
use App\Models\Committee;
use Illuminate\Http\Request;

class OfficialController extends Controller
{
    public function index()
    {
        $officials = Official::with('committee')->get();
        $committees = Committee::all();
        
        return view('admin.officials.index', compact('officials', 'committees'));
    }

    public function create()
    {
        return view('admin.officials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'committee_id' => 'nullable|exists:committees,id',
            'term_start' => 'required|date',
            'term_end' => 'required|date|after_or_equal:term_start',
        ]);

        Official::create($request->only('name', 'position', 'committee_id', 'term_start', 'term_end'));

        return redirect()->route('admin.official.index')->with('success', 'Official created successfully.');
    }

    public function update(Request $request, Official $official)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'committee_id' => 'nullable|exists:committees,id',
            'term_start' => 'required|date',
            'term_end' => 'required|date|after_or_equal:term_start',
        ]);

        $official->update($request->only('name', 'position', 'committee_id', 'term_start', 'term_end'));

        return redirect()->route('admin.official.index')->with('success', 'Official updated successfully.');
    }

    public function destroy(Official $official)
    {
        $official->delete();
        return redirect()->route('admin.official.index')->with('success', 'Official deleted successfully.');
    }
}
