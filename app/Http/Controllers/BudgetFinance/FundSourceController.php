<?php

namespace App\Http\Controllers\BudgetFinance;

use App\Http\Controllers\Controller;
use App\Models\FundSource;
use Illuminate\Http\Request;

class FundSourceController extends Controller
{
    public function index()
    {
        $fundSources = FundSource::orderByDesc('date')->paginate(15);

        return view('committees.modules.budget_finance.fund_sources.index', compact('fundSources'));
    }

    public function create()
    {
        return view('committees.modules.budget_finance.fund_sources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'nullable|date',
            'type' => 'nullable|string|max:255',
        ]);

        FundSource::create($validated);

        return redirect()->route('committee.fund-sources.index')->with('success', 'Fund source added successfully.');
    }
}
