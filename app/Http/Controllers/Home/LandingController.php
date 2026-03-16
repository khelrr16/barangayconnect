<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;

class LandingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.index'));
        } elseif ($user->hasRole('clerk')) {
            return redirect()->intended(route('clerk.index'));
        } elseif ($user->hasRole('assistant') && $user->committeeAssistantAccessAsAssistant()->exists()) {
            return redirect()->intended(route('committee.index'));
        } elseif ($user->hasRole('committee_head') || $user->roles()->whereNotNull('committee_id')->exists()) {
            return redirect()->intended(route('committee.index'));
        } elseif ($user->hasRole('resident')) {
            return redirect()->intended(route('resident.index'));
        }

        return view('landing');
    }
}
