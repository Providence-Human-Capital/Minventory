<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class distributeStockController extends Controller
{
    public function show()
    {
        return view('distributeStock.distributeStock');
    }
}
