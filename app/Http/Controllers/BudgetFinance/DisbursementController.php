<?php

namespace App\Http\Controllers\BudgetFinance;

use App\Http\Controllers\Controller;
use App\Models\Disbursement;
use Illuminate\Http\Request;

class DisbursementController extends Controller
{
    public function index()
    {
        $disbursements = Disbursement::orderByDesc('date')->paginate(15);

        return view('committees.modules.budget_finance.disbursements.index', compact('disbursements'));
    }

    public function create()
    {
        return view('committees.modules.budget_finance.disbursements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:255',
        ]);

        Disbursement::create($validated);

        return redirect()->route('committee.disbursements.index')->with('success', 'Disbursement recorded successfully.');
    }
}
