<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\dispense;
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

    public function resetpassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8', // You can adjust the validation rules as needed
        ]);

        $userId = $request->id; // Get the user ID
        $password = $request->password; // Get the new password

        // Find the user and update the password
        User::find($userId)->update(['password' => bcrypt($password)]);

        return redirect()->route('getuseroptions')->with('success', 'Password Reset');
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

    // Fetch the data from the 'pending_stocks'table
    $data = DB::table("pending_stocks")
        ->select(DB::raw('DATE_FORMAT(updated_at, "%M-%y") as date'), 
                 DB::raw('SUM(item_quantity) as monthsum'), 
                 'item_name')
        ->where('clinics', 'like', $clinic)  
        ->where('status', 'like', 'Received') 
        ->whereYear('updated_at', $year) 
        ->whereMonth('updated_at', $month)
        ->groupBy(DB::raw('MONTH(updated_at)'), 'item_name') 
        ->orderBy('updated_at', 'asc')
        ->get();

    // Initialize arrays to store chart data and the HTML table rows
    $labels = [];
    $values = [];
    $html = '';

    
    // Generate table name dynamically from the selected clinic
    $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic); // Clean clinic name
    $tableName = strtolower($tableName) . '_stocks';  // Add suffix for the stock table
    // Loop through the fetched data and build the HTML table rows
    foreach ($data as $item) {
        // For each item, find the current quantity in the clinic's specific table
        $itemQuantity = DB::table($tableName)->where('item_name', $item->item_name)->value('item_quantity');
       
        $usage = DB::table('dispenses')
        ->select(DB::raw('SUM(damount) as monthsum'))
        ->where('drug', $item->item_name)
        ->whereYear('dispense_time', $year) // Assuming `dispense_date` is the column for the date
        ->whereMonth('dispense_time', $month)
        ->value('monthsum');
        // Append a new row to the HTML table with the data
        $html .= "<tr><td>{$item->item_name}</td><td>{$item->monthsum}</td><td>{$itemQuantity}</td><td>{$usage}</td></tr>";

        // Populate chart data (labels and values)
        $labels[] = $item->item_name;
        $values[] = $item->monthsum;
    }
    // Return the HTML table and chart data as a JSON response
    return response()->json([
        'html' => $html,  
        'chartData' => [  
            'labels' => $labels, 
            'values' => $values, 
        ]
    ]);
}

public function getcreateclinicform(){
    return view('admin.createclinic');
}

public function createclinic(Request $request)
{
    $request->validate([
        'clinic_name'=>'required'
    ]);
    $newclinic['clinic_name']= $request->clinic_name;
    Clinic::create($newclinic);

    return view('admin.createclinic');
}
}
