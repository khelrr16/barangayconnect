<?php

namespace App\Http\Controllers\BudgetFinance;

use App\Http\Controllers\Controller;
use App\Models\Disbursement;
use App\Models\FundSource;

class BudgetOverviewController extends Controller
{
    public function index()
    {
        $totalDisbursements = Disbursement::sum('amount');
        $totalFundSources = FundSource::sum('amount');
        $balance = $totalFundSources - $totalDisbursements;
        $recentDisbursements = Disbursement::orderByDesc('date')->limit(5)->get();
        $recentFundSources = FundSource::orderByDesc('date')->limit(5)->get();

        return view('committees.modules.budget_finance.budget.index', compact(
            'totalDisbursements',
            'totalFundSources',
            'balance',
            'recentDisbursements',
            'recentFundSources'
        ));
    }
}
