<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Admin can access everything
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Check if user has any of the required roles
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // If staff tries to access admin area
        if ($user->hasRole('staff') && in_array('admin', $roles)) {
            abort(403, 'Staff members cannot access user management.');
        }

        // Default unauthorized response
        abort(403, 'Unauthorized access.');
    }
}