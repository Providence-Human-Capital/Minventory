<?php

namespace App\Http\Controllers;

use App\Models\dispense;
use Illuminate\Http\Request;
use App\Models\employees;

class PatientController extends Controller
{
    public function showform()
    {
        return view('patients.patients');
    }

    public function searchhis(Request $request)
    {
     $employee =employees::where('uin',$request->uin)->get()->first();
     $history = dispense::where('UIN',$request->uin)->get();

     return view('patients.searchpatients', compact('employee','history'));
    }
}
