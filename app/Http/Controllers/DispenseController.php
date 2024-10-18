<?php

namespace App\Http\Controllers;

use App\Models\employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispenseController extends Controller
{
    public function dispenseform(Request $request)
    {
        $uin =$request->uin;
        $drug = $request->item_name;
        $dependantcheck = $request->checkbox;
        
        $employee =DB::table('employee')->where('uin',$uin)->get()->first();

     return view('clinicstock.dispense',compact('employee','dependantcheck','drug'));
    }
}
