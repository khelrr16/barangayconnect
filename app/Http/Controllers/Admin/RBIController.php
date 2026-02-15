<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class RBIController extends Controller
{
    public function index()
    {
        return view('admin.rbi.index');
    }
}
