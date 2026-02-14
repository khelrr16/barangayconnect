<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing');
    }
}