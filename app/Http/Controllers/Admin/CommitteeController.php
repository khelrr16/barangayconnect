<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Committee;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    public function index()
    {
        $committees = Committee::all();
        return view('committees.index', compact('committees'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:committees,name',
            'description' => 'nullable|string',
        ]);

        Committee::create($request->only('name', 'description'));

        return redirect()->route('admin.committee.index')->with('success', 'Committee created successfully.');
    }

    public function edit(Committee $committee)
    {
        return view('admin.committees.edit', compact('committee'));
    }

    public function update(Request $request, Committee $committee)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:committees,name,' . $committee->id,
            'description' => 'nullable|string',
        ]);

        $committee->update($request->only('name', 'description'));

        return redirect()->route('admin.committee.index')->with('success', 'Committee updated successfully.');
    }

    public function destroy(Committee $committee)
    {
        $committee->delete();
        return redirect()->route('admin.committee.index')->with('success', 'Committee deleted successfully.');
    }

    public function redirect()
    {
        return redirect()->route('admin.committee.index');
    }
}
