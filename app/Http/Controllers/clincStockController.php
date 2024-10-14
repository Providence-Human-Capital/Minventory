<?php

namespace App\Http\Controllers;

use App\Models\avenue81_stock;
use App\Models\clinic_stock;
use App\Models\mainstock_journal;
use App\Models\pending_stocks;
use App\Models\pendingstock;
use App\Models\stock_request;
use App\Models\StockItem;
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
        $Received = DB::table('pending_stocks')->where('clinic', 'LIKE', Auth::user()->clinic)
            ->where('status', 'LIKE', 'Received')->get();

        return view('clinicstock.pendingstock', ['pstocks' => $pending, 'rstocks' => $Received]);
    }

    public function changestatus(Request $request, avenue81_stock $avenue81_stock)
    {
        $id = $request->id;
        $update['status'] = 'Received';
        $update['reciever'] = Auth::user()->name;
        $approved = pending_stocks::where('id','like',$id)->get()->first();
        $journal = mainstock_journal::where('item_number','like',$approved->item_number)
                        ->where('clinics','like',$approved->clinic)
                        ->where('created_at','like',$approved->created_at)
                        ->where('item_quantity','like',$approved->item_quantity)
                        ->where('item_number','like',$approved->item_number)
                        ->update([
                            'recieved_by' => auth()->user()->name,
                            // Add any other fields as necessary
                        ]);;
        $approve = pending_stocks::find($id);
        $approve->update($update);




        $stockitemz=avenue81_stock::where('item_number', 'like', $approve->item_number)->get()->first();
        $currenstock=$stockitemz->item_quantity;
        $addstock = $approve->item_quantity;
        $newstock = $addstock + $currenstock;

        DB::table('avenue81_stocks')
            ->where('item_number', 'like', $approve->item_number)
            ->update(['avenue81_stocks.item_quantity' => $newstock]);
        return redirect()->route('pendingstock')->with('success','Stock Received.');
    }

    public function avenue81()
    {
        
        $avenue81 = DB::table('avenue81_stocks')->get();
        return view('clinicstock.clinicstock', ['avenue81' => $avenue81]);
    }


    public function requeststock()
    {
        $drugs = DB::table('stock_items')->select('item_number', 'item_name')->get();
        return view('clinicstock.requeststock',['drugs'=>$drugs]);
    }



    public function saverequest(Request $request)
    {
        $things['item_name']=$request->item_name;
        $things['item_quantity'] =$request->item_quantity;
        $things['item_number']=$request->item_number;
        $things['clinic']=auth()->user()->clinic;
        $things['requester']=auth()->user()->name;
        $things['status']="Pending";
        $things['date_requested']= Carbon::now()->toDatetimeString();
  
        stock_request::create($things);
        $things['approver']=
        $things['date_approved']=
       


        $drugs = DB::table('stock_items')->select('item_number', 'item_name')->get();      
        return view('clinicstock.requeststock',['drugs'=>$drugs]);
    }
}
