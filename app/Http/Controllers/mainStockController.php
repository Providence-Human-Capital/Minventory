<?php

namespace App\Http\Controllers;

use App\Models\mainstock_journal;
use App\Models\pending_stock;
use App\Models\pending_stocks;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    public function updatemain(Request $request,StockItem $stockItem)
    {
        $request->validate([
            'item_name'=>'required',
            'item_quantity'=>'required',
            'item_number'=>'required',
            'price'=>'required'

        ]);     
        $items['item_name']=$request->item_name;
        $items['item_number']=$request->item_number;
        $items['price']=$request->price;
        $items['expiry_date']=date('Y/m/d',strtotime($request->expiry_date));
        $search=$request->item_number;
        $currenstock =StockItem::where('item_number','like',$search)->get()->first()->item_quantity;
        $addstock= $request->item_quantity;
        $newstock= $currenstock + $addstock;
        $items['item_quantity']=$newstock;  
        $stockItem->update($items);
        $journal['item_name']=$request->item_name;
        $journal['item_quantity']=$request->item_quantity;
        $journal['item_number']=$request->item_number;
        $journal['price']=$request->price;
        $journal['procurer']=auth()->user()->name;
        $journal['to_from_mainstock']="TO";
        $journal['expiry_date']=date('Y/m/d',strtotime($request->expiry_date));
        mainstock_journal::create($journal);
        return redirect()->route('mainstock')->with('success','Added to Main Stock.');
    }


    public function distributemain(Request $request,StockItem $stockItem)
    {
        $request->validate([
            'item_name'=>'required',
            'item_quantity'=>'required',
            'item_number'=>'required',

        ]);     
        $items['item_name']=$request->item_name;
        $items['item_number']=$request->item_number;
        $items['price']=$request->price;
        $items['expiry_date']=date('Y/m/d',strtotime($request->expiry_date));
        $search=$request->item_number;
        $currenstock =StockItem::where('item_number','like',$search)->get()->first()->item_quantity;
        
        $disributestock= $request->item_quantity;
        $newstock= $currenstock - $disributestock;
        $items['item_quantity']=$newstock;  
        $stockItem->update($items);
        $journal['item_name']=$request->item_name;
        $journal['item_quantity']=$request->item_quantity;
        $journal['item_number']=$request->item_number;
        $journal['price']=$request->price;
        $journal['procurer']=auth()->user()->name;
        $journal['clinics']=$request->clinics;
        $journal['expiry_date']=date('Y/m/d',strtotime($request->expiry_date));
        mainstock_journal::create($journal);
        $pending['item_name']=$request->item_name;
        $pending['item_quantity']=$request->item_quantity;
        $pending['item_number']=$request->item_number;
        $pending['procurer']=auth()->user()->name;
        $pending['status']='Pending';
        $pending['clinic']=$request->clinics;
        pending_stocks::create($pending);
        return redirect()->route('mainstock')->with('success','Send  to clinic.');
    }
}


