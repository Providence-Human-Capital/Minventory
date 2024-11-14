<?php

namespace App\Http\Controllers;

use App\Models\dispense;
use App\Models\employees;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DispenseController extends Controller
{
    public function dispenseform(Request $request)
    {


        $drug = $request->item_name;
        $drug_number = $request->item_number;


        return view('clinicstock.dispense', compact('drug', 'drug_number'));
    }

    public function dispense(Request $request)
    {

        $request->validate([
            'damount' => "required",

        ]);
        $dispense['drug'] = $request->drug;
        $dispense['damount'] = $request->damount;
        $dispense['dispenser'] = auth()->user()->name;
        $dispense['dispense_time'] = Carbon::now()->toDatetimeString();
        $dispense['clinic'] = auth()->user()->clinic;
        $damount = $request->damount;
        $clinic = auth()->user()->clinic;
        $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic); // Clean clinic name
        $tableName = strtolower($tableName) . '_stocks';  // Add suffix for the stock table
        $currenstock = DB::table($tableName)->where('item_number', 'like', $request->drug_number)->get()->first()->item_quantity;
        if ($damount < $currenstock) {
            $newstock = $currenstock - $damount;
            DB::table($tableName)
                ->where('item_number', 'like', $request->drug_number)
                ->update(['item_quantity' => $newstock]);
            dispense::create($dispense);
            return redirect()->route('getclinicstock')->with('success', 'Stock Dispensed.');
        } 

        return redirect()->route('requeststock')->with('error', 'Request more stock');


    }

    public function dispensehistory()
    {
        $disclinic = auth()->user()->clinic;
        $clinichis = dispense::where('clinic', $disclinic)->get();

        return view('clinicstock.dispensedstock', compact('clinichis'));
    }

    public function searchhis(Request $request)
    {

        $query = dispense::query();

        // Apply filters based on the request inputs

        // UIN
        if ($request->filled('UIN')) {
            $query->where('UIN', 'like', '%' . $request->UIN . '%');
        }

        // drug
        if ($request->filled('drug')) {
            $query->where('drug', $request->drug);
        }

        // dispenser
        if ($request->filled('dispenser')) {
            $query->where('dispenser', $request->dispenser);
        }

        // Transaction Date
        if ($request->filled('transaction_date_from') && $request->filled('transaction_date_to')) {
            $query->whereBetween('dispense_time', [$request->transaction_date_from, $request->transaction_date_to]);
        }

        // Execute the query and get the results
        $results = $query->get();
        return view('clinicstock.dispensesearch', ['results' => $results]);
    }
}
