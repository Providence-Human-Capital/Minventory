<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\mainstock_journal;
use App\Models\pending_stock;
use App\Models\pending_stocks;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class mainStockController extends Controller
{
    public function showmain()
    {
        $stock = StockItem::all();

        return view(
            'mainStock.mainStock',
            [
                'stock' => $stock
            ]
        );
    }

    public function addnewitem(Request $request)
    {
        $request->validate(
            [
                'item_name' => 'required',
                'item_quantity' => 'required',
                'item_number' => 'required',
            ]
        );
        $items['item_name'] = $request->item_name;
        $items['item_number'] = $request->item_number;
        $items['item_quantity'] = $request->item_quantity;
        StockItem::create($items);
        $clinics= Clinic::all();
        foreach($clinics as $clinic)
        {
            $clinicname= $clinic->clinic_name;
            $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinicname); // Clean clinic name
            $tableName = strtolower($tableName) . '_stocks';
            DB::table($tableName)->insert($items);
    
        }
       


        return redirect()->route('mainstock')->with('success', 'Product Added.');
    }
    public function searchmain(Request $request)
    {

        $searchTerm = $request->input('isearch'); // Get the search query from the request
        $pending = [];
        $pending = DB::table('stock_items')->where('item_name', 'LIKE', "%{$searchTerm}%")->get();
        if ($pending->isEmpty()) {
            return redirect()->route('mainstock')->with('error', 'Product could not be found');
        } else {
            return view('Mainstock.search', ['search' => $pending]);
        }
    }

    public function updatemain(Request $request, StockItem $stockItem)
    {
        $request->validate([
            'item_name' => 'required',
            'item_quantity' => 'required',
            'item_number' => 'required',
            'price' => 'required',
            'batch_number' => 'required',
            'item_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $imagename = $request->item_image . '.' . $request->item_image->extension();
        $request->item_image->move(public_path('images'), $imagename);
        $items['item_number'] = $request->item_number;
        $items['price'] = $request->price;
        $items['expiry_date'] = date('Y/m/d', strtotime($request->expiry_date));
        $search = $request->item_number;
        $currenstock = StockItem::where('item_number', 'like', $search)->get()->first()->item_quantity;
        $addstock = $request->item_quantity;
        $newstock = $currenstock + $addstock;
        $items['item_quantity'] = $newstock;
        $stockItem->update($items);
        $journal['item_name'] = StockItem::where('item_number', 'like', $search)->get()->first()->item_name;
        $journal['item_quantity'] = $request->item_quantity;
        $journal['item_number'] = $request->item_number;
        $journal['price'] = $request->price;
        $journal['procurer'] = auth()->user()->name;
        $journal['to_from_mainstock'] = "TO";
        $journal['p_o_d'] = 'images/' . $imagename;
        $journal['batch_number'] = $request->batch_number;
        $journal['expiry_date'] = date('Y/m/d', strtotime($request->expiry_date));
        mainstock_journal::create($journal);
        return redirect()->route('mainstock')->with('success', 'Added to Main Stock.');
    }


    public function searchitem(Request $request)
    {

        $searchTerm = $request->input('isearch'); // Get the search query from the request
        $pending = [];
        $pending = DB::table('stock_items')->where('item_name', 'LIKE', "%{$searchTerm}%")->get();
        if ($pending->isEmpty()) {
            return redirect()->route('mainstock')->with('error', 'Product could not be found');
        } else {
            return view('Mainstock.search', ['search' => $pending]);
        }
    }

    public function bulkform()
    {
        return view('Mainstock.bulkform');
    }

    public function bulkformadd()
    {
        return view('Mainstock.bulkformadd');
    }

    

    public function bulksend(Request $request)
    {
        dd($request);
        $request->validate([
            'clinics' => 'required',
            'drug_name' => 'required|array',
            'drug_name.*' => 'required|string',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'item_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $clinics = $request->clinics;
        $procurer = auth()->user()->name;


        $bulkDetails = [];
        $totalDrugs = count($request->drug_name);

        foreach ($request->drug_name as $index => $drugName) {
            $drugQuantity = $request->quantity[$index];
            $stockItem = StockItem::where('item_number', $drugName)->first();
            if ($drugQuantity > $stockItem->item_quantity) {
                // Handle insufficient stock
                return redirect()->back()->with('error', "Not enough stock for '{$drugName}'. Current stock: {$stockItem->item_quantity}.");
            }
            $newStock = $stockItem->item_quantity - $drugQuantity;
            $stockItem->update(['item_quantity' => $newStock]);

            // Collect details for the bulk journal and pending stock entries
            $bulkDetails[] = [
                'item_name' => $stockItem->item_name,
                'item_quantity' => $drugQuantity,
                'item_number' => $drugName 
            ];
        }
        $imagename = now()->format('Y-m-d_H-i-s') . '.' . $request->item_image->extension();
        $request->item_image->move(public_path('images'), $imagename);
        mainstock_journal::create([
            'total_items' => $totalDrugs,
            'procurer' => $procurer,
            'clinics' => $clinics,
            'p_o_d' => 'images/' . $imagename,
            'details' => json_encode($bulkDetails), // Store bulk details as JSON

        ]) ;

        // Save pending stock entry
        pending_stocks::create([
            'status' => 'Pending',
            'procurer' => $procurer,
            'clinics' => $clinics,
            'total_items' => $totalDrugs,
            'details' => json_encode($bulkDetails), // Store bulk details as JSON
        ]);

        return redirect()->back()->with('success', 'Bulk distribution completed successfully!');
    }

    public function bulkadd(Request $request)
    {
        $request->validate([
            'drug_name' => 'required|array',
            'drug_name.*' => 'required|string',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'item_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $procurer = auth()->user()->name;
        $bulkDetails = [];
        $totalDrugs = count($request->drug_name);
        
        foreach ($request->drug_name as $index => $drugName) {
            $drugQuantity = $request->quantity[$index];
            $stockItem = StockItem::where('item_number', $drugName)->first();
            $newStock = $stockItem->item_quantity + $drugQuantity;
            $stockItem->update(['item_quantity' => $newStock]);

            // Collect details for the bulk journal and pending stock entries
            $bulkDetails[] = [
                'item_name' => $stockItem->item_name,
                'item_quantity' => $drugQuantity,
                'item_number' => $stockItem->item_number,
            ];
        }
        $imagename = now()->format('Y-m-d_H-i-s') . '.' . $request->item_image->extension();
        $request->item_image->move(public_path('images'), $imagename);
        mainstock_journal::create([
            'total_items' => $totalDrugs,
            'procurer' => $procurer,
            'p_o_d' => 'images/' . $imagename,
            'details' => json_encode($bulkDetails), // Store bulk details as JSON

        ]) ;

        return redirect()->back()->with('success', 'Bulk Addition completed successfully!');
    }
}
