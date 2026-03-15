<?php

namespace App\Http\Controllers\Peace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlotterController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        return view('committees.modules.peace.blotter.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
