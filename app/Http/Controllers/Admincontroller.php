<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\dispense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;

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
            ->select(
                DB::raw('DATE_FORMAT(updated_at, "%M-%y") as date'),
                DB::raw('SUM(item_quantity) as monthsum'),
                'item_name',
                'item_number'
            )
            ->where('clinics', 'like', $clinic)
            ->where('status', 'like', 'Received')
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->groupBy(DB::raw('MONTH(updated_at)'), 'item_name')
            ->orderBy('updated_at', 'asc')
            ->get();

        $labels = [];
        $values = [];
        $html = '';
        // Generate table name dynamically from the selected clinic
        $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic);
        $tableName = strtolower($tableName) . '_stocks';
        foreach ($data as $item) {
            $itemQuantity = DB::table($tableName)->where('item_number', $item->item_number)->value('item_quantity');

            $usage = DB::table('dispenses')
                ->select(DB::raw('SUM(damount) as monthsum'))
                ->where('drug', $item->item_name)
                ->whereYear('dispense_time', $year) // Assuming `dispense_date` is the column for the date
                ->whereMonth('dispense_time', $month)
                ->value('monthsum');
            $fontColor = ($itemQuantity < 100) ? 'red' : 'black';
            // Append a new row to the HTML table with the data
            $html .= "<tr><td>{$item->item_name}</td><td>{$item->monthsum}</td><td style='color: {$fontColor};'>{$itemQuantity}</td><td>{$usage}</td></tr>";

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


    public function allclinicstocksbatch()
    {
        return view('admin.allclinicsstocksbatch');
    }

    public function showclinicchartbatch(Request $request)
    {
        $clinic = $request->clinics;
        $month = $request->month;
        $year = $request->year;

        // Fetch the data from the 'pending_stocks'table
        $data = DB::table("pending_stocks")
            ->select(
                DB::raw('DATE_FORMAT(updated_at, "%M-%y") as date'),
                DB::raw('SUM(item_quantity) as monthsum'),
                'item_name',
                'item_number',
                'details'
            )
            ->where('clinics', 'like', $clinic)
            ->where('status', 'like', 'Received')
            ->whereYear('updated_at', $year)
            ->whereMonth('updated_at', $month)
            ->groupBy(DB::raw('MONTH(updated_at)'), 'item_name')
            ->orderBy('updated_at', 'asc')
            ->get();

        $labels = [];
        $values = [];
        $html = '';
        // Initialize an empty array for the drug summary
        $drugSummary = [];

        // Loop through the records and process each item
        foreach ($data as $stock) {
            // Decode the details JSON field to extract drug details
            $details = json_decode($stock->details);

            // Check if details are not empty
            if ($details) {
                // Loop through each drug detail
                foreach ($details as $detail) {
                    $drugName = $detail->item_name;
                    $drugNumber = $detail->item_number;
                    $drugQuantity = $detail->item_quantity;

                    // If the drug already exists in the summary, add the quantity
                    if (isset($drugSummary[$drugNumber])) {
                        $drugSummary[$drugNumber]['quantity'] += $drugQuantity;
                    } else {
                        // If not, initialize the quantity for the drug
                        $drugSummary[$drugNumber] = [
                            'name' => $drugName,  // Store the drug name
                            'quantity' => $drugQuantity  // Store the quantity
                        ];
                    }
                }
            }
        }
            // Process each drug summary to fetch current stock and usage
        $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic); // Clean clinic name
        $tableName = strtolower($tableName) . '_stocks';  // Use clinic name as the table name
        foreach ($drugSummary as $drug => $itemData) {
            $drugName = $itemData['name'];
            $itemQuantity = $itemData['quantity'];

            // Fetch the stock for the drug
            $stock_item = DB::table($tableName)->where('item_number', $drug)->first();


            if ($stock_item) {
                $currentStockQuantity = $stock_item->item_quantity;

                // Get the usage for the drug
                $usage = DB::table('dispenses')
                    ->select(DB::raw('SUM(damount) as monthsum'))
                    ->where('drug_number', $drug)
                    ->whereYear('dispense_time', $year) // Assuming `dispense_date` is the column for the date
                    ->whereMonth('dispense_time', $month)
                    ->value('monthsum') ?? 0;
                // Add the row to the HTML table
                $fontColor = ($currentStockQuantity < 100) ? 'red' : 'black';
                $html .= "<tr><td>{$drugName}</td><td>{$itemQuantity}</td><td style='color: {$fontColor};'>{$currentStockQuantity}</td><td>{$usage}</td></tr>";

                // Populate chart data (labels and values)
                $labels[] = $drugName;
                $values[] = $itemQuantity;
            }
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



    public function getcreateclinicform()
    {
        return view('admin.createclinic');
    }

    public function createclinic(Request $request)
    {

        $request->validate([
            'clinic_name' => 'required',
            'csv_file' => 'required|mimes:csv,txt|max:10240',
        ]);

        $newclinic = [
            'clinic_name' => $request->clinic_name
        ];
        Clinic::create($newclinic);

        $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $request->clinic_name);
        $tableName = strtolower($tableName) . '_stocks';

        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('item_name', 255)->nullable();
                $table->string('item_quantity', 255)->nullable();
                $table->string('item_number', 10)->nullable();
                $table->index('item_number');
            });
        } else {
            return redirect()->route('getcreateclinicform')->with('error', 'Clinic already exists');
        }
        return redirect()->route('getcreateclinicform')->with('success', 'Clinic created and CSV imported successfully!');
    }
}
