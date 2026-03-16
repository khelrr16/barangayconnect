<?php

namespace App\Http\Controllers\BudgetFinance;

use App\Http\Controllers\Controller;
use App\Models\Disbursement;
use App\Models\FundSource;
use Illuminate\Http\Request;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $disbursementsQuery = Disbursement::query();
        $fundSourcesQuery = FundSource::query();

        if ($from) {
            $disbursementsQuery->whereDate('date', '>=', $from);
            $fundSourcesQuery->whereDate('date', '>=', $from);
        }
        if ($to) {
            $disbursementsQuery->whereDate('date', '<=', $to);
            $fundSourcesQuery->whereDate('date', '<=', $to);
        }

        $totalDisbursements = $disbursementsQuery->sum('amount');
        $totalFundSources = $fundSourcesQuery->sum('amount');
        $balance = $totalFundSources - $totalDisbursements;
        $disbursements = Disbursement::when($from, fn ($q) => $q->whereDate('date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('date', '<=', $to))
            ->orderByDesc('date')
            ->get();
        $fundSources = FundSource::when($from, fn ($q) => $q->whereDate('date', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('date', '<=', $to))
            ->orderByDesc('date')
            ->get();

        return view('committees.modules.budget_finance.reports.index', compact(
            'totalDisbursements',
            'totalFundSources',
            'balance',
            'disbursements',
            'fundSources',
            'from',
            'to'
        ));
    }
}
