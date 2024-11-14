<?php

namespace App\Http\Controllers;

use App\Models\mainstock_journal;
use App\Models\pending_stocks;
use App\Models\stock_request;
use App\Models\Transferrecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class clincStockController extends Controller
{
    public function showpending()
    {

        $pending = DB::table('pending_stocks')->where('clinics', 'LIKE', Auth::user()->clinic)
            ->where('status', 'LIKE', 'Pending')->get();

        return view('clinicstock.pendingstock', ['pstocks' => $pending]);
    }

    public function receivedstock()
    {
        $Received = DB::table('pending_stocks')->where('clinics', 'LIKE', Auth::user()->clinic)
            ->where('status', 'LIKE', 'Received')->get();

        return view('clinicstock.receivedstock', ['rstocks' => $Received]);
    }
    public function searchpstock(Request $request)
    {

        $searchTerm = $request->input('ssearch'); // Get the search query from the request
        $pending = [];
        $pending =  DB::table('pending_stocks')->where('clinics', 'LIKE', Auth::user()->clinic)->where('item_name', 'LIKE', "%{$searchTerm}%")->get();
        if ($pending->isEmpty()) {
            return redirect()->route('pendingstock')->with('error', 'Product could not be found');
        } else {
            return view('clinicstock.pendingstocksearch', ['search' => $pending]);
        }
    }


    public function changestatus(Request $request)
    {
        $request->validate([
            'item_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $id = $request->id;
        $imagename = now()->format('Y-m-d_H-i-s') . '.' . $request->item_image->extension();
        $request->item_image->move(public_path('images'), $imagename);
        $update['status'] = 'Received';
        $update['reciever'] = Auth::user()->name;
        $approved = pending_stocks::where('id', 'like', $id)->get()->first();
        $update['p_o_r'] = 'images/' . $imagename;
        mainstock_journal::where('item_number', 'like', $approved->item_number)
            ->where('clinics', 'like', $approved->clinics)
            ->where('created_at', 'like', $approved->created_at)
            ->where('item_quantity', 'like', $approved->item_quantity)
            ->where('item_number', 'like', $approved->item_number)
            ->update([
                'recieved_by' => auth()->user()->name,
                'p_o_r' => 'images/' . $imagename,
                // Add any other fields as necessary
            ]);;
        $approve = pending_stocks::find($id);
        $match = Transferrecord::where('drug_name', $approve->item_name)
            ->where('sender', $approve->procurer)
            ->where('drug_amount', $approve->item_quantity)
            ->where('clinic_to', $approve->clinics)
            ->first();
        if ($match) {
            $match->receiver = Auth::user()->name;
            $match->status = 'Received';
            $match->save();
        }
        $approve->update($update);
        $addstock = $approve->item_quantity;
        $clinic = $approved->clinics;
        $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic); // Clean clinic name
        $tableName = strtolower($tableName) . '_stocks';  // Add suffix for the stock table
        $currenstock = DB::table($tableName)->where('item_number', 'like', $approve->item_number)->get()->first()->item_quantity;
        $newstock = $addstock + $currenstock;
        DB::table($tableName)
            ->where('item_number', 'like', $approve->item_number)
            ->update(['item_quantity' => $newstock]);

        return redirect()->route('pendingstock')->with('success', 'Stock Received.');
    }


    public function searchclinicstock(Request $request)
    {

        $searchTerm = $request->input('isearch'); // Get the search query from the request
        $pending = [];
        $clinic = auth()->user()->clinic;
        $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic); // Clean clinic name
        $tableName = strtolower($tableName) . '_stocks';  // Add suffix for the stock table
        $pending = DB::table($tableName)->where('item_name', 'LIKE', "%{$searchTerm}%")->get();

        if ($pending->isEmpty()) {
            return redirect()->route('getclinicstock')->with('error', 'Product could not be found');
        } else {

            return view('clinicstock.clinicstocksearch', ['clinicstock' => $pending]);
        }
    }
    public function getclinicstock()
    {
        $clinic = auth()->user()->clinic;
        $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic); // Clean clinic name
        $tableName = strtolower($tableName) . '_stocks';  // Add suffix for the stock table
        $clinicstock = DB::table($tableName)->orderBy('item_name', 'asc')->get();
        return view('clinicstock.clinicstock', ['clinicstock' => $clinicstock]);
    }

    public function requeststock()
    {
        $drugs = DB::table('stock_items')->select('item_number', 'item_name')->get();
        return view('clinicstock.requeststock', ['drugs' => $drugs]);
    }

    public function searchrstock(Request $request)
    {
        $drugs = DB::table('stock_items')->select('item_number', 'item_name')->get();
        $request->validate([
            'item_name' => 'nullable|string|max:255',
            'item_number' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'requester' => 'nullable|string|max:255',
            'transaction_date_from' => 'nullable|date',
            'transaction_date_to' => 'nullable|date',
        ]);

        // Start the query
        $query = stock_request::query(); // Adjust the model name

        // Apply filters based on input
        if ($request->filled('item_name')) {
            $query->where('item_name', 'like', '%' . $request->item_name . '%');
        }

        if ($request->filled('item_number')) {
            $query->where('item_number', 'like', '%' . $request->item_number . '%');
        }

        if ($request->filled('requester')) {
            $query->where('requester', 'like', '%' . $request->requester . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', 'like', '%' . $request->status . '%');
        }

        if ($request->filled('transaction_date_from')) {
            $query->where('date_requested', '>=', $request->transaction_date_from);
        }

        if ($request->filled('transaction_date_to')) {
            $query->where('date_requested', '<=', $request->transaction_date_to);
        }

        // Execute the query and get the results
        $results = $query->get();

        // Return the results to a view or as a JSON response
        return view('clinicstock.receivedstocksearch', compact('results', 'drugs')); // Adjust view name as needed
    }

    public function saverequest(Request $request)
    {
        $things['item_name'] = $request->item_name;
        $things['item_quantity'] = $request->item_quantity;
        $things['item_number'] = $request->item_number;
        $things['clinic'] = auth()->user()->clinic;
        $things['requester'] = auth()->user()->name;
        $things['status'] = "Pending";
        $things['date_requested'] = Carbon::now()->toDatetimeString();

        stock_request::create($things);
        $things['approver'] =
            $things['date_approved'] =



            $drugs = DB::table('stock_items')->select('item_number', 'item_name')->get();
        return redirect()->route('requeststock', ['drugs' => $drugs])->with('success', 'Stock Requested.');
    }

    public function stocktransfer()
    {
        $records = Transferrecord::where('clinic_to', auth()->user()->clinic)
            ->orwhere('clinic_from', auth()->user()->clinic)->get();

        $drugs = DB::table('stock_items')->select('item_number', 'item_name')->get();

        return view('clinicstock.transfers', compact('records', 'drugs'));
    }

    public function savetransfer(Request $request)

    {
        $transfer['drug_name'] = $request->drug_name;
        $transfer['clinic_from'] = auth()->user()->clinic;
        $transfer['drug_amount'] = $request->drug_amount;
        $transfer['clinic_to'] = $request->clinic_to;
        $transfer['sender'] = auth()->user()->name;
        $transfer['status'] = 'Pending';
        Transferrecord::create($transfer);
        $pending['item_name'] = $request->drug_name;
        $pending['item_quantity'] = $request->drug_amount;
        $pending['item_number'] = $request->item_number;
        $pending['procurer'] = auth()->user()->name;
        $pending['status'] = 'Pending';
        $pending['clinics'] = $request->clinic_to;
        pending_stocks::create($pending);
        $removestock = $request->drug_amount;

        $clinic = auth()->user()->clinic;
        $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic); // Clean clinic name
        $tableName = strtolower($tableName) . '_stocks';  // Add suffix for the stock table
        $currenstock = DB::table($tableName)->where('item_number', 'like', $request->item_number)->get()->first()->item_quantity;
        $newstock = $currenstock - $removestock;
        DB::table($tableName)
            ->where('item_number', 'like', $request->item_number)
            ->update(['item_quantity' => $newstock]);

        redirect()->route('stocktransfer')->with('success', 'Transfer sent');
    }



    public function searchtransfer(Request $request)
    {

        $drugs = DB::table('stock_items')->select('item_number', 'item_name')->get();
        $query = Transferrecord::query();  // Start with the base query for DrugTransfer

        // Apply filters based on the input parameters
        if ($request->filled('drug_name')) {
            $query->where('drug_name', 'like', '%' . $request->drug_name . '%');
        }
        if ($request->filled('clinic_from')) {
            $query->where('clinic_from', 'like', '%' . $request->clinic_from . '%');
        }
        if ($request->filled('sender')) {
            $query->where('sender', 'like', '%' . $request->sender . '%');
        }
        if ($request->filled('drug_amount')) {
            $query->where('drug_amount', '=', $request->drug_amount);
        }
        if ($request->filled('clinic_to')) {
            $query->where('clinic_to', 'like', '%' . $request->clinic_to . '%');
        }
        if ($request->filled('receiver')) {
            $query->where('receiver', 'like', '%' . $request->receiver . '%');
        }
        if ($request->filled('send_at_start') && $request->filled('send_at_end')) {
            // Ensure the date format matches the database's format
            $query->whereBetween('created_at', [
                $request->send_at_start . ' 00:00:00',
                $request->send_at_end . ' 23:59:59'
            ]);
        }

        // Date range for 'updated_at' (Received At)
        if ($request->filled('received_at_start') && $request->filled('received_at_end')) {
            $query->whereBetween('updated_at', [
                $request->received_at_start . ' 00:00:00',
                $request->received_at_end . ' 23:59:59'
            ]);
        }
        // Get the filtered records
        $records = $query->get();
        return view('clinicstock.transfersearch', compact('records', 'drugs'));
    }
}
