<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request){
        $gender['male'] = Resident::where('sex', 'Male')->count();
        $gender['female'] = Resident::where('sex', 'Female')->count();
        $subdivision = $request->query('subdivision', 0);
        $a_subdivision = array('*', 'Conpil I Village', 'Conpil III Executive', 'Console 1 Village', 'Greatland Village', 'Guevara Subdivision', 'Pactia 2A', 'Pactia 2B');
        $civil_status = Resident::groupBy('civil_status')
        ->where('barangay', $a_subdivision[$subdivision])
        ->selectRaw('civil_status, COUNT(*) as total')
        ->pluck('total', 'civil_status');

        return view('admin.dashboard', compact('gender', 'civil_status'));
    }
}
