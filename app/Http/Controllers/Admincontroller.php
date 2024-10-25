<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Admincontroller extends Controller
{
    public function getuseroptions()
    {
        $user = User::get();

        return view('admin.alluser', ['user' => $user]);
    }


    /**
     * Delete the user's account.
     */
    public function deleteuser(Request $request)
    {
        User::find($request->userid)
            ->delete();

        return redirect()->route('getuseroptions')->with('error', 'User deleted');
    }

    public function allclinicstocks()
    {
        return view('admin.allclinicsstocks');
    }

    public function showclinicchart(Request $request)
    {
        $clinic = $request->clinics;
        $month = $request->month;
        $year = $request->year;

        $data = DB::table("pending_stocks")

            ->select(DB::raw('DATE_FORMAT(updated_at, "%M-%y") as date'), DB::raw('SUM(item_quantity) as monthsum'), 'item_name')
            ->where('clinic', 'like', $clinic)
            ->where('status', 'like', 'Received')
            ->whereYear('updated_at', $year)  // Filter by year
            ->whereMonth('updated_at', $month) // Filter by month
            ->groupBy(DB::raw('MONTH(updated_at)'), 'item_name')
            ->orderBy('updated_at', 'asc')
            ->get();

            $labels = [];
            $values = [];
            $html = '';
            
            foreach ($data as $item) {
                $labels[] = $item->item_name; // Drug names
                $values[] = $item->monthsum;   // Quantities
                $html .= "<tr><td>{$item->item_name}</td><td>{$item->monthsum}</td></tr>";
            }
            


            return response()->json([
                'html' => $html,
                'chartData' => [
                    'labels' => $labels,
                    'values' => $values,
                ]
            ]);
    }
        
    }
