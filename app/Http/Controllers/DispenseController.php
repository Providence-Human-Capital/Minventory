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
        $uin = $request->uin;

        $drug = $request->item_name;
        $drug_number = $request->item_number;
        $dependantcheck = $request->checkbox;

        $employee = DB::table('employees')->where('uin', $uin)->get()->first();

        return view('clinicstock.dispense', compact('employee', 'dependantcheck', 'drug', 'drug_number'));
    }

    public function dispense(Request $request)
    {
       
        $request->validate([
            'damount' => "required",

        ]);
        $dispense['UIN'] = $request->UIN;
        $dispense['drug'] = $request->drug;
        $dispense['damount'] = $request->damount;
        $dispense['dispenser'] = auth()->user()->name;
        $dispense['dispense_time'] = Carbon::now()->toDatetimeString();
        $dispense['clinic'] = auth()->user()->clinic;
        $damount = $request->damount;
        switch (auth()->user()->clinic) {
            case '81 Baines Avenue(Harare)':
                $currenstock = DB::table('avenue81_stocks')->where('item_number', 'like', $request->drug_number)->get()->first()->item_quantity;
                if($damount>$currentstock)
                {
                redirect()->route('requeststock')->with('error','Request more stock');
                }
                else
                $newstock = $currenstock - $damount;
                DB::table('avenue81_stocks')
                    ->where('item_number', 'like', $request->drug_number)
                    ->update(['avenue81_stocks.item_quantity' => $newstock]);
                break;
            case '52 Baines Avenue(Harare)':
                $currenstock = DB::table('avenue52_stocks')->where('item_number', 'like', $request->drug_number)->get()->first()->item_quantity;
                $newstock = $currenstock - $damount;
                DB::table('avenue52_stocks')
                    ->where('item_number', 'like', $request->drug_number)
                    ->update(['avenue52_stocks.item_quantity' => $newstock]);
                break;
            case '64 Cork road Avondale(Harare)':
                $currenstock = DB::table('avondale64_stocks')->where('item_number', 'like', $request->drug_number)->get()->first()->item_quantity;
                $newstock = $currenstock - $damount;
                DB::table('avondale64_stocks')
                    ->where('item_number', 'like', $request->drug_number)
                    ->update(['avondale64_stocks.item_quantity' => $newstock]);
                break;
            case '40 Josiah Chinamano Avenue(Harare)':
                $currenstock = DB::table('chimano40_stocks')->where('item_number', 'like', $request->drug_number)->get()->first()->item_quantity;
                $newstock = $currenstock - $damount;
                DB::table('chimano40_stocks')
                    ->where('item_number', 'like', $request->drug_number)
                    ->update(['chimano40_stocks.item_quantity' => $newstock]);
                break;
            case 'Epworth Clinic(Harare)':
                $currenstock = DB::table('epworth_stocks')->where('item_number', 'like', $request->drug_number)->get()->first()->item_quantity;
                $newstock = $currenstock - $damount;
                DB::table('epworth_stocks')
                    ->where('item_number', 'like', $request->drug_number)
                    ->update(['epworth_stocks.item_quantity' => $newstock]);
                break;
            case 'Fort Street and 9th Avenue(Bulawayo)':
                $currenstock = DB::table('fortstreet_stocks')->where('item_number', 'like', $request->drug_number)->get()->first()->item_quantity;
                $newstock = $currenstock - $damount;
                DB::table('fortstreet_stocks')
                    ->where('item_number', 'like', $request->drug_number)
                    ->update(['fortstreet_stocks.item_quantity' => $newstock]);
                break;
            case 'Royal Arcade Complex(Bulawayo)':
                $currenstock = DB::table('royalarcade_stocks')->where('item_number', 'like', $request->drug_number)->get()->first()->item_quantity;
                $newstock = $currenstock - $damount;
                DB::table('royalarcade_stocks')
                    ->where('item_number', 'like', $request->drug_number)
                    ->update(['royalarcade_stocks.item_quantity' => $newstock]);
                break;
            case '39 6th street(GWERU)':
                $currenstock = DB::table('street6gweru_stocks')->where('item_number', 'like', $request->drug_number)->get()->first()->item_quantity;
                $newstock = $currenstock - $damount;
                DB::table('street6gweru_stocks')
                    ->where('item_number', 'like', $request->drug_number)
                    ->update(['street6gweru_stocks.item_quantity' => $newstock]);
                break;
            case '126 Herbert Chitepo Street(Mutare)':
                $currenstock = DB::table('chitepo126mutare_stock')->where('item_number', 'like', $request->drug_number)->get()->first()->item_quantity;
                $newstock = $currenstock - $damount;
                DB::table('chitepo126mutare_stock')
                    ->where('item_number', 'like', $request->drug_number)
                    ->update(['chitepo126mutare_stock.item_quantity' => $newstock]);
                break;
            case '13 Shuvai Mahofa street(Masvingo)':
                $currenstock = DB::table('shuvaimahofa13masvingo_stocks')->where('item_number', 'like', $request->drug_number)->get()->first()->item_quantity;
                $newstock = $currenstock - $damount;
                DB::table('shuvaimahofa13masvingo_stocks')
                    ->where('item_number', 'like', $request->drug_number)
                    ->update(['shuvaimahofa13masvingo_stocks.item_quantity' => $newstock]);
                break;
        }

        if ($request->checkbox == null) {
            $person = employees::where('UIN', $request->UIN)->get()->first();
            $dispense['recipient'] = $person->name;
            dispense::create($dispense);
            return redirect()->route('getclinicstock')->with('success', 'Stock Dispensed.');
        } elseif ($request->dependant1 == null || $request->dependant2 !== null) {

            $person = employees::where('UIN', $request->UIN)->get()->first();
            $dispense['recipient'] = $person->dependant2;
            dispense::create($dispense);
            return redirect()->route('getclinicstock')->with('success', 'Stock Dispensed.');
        } elseif ($request->dependant1 !== null || $request->dependant2 == null) {
            $person = employees::where('UIN', $request->UIN)->get()->first();
            $dispense['recipient'] = $person->dependant1;
            dispense::create($dispense);
            return redirect()->route('getclinicstock')->with('success', 'Stock Dispensed.');
        } elseif ($request->dependant1 == null || $request->dependant2 == null) {
            return redirect()->back()->with('error', 'If patient is dependant select which dependant.Make sure you select only one dependant');
        }
    }

    public function dispensehistory() 
    {
        $disclinic = auth()->user()->clinic;
        $clinichis = dispense::where('clinic',$disclinic)->get();

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
        return view('clinci.transactionsearch', ['results' => $results]);
        

    }

    }

