<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommitteeRole
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return $next($request);
        }

        $hasCommitteeRole = $user->roles()->where(function ($query) {
            $query->where('name', 'committee_head')
                ->orWhereNotNull('committee_id');
        })->exists();

        if ($hasCommitteeRole) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
