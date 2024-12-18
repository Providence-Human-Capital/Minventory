<?php

namespace App\Http\Controllers;

use App\Models\mainstock_journal;
use App\Models\StockItem;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

class StockTransactionsController extends Controller
{
    public function show()
    {
        $entries = mainstock_journal::all();
        return view('StockTransactions.StockTransactions', ['entries' => $entries]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required',
            'item_quantity' => 'required',
            'item_number' => 'required',
            'price' => 'required'

        ]);
        $items['item_name'] = $request->item_name;
        $items['item_quantity'] = $request->item_quantity;
        $items['item_number'] = $request->item_number;
        $items['price'] = $request->price;
        $items['expiry_date'] = date('Y/m/d', strtotime($request->expiry_date));
        StockItem::create($items);
        $journal['item_name'] = $request->item_name;
        $journal['item_quantity'] = $request->item_quantity;
        $journal['item_number'] = $request->item_number;
        $journal['price'] = $request->price;
        $journal['procurer'] = auth()->user()->name;
        $journal['expiry_date'] = date('Y/m/d', strtotime($request->expiry_date));
        mainstock_journal::create($journal);
        return redirect()->route('dashboard')->with('success', 'Added to Main Stock.');
    }

    public function seachjournal(Request $request)
    {

        $query = mainstock_journal::query();

        // Apply filters based on the request inputs

        // Item Name
        if ($request->filled('item_name')) {
            $query->where('item_name', 'like', '%' . $request->item_name . '%');
        }

        // Item Number
        if ($request->filled('item_number')) {
            $query->where('item_number', $request->item_number);
        }

        // Main Stock (Clinic)
        if ($request->filled('clinics')) {
            $query->where('clinics', $request->clinics);
        }

        // Procurer
        if ($request->filled('procurer')) {
            $query->where('procurer', 'like', '%' . $request->procurer . '%');
        }

        // Amount Procured
        if ($request->filled('item_quantity_min') && $request->filled('item_quantity_max')) {
            dd($request->item_quantity_min, $request->item_qunatity_max);
            $query->whereBetween('item_quantity', [$request->item_quantity_min, $request->item_quantity_max]);
        }

        // Price of Procurement
        if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereBetween('price', [$request->price_min, $request->price_max]);
        }

        // Expiration Date
        if ($request->filled('expiry_date_from') && $request->filled('expiry_date_to')) {
            $query->whereBetween('expiry_date', [$request->expiry_date_from, $request->expiry_date_to]);
        }

        // Transaction Date
        if ($request->filled('transaction_date_from') && $request->filled('transaction_date_to')) {
            $query->whereBetween('created_at', [$request->transaction_date_from, $request->transaction_date_to]);
        }

        // Execute the query and get the results
        $results = $query->get();   
        return view('StockTransactions.transactionsearch', ['results' => $results]);
        

    }
}