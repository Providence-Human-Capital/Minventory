<?php

namespace App\Http\Controllers;

use App\Models\avenue81_stock;
use App\Models\clinic_stock;
use App\Models\pending_stocks;
use App\Models\pendingstock;
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
        $recieved = DB::table('pending_stocks')->where('clinic', 'LIKE', Auth::user()->clinic)
            ->where('status', 'LIKE', 'Recieved')->get();

        return view('clinicstock.pendingstock', ['pstocks' => $pending, 'rstocks' => $recieved]);
    }

    public function changestatus(Request $request, avenue81_stock $avenue81_stock)
    {
        $id = $request->id;
        $update['status'] = 'Recieved';
        $update['reciever'] = Auth::user()->name;
        $approve = pending_stocks::find($id);
        $approve->update($update);


        $stockitemz=avenue81_stock::where('item_number', 'like', $approve->item_number)->get()->first();
        $currenstock=$stockitemz->item_quantity;
        $addstock = $approve->item_quantity;
        $newstock = $addstock + $currenstock;

        DB::table('avenue81_stocks')
            ->where('item_number', 'like', $approve->item_number)
            ->update(['avenue81_stocks.item_quantity' => $newstock]);
        return redirect()->route('clinicstock.pendingstock');
    }

    public function avenue81()
    {
        
        $avenue81 = DB::table('avenue81_stocks')->get();
        return view('clinicstock.clinicstock', ['avenue81' => $avenue81]);
    }
}
