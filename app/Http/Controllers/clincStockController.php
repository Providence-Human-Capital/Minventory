<?php

namespace App\Http\Controllers;

use App\Models\mainstock_journal;
use App\Models\pending_stocks;
use App\Models\stock_request;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class clincStockController extends Controller
{
    public function showpending()
    {

        $pending = DB::table('pending_stocks')->where('clinic', 'LIKE', Auth::user()->clinic)
            ->where('status', 'LIKE', 'Pending')->get();

        return view('clinicstock.pendingstock', ['pstocks' => $pending]);
    }

    public function receivedstock()
    {
        $Received = DB::table('pending_stocks')->where('clinic', 'LIKE', Auth::user()->clinic)
            ->where('status', 'LIKE', 'Received')->get();

        return view('clinicstock.receivedstock', ['rstocks' => $Received]);
    }
    public function searchpstock(Request $request)
    {

        $searchTerm = $request->input('ssearch'); // Get the search query from the request
        $pending = [];
        $pending =  DB::table('pending_stocks')->where('clinic', 'LIKE', Auth::user()->clinic)->where('item_name', 'LIKE', "%{$searchTerm}%")->get();
        if ($pending->isEmpty()) {
            return redirect()->route('pendingstock')->with('error', 'Product could not be found');
        } else {
            return view('clinicstock.pendingstocksearch', ['search' => $pending]);
        }
    }


    public function changestatus(Request $request)
    {
        $id = $request->id;
        $update['status'] = 'Received';
        $update['reciever'] = Auth::user()->name;
        $approved = pending_stocks::where('id', 'like', $id)->get()->first();
        $journal = mainstock_journal::where('item_number', 'like', $approved->item_number)
            ->where('clinics', 'like', $approved->clinic)
            ->where('created_at', 'like', $approved->created_at)
            ->where('item_quantity', 'like', $approved->item_quantity)
            ->where('item_number', 'like', $approved->item_number)
            ->update([
                'recieved_by' => auth()->user()->name,
                // Add any other fields as necessary
            ]);;
        $approve = pending_stocks::find($id);
        $approve->update($update);


        $addstock = $approve->item_quantity;
        switch ($approved->clinic) {
            case '81 Baines Avenue(Harare)':
                $currenstock = DB::table('avenue81_stocks')->where('item_number', 'like', $approve->item_number)->get()->first()->item_quantity;;
                $newstock = $addstock + $currenstock;
                DB::table('avenue81_stocks')
                    ->where('item_number', 'like', $approve->item_number)
                    ->update(['avenue81_stocks.item_quantity' => $newstock]);
                break;
            case '52 Baines Avenue(Harare)':
                $currenstock = DB::table('avenue52_stocks')->where('item_number', 'like', $approve->item_number)->get()->first()->item_quantity;;
                $newstock = $addstock + $currenstock;
                DB::table('avenue52_stocks')
                    ->where('item_number', 'like', $approve->item_number)
                    ->update(['avenue52_stocks.item_quantity' => $newstock]);
                break;
            case '64 Cork road Avondale(Harare)':
                $currenstock = DB::table('avondale64_stocks')->where('item_number', 'like', $approve->item_number)->get()->first()->item_quantity;;
                $newstock = $addstock + $currenstock;
                DB::table('avondale64_stocks')
                    ->where('item_number', 'like', $approve->item_number)
                    ->update(['avondale64_stocks.item_quantity' => $newstock]);
                break;
            case '40 Josiah Chinamano Avenue(Harare)':
                $currenstock = DB::table('chimano40_stocks')->where('item_number', 'like', $approve->item_number)->get()->first()->item_quantity;;
                $newstock = $addstock + $currenstock;
                DB::table('chimano40_stocks')
                    ->where('item_number', 'like', $approve->item_number)
                    ->update(['chimano40_stocks.item_quantity' => $newstock]);
                break;
            case 'Epworth Clinic(Harare)':
                $currenstock = DB::table('epworth_stocks')->where('item_number', 'like', $approve->item_number)->get()->first()->item_quantity;;
                $newstock = $addstock + $currenstock;
                DB::table('epworth_stocks')
                    ->where('item_number', 'like', $approve->item_number)
                    ->update(['epworth_stocks.item_quantity' => $newstock]);
                break;
            case 'Fort Street and 9th Avenue(Bulawayo)':
                $currenstock = DB::table('fortstreet_stocks')->where('item_number', 'like', $approve->item_number)->get()->first()->item_quantity;;
                $newstock = $addstock + $currenstock;
                DB::table('fortstreet_stocks')
                    ->where('item_number', 'like', $approve->item_number)
                    ->update(['fortstreet_stocks.item_quantity' => $newstock]);
                break;
            case 'Royal Arcade Complex(Bulawayo)':
                $currenstock = DB::table('royalarcade_stocks')->where('item_number', 'like', $approve->item_number)->get()->first()->item_quantity;;
                $newstock = $addstock + $currenstock;
                DB::table('royalarcade_stocks')
                    ->where('item_number', 'like', $approve->item_number)
                    ->update(['royalarcade_stocks.item_quantity' => $newstock]);
                break;
            case '39 6th street(GWERU)':
                $currenstock = DB::table('street6gweru_stocks')->where('item_number', 'like', $approve->item_number)->get()->first()->item_quantity;;
                $newstock = $addstock + $currenstock;
                DB::table('street6gweru_stocks')
                    ->where('item_number', 'like', $approve->item_number)
                    ->update(['street6gweru_stocks.item_quantity' => $newstock]);
                break;
            case '126 Herbert Chitepo Street(Mutare)':
                $currenstock = DB::table('chitepo126mutare_stock')->where('item_number', 'like', $approve->item_number)->get()->first()->item_quantity;;
                $newstock = $addstock + $currenstock;
                DB::table('chitepo126mutare_stock')
                    ->where('item_number', 'like', $approve->item_number)
                    ->update(['chitepo126mutare_stock.item_quantity' => $newstock]);
                break;
            case '13 Shuvai Mahofa street(Masvingo)':
                $currenstock = DB::table('shuvaimahofa13masvingo_stocks')->where('item_number', 'like', $approve->item_number)->get()->first()->item_quantity;;
                $newstock = $addstock + $currenstock;
                DB::table('shuvaimahofa13masvingo_stocks')
                    ->where('item_number', 'like', $approve->item_number)
                    ->update(['shuvaimahofa13masvingo_stocks.item_quantity' => $newstock]);
                break;
        }

        return redirect()->route('pendingstock')->with('success', 'Stock Received.');
    }


    public function searchclinicstock(Request $request)
    {

        $searchTerm = $request->input('isearch'); // Get the search query from the request
        $pending = [];
        switch (auth()->user()->clinic) {
            case '81 Baines Avenue(Harare)':
                $pending = DB::table('avenue81_stocks')->where('item_name', 'LIKE', "%{$searchTerm}%")->get();

                break;
            case '52 Baines Avenue(Harare)':
                $pending = DB::table('avenue52_stocks')->where('item_name', 'LIKE', "%{$searchTerm}%")->get();

                break;
            case '64 Cork road Avondale(Harare)':
                $pending = DB::table('avondale64_stocks')->where('item_name', 'LIKE', "%{$searchTerm}%")->get();

                break;
            case '40 Josiah Chinamano Avenue(Harare)':
                $pending = DB::table('chimano40_stocks')->where('item_name', 'LIKE', "%{$searchTerm}%")->get();

                break;
            case 'Epworth Clinic(Harare)':
                $pending = DB::table('epworth_stocks')->where('item_name', 'LIKE', "%{$searchTerm}%")->get();

                break;
            case 'Fort Street and 9th Avenue(Bulawayo)':
                $pending = DB::table('fortstreet_stocks')->where('item_name', 'LIKE', "%{$searchTerm}%")->get();

                break;
            case 'Royal Arcade Complex(Bulawayo)':
                $pending = DB::table('royalarcade_stocks')->where('item_name', 'LIKE', "%{$searchTerm}%")->get();

                break;
            case '39 6th street(GWERU)':
                $pending = DB::table('street6gweru_stocks')->where('item_name', 'LIKE', "%{$searchTerm}%")->get();

                break;
            case '126 Herbert Chitepo Street(Mutare)':
                $pending = DB::table('chitepo126mutare_stock')->where('item_name', 'LIKE', "%{$searchTerm}%")->get();

                break;
            case '13 Shuvai Mahofa street(Masvingo)':
                $pending = DB::table('shuvaimahofa13masvingo_stocks')->where('item_name', 'LIKE', "%{$searchTerm}%")->get();

                break;
        }
        if ($pending->isEmpty()) {
            return redirect()->route('getclinicstock')->with('error', 'Product could not be found');
        } else {

            return view('clinicstock.clinicstocksearch', ['clinicstock' => $pending]);
        }
    }
    public function getclinicstock()
    {

        switch (auth()->user()->clinic) {
            case '81 Baines Avenue(Harare)':
                $clinicstock = DB::table('avenue81_stocks')->get();
                return view('clinicstock.clinicstock', ['clinicstock' => $clinicstock]);
                break;
            case '52 Baines Avenue(Harare)':
                $clinicstock = DB::table('avenue52_stocks')->get();
                return view('clinicstock.clinicstock', ['clinicstock' => $clinicstock]);
                break;
            case '64 Cork road Avondale(Harare)':
                $clinicstock = DB::table('avondale64_stocks')->get();
                return view('clinicstock.clinicstock', ['clinicstock' => $clinicstock]);
                break;
            case '40 Josiah Chinamano Avenue(Harare)':
                $clinicstock = DB::table('chimano40_stocks')->get();
                return view('clinicstock.clinicstock', ['clinicstock' => $clinicstock]);
                break;
            case 'Epworth Clinic(Harare)':
                $clinicstock = DB::table('epworth_stocks')->get();
                return view('clinicstock.clinicstock', ['clinicstock' => $clinicstock]);
                break;
            case 'Fort Street and 9th Avenue(Bulawayo)':
                $clinicstock = DB::table('fortstreet_stocks')->get();
                return view('clinicstock.clinicstock', ['clinicstock' => $clinicstock]);
                break;
            case 'Royal Arcade Complex(Bulawayo)':
                $clinicstock = DB::table('royalarcade_stocks')->get();
                return view('clinicstock.clinicstock', ['clinicstock' => $clinicstock]);
                break;
            case '39 6th street(GWERU)':
                $clinicstock = DB::table('street6gweru_stocks')->get();
                return view('clinicstock.clinicstock', ['clinicstock' => $clinicstock]);
                break;
            case '126 Herbert Chitepo Street(Mutare)':
                $clinicstock = DB::table('chitepo126mutare_stock')->get();
                return view('clinicstock.clinicstock', ['clinicstock' => $clinicstock]);
                break;
            case '13 Shuvai Mahofa street(Masvingo)':
                $clinicstock = DB::table('shuvaimahofa13masvingo_stocks')->get();
                return view('clinicstock.clinicstock', ['clinicstock' => $clinicstock]);
                break;
        }
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
}
