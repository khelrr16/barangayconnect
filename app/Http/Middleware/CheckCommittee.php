<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCommittee
{
    public function handle(Request $request, Closure $next, ...$committees)
    {

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        /** @var \App\Models\User $user */

        if ($user->hasRole('admin')) {
            return $next($request);
        }

        if ($user->hasRole('assistant')) {
            $slug = $user->assistedCommitteeSlug();
            if ($slug) {
                foreach ($committees as $committee) {
                    if ($slug === $committee) {
                        return $next($request);
                    }
                }
            }

            abort(403, 'Unauthorized access.');
        }

        if (!$user->official || !$user->official->committee) {
            abort(403, 'Unauthorized access.');
        }

        foreach ($committees as $committee) {
            if ($user->official->committee->slug === $committee) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized access.');
    }
}
