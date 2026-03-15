<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user) {
            abort(403, 'Unauthorized access.');
        }

        if (! $user->hasRole('assistant') && ! $user->can('view committee_dashboard')) {
            abort(403, 'Unauthorized access.');
        }

        $committeeSlug = $user?->official?->committee?->slug;

        if ($committeeSlug === null && $user?->hasRole('assistant')) {
            $committeeSlug = $user->assistedCommitteeSlug();
        }

        if ($committeeSlug === 'health_sanitation') {
            return redirect()->route('committee.health.index');
        } else if ($committeeSlug === 'peace_order') {
            return redirect()->route('committee.peace.blotter.index');
        } else if ($committeeSlug === 'budget_finance') {
            return redirect()->route('committee.budget.index');
        }

        return abort(404);
    }
}
