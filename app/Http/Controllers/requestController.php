<?php

namespace App\Http\Controllers;

use App\Charts\MonthlyDistribution;
use App\Mail\StockDeliveryMail;
use App\Models\avenue81_stock;
use App\Models\pending_stocks;
use App\Models\stock_request;
use App\Models\StockItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\mainstock_journal;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;

class requestController extends Controller
{
    public function showrequests()
    {
        $stock_request = stock_request::where('status', 'LIKE', 'Pending')->get();

        return view('Requests.Requests', ['requests' => $stock_request]);
    }

    public function showarequests()
    {
        $stock_request = stock_request::where('status', 'LIKE', 'Approved')->get();

        return view('Requests.Requestsapproved', ['requests' => $stock_request]);
    }

    public function showdrequests()
    {
        $stock_request = stock_request::where('status', 'LIKE', 'Rejected')->get();

        return view('Requests.Requestsdenied', ['requests' => $stock_request]);
    }

    public function showallrequests()
    {
        $stock_request = stock_request::all();

        return view('Requests.Requestsall', ['requests' => $stock_request]);
    }


    public function viewrequest(Request $request)
    {
        $requested = stock_request::where('id', 'like', $request->id)->get()->first();
        $requestedid = $request->id;
        $requestedname = $requested->item_name;
        $requestednumber = $requested->item_number;
        $requestedclinic = $requested->clinic;

        $maincurrentstock = StockItem::where('item_name', 'like', $requestedname)->get()->first()->item_quantity;
        switch ($requestedclinic) {
            case "81 Baines Avenue(Harare)":
                $currentclinicstock = avenue81_stock::where('item_name', 'like', $requestedname)->get()->first()->item_quantity;
                $sentstock = DB::table("pending_stocks")

                    ->select(DB::raw('DATE_FORMAT(updated_at, "%M-%y") as date'), DB::raw('SUM(item_quantity) as monthsum'))
                    ->where('item_name', 'like', $requestedname)
                    ->where('clinic', 'like', $requestedclinic)
                    ->where('status', 'like', 'Received')
                    ->groupBy(DB::raw('MONTH(updated_at)'))
                    ->orderBy('updated_at', 'asc')
                    ->get();


                $date = [];
                $sent = [];


                foreach ($sentstock as $sentstocks) {
                    $date[] = $sentstocks->date;
                    $sent[] = intval($sentstocks->monthsum);
                }


                $chart = LarapexChart::setType('line')
                    ->setTitle($requestedclinic)
                    ->setLabels($date)
                    ->setDataset([
                        'name' => 'Amount Sent',
                        'data' => $sent
                    ])
                    ->setColors(['#ffc73c']);



                return view('requestchart',  compact('chart', 'currentclinicstock', 'maincurrentstock', 'requestedclinic', 'requestedname', 'requestednumber', 'requestedid'));
        }
    }

    public function approverequest(Request $request, StockItem $stockItem)
    {
        $request->validate([
            'item_name' => 'required',
            'item_quantity' => 'required',
            'item_number' => 'required',

        ]);
        $items['item_number'] = $request->item_number;
        $items['expiry_date'] = date('Y/m/d', strtotime($request->expiry_date));
        $search = $request->item_number;
        $currenstock = StockItem::where('item_number', 'like', $search)->get()->first()->item_quantity;

        $disributestock = $request->item_quantity;
        if ($disributestock > $currenstock) {
            $pending = DB::table('stock_items')->where('item_number', '=', '%' . $request->item_number . '%')->get();
            return redirect()->route('searchmainstock', ['search' => $pending])->with('error', 'Not Enough Stock.');
        } else {
            $newstock = $currenstock - $disributestock;
            $items['item_quantity'] = $newstock;
            DB::table('stock_items')
                ->where('item_number', $request->item_number)
                ->update([
                    'item_quantity' => $newstock,
                    // Add any other fields as necessary
                ]);
            $journal['item_name'] = $request->item_name;
            $journal['item_quantity'] = $request->item_quantity;
            $journal['item_number'] = $request->item_number;
            $journal['price'] = $request->price;
            $journal['procurer'] = auth()->user()->name;
            $journal['clinics'] = $request->clinics;
            $journal['expiry_date'] = date('Y/m/d', strtotime($request->expiry_date));
            mainstock_journal::create($journal);
            $pending['item_name'] = $request->item_name;
            $pending['item_quantity'] = $request->item_quantity;
            $pending['item_number'] = $request->item_number;
            $pending['procurer'] = auth()->user()->name;
            $pending['status'] = 'Pending';
            $pending['clinic'] = $request->clinics;
            pending_stocks::create($pending);
            stock_request::where('id', $request->requestid)
                ->update([
                    'status' => 'Approved',
                    'approver' => auth()->user()->name,
                    'date_approved' => Carbon::now()->toDatetimeString()

                ]);

            $stockRequest = stock_request::where('id', $request->requestid)->first();

            if (!$stockRequest || !$stockRequest->requester) {
                // Handle the case where the requester is not found or doesn't exist
                $data = [
                    'subject' => "Request has been approved",
                    'messages' => "Stock of {$request->item_name}. Amount: {$request->item_quantity} shall be delivered.",


                ];

                Mail::send('delivery-mail', $data, function ($message) use ($data) {
                    $message->to('kingchemz@gmail.com') // Change to the recipient's email address
                        ->subject($data['subject']);
                });
            } else {
                // Proceed to get the requester details
                $requester = $stockRequest->requester;
                $requesterEmail = User::where('name', $requester)->first()->email;

                $data = [
                    'subject' => "Request has been approved",
                    'messages' => "Stock of {$request->item_name}. Amount: {$request->item_quantity} shall be delivered.",
                    'requestedId' => $request->requestedId,
                    'requester' => $requester,
                ];

                Mail::send('stocksend', $data, function ($message) use ($data, $requesterEmail) {
                    $message->to($requesterEmail) // Change to the recipient's email address
                        ->subject($data['subject']);
                });
            }
        }


        return redirect()->route('mainstock')->with('success', 'Send  to clinic.');
    }

    public function searchrequests(Request $request)
    {
        $request->validate([
            'item_name' => 'nullable|string|max:255',
            'item_number' => 'nullable|string|max:255',
            'clinics' => 'nullable|string|max:255',
            'requester' => 'nullable|string|max:255',
            'approver' => 'nullable|string|max:255',
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

        if ($request->filled('clinics')) {
            $query->where('clinic', $request->clinics);
        }

        if ($request->filled('requester')) {
            $query->where('requester', 'like', '%' . $request->requester . '%');
        }

        if ($request->filled('handler')) {
            $query->where('handler', 'like', '%' . $request->handler . '%');
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
        return view('Requests.Requestsearch', compact('results')); // Adjust view name as needed
    }
}
