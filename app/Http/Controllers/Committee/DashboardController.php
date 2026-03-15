<?php

namespace App\Http\Controllers\Committee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $committeeSlug = Auth::user()?->official?->committee?->slug;

        if ($committeeSlug === 'health_sanitation') {
            return redirect()->route('committee.health.dashboard');
        }

        return view('committees.dashboard');
    }
}
