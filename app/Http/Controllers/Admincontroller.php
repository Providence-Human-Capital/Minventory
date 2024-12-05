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
use PhpParser\Node\Stmt\Return_;

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



    public function allclinicstocksbatch()
    {
        return view('admin.allclinicsstocksbatch');
    }

    public function showclinicchartbatch(Request $request)
    {
        $clinic = $request->clinics;
        $month = $request->month;
        $year = $request->year;
        $mode = $request->mode; // Get the mode (monthly or yearly)

        $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic); // Clean clinic name
        $tableName = strtolower($tableName) . '_stocks';  // Use clinic name as the table name

        // Fetch the data based on the mode (monthly or yearly)
        $pendingStocksQuery = DB::table("pending_stocks")
            ->select('details')
            ->where('clinics', 'like', $clinic)
            ->where('status', 'like', 'Received');

        // Apply filters based on mode
        if ($mode === 'monthly') {
            $pendingStocksQuery->whereYear('updated_at', $year)
                ->whereMonth('updated_at', $month);
        } else if ($mode === 'yearly') {
            $pendingStocksQuery->whereYear('updated_at', $year);
        }

        // Get the pending stocks based on the selected filters
        $pendingStocks = $pendingStocksQuery->get();

        // Data extraction from JSON format
        $extractedStocks = [];
        foreach ($pendingStocks as $stock) {
            $details = json_decode($stock->details, true); // Decode JSON details
            if (is_array($details)) {
                foreach ($details as $drug) {
                    $itemNumber = $drug['item_number'];
                    $itemName = $drug['item_name'];
                    $quantity = $drug['item_quantity'];

                    if (isset($extractedStocks[$itemNumber])) {
                        $extractedStocks[$itemNumber]['quantity'] += $quantity;
                    } else {
                        $extractedStocks[$itemNumber] = [
                            'item_name' => $itemName,
                            'quantity' => $quantity,
                        ];
                    }
                }
            }
        }

        // CURRENT stock in clinic 
        $clinicStocks = DB::table($tableName)
            ->select('item_name', 'item_number', 'item_quantity as stock_quantity')
            ->get();

        // Get usage of drugs
        $dispensesQuery = DB::table('dispenses')
            ->select(
                'drug as item_name',
                'drug_number as item_number',
                DB::raw('SUM(damount) as dispensed_quantity')
            )
            ->where('clinic', 'like', $clinic)
            ->whereYear('dispense_time', $year);

        // Apply month filter if in monthly mode
        if ($mode === 'monthly') {
            $dispensesQuery->whereMonth('dispense_time', $month);
        }

        $dispenses = $dispensesQuery->groupBy('drug_number')->get();

        $combinedData = [];
        // Index pending stocks by item_number
        foreach ($extractedStocks as $itemNumber => $drug) {
            $combinedData[$itemNumber] = [
                'item_name' => $drug['item_name'],
                'sent_quantity' => $drug['quantity'],
                'current_stock' => 0,
                'dispensed_quantity' => 0,
            ];
        }

        // Merge clinic stocks
        foreach ($clinicStocks as $clinicStock) {
            if (!isset($combinedData[$clinicStock->item_number])) {
                $combinedData[$clinicStock->item_number] = [
                    'item_name' => $clinicStock->item_name,
                    'sent_quantity' => 0,
                    'current_stock' => $clinicStock->stock_quantity,
                    'dispensed_quantity' => 0,
                ];
            } else {
                $combinedData[$clinicStock->item_number]['current_stock'] = $clinicStock->stock_quantity;
            }
        }

        // Merge dispense data
        foreach ($dispenses as $dispense) {
            if (!isset($combinedData[$dispense->item_number])) {
                $combinedData[$dispense->item_number] = [
                    'item_name' => $dispense->item_name,
                    'sent_quantity' => 0,
                    'current_stock' => 0,
                    'dispensed_quantity' => $dispense->dispensed_quantity,
                ];
            } else {
                $combinedData[$dispense->item_number]['dispensed_quantity'] = $dispense->dispensed_quantity;
            }
        }

        // Prepare HTML and chart data
        $html = '';
        $labels = [];
        $values = [];
        // Merge dispense data
        foreach ($combinedData as $drug) {
            // Calculate stock percentage
            $sentQuantity = $drug['sent_quantity'];
            $currentStock = $drug['current_stock'];

            if ($sentQuantity > 0 && ($currentStock / $sentQuantity) < 0.05) {
                $fontColor = 'red'; // Less than 5% of sent stock
            } else {
                $fontColor = 'black';
            }

            // Add table row
            $html .= "<tr>
                    <td>{$drug['item_name']}</td>
                    <td>{$drug['sent_quantity']}</td>
                    <td style='color: {$fontColor};'>{$drug['current_stock']}</td>
                    <td>{$drug['dispensed_quantity']}</td>
                  </tr>";

            // Prepare chart data
            $labels[] = $drug['item_name'];
            $values[] = $drug['sent_quantity'];
        }

        return response()->json([
            'html' => $html,
            'chartData' => [
                'labels' => $labels,
                'values' => $values,
            ]
        ]);
    }

    public function globalstats(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $mode = $request->mode;

        // Fetch the data based on the mode (monthly or yearly)
        $pendingStocksQuery = DB::table("pending_stocks")
            ->select('details')
            ->where('status', 'like', 'Received');

        // Apply filters based on mode
        if ($mode === 'monthly') {
            $pendingStocksQuery->whereYear('updated_at', $year)
                ->whereMonth('updated_at', $month);
        } else if ($mode === 'yearly') {
            $pendingStocksQuery->whereYear('updated_at', $year);
        }

        // Get the pending stocks based on the selected filters
        $pendingStocks = $pendingStocksQuery->get();

        // Data extraction from JSON format
        $extractedStocks = [];
        foreach ($pendingStocks as $stock) {
            $details = json_decode($stock->details, true); // Decode JSON details
            if (is_array($details)) {
                foreach ($details as $drug) {
                    $itemNumber = $drug['item_number'];
                    $itemName = $drug['item_name'];
                    $quantity = $drug['item_quantity'];

                    if (isset($extractedStocks[$itemNumber])) {
                        $extractedStocks[$itemNumber]['quantity'] += $quantity;
                    } else {
                        $extractedStocks[$itemNumber] = [
                            'item_name' => $itemName,
                            'quantity' => $quantity,
                        ];
                    }
                }
            }
        }
        //exctract all stocks from all clinics
        $allclinicstock = [];
        $clinics = Clinic::get();

        foreach ($clinics as $clinic) {
            $tableName = preg_replace('/[^a-zA-Z0-9]/', '', $clinic->clinic_name); // Clean clinic name
            $tableName = strtolower($tableName) . '_stocks';  // Use clinic name as the table name

            // CURRENT stock in clinic 
            $clinicStocks = DB::table($tableName)
                ->select('item_name', 'item_number', 'item_quantity as stock_quantity')
                ->get();

            foreach ($clinicStocks as $clinicstock) {
                $itemNumber = $clinicstock->item_number;
                if (!isset($allclinicstock[$itemNumber])) {
                    $allclinicstock[$itemNumber] = [
                        'item_name' => $clinicstock->item_name,
                        'current_stock' => $clinicstock->stock_quantity,
                    ];
                } else {
                    $allclinicstock[$itemNumber]['current_stock'] += $clinicstock->stock_quantity;
                }
            }
        }


        // Get usage of drugs
        $dispensesQuery = DB::table('dispenses')
            ->select(
                'drug as item_name',
                'drug_number as item_number',
                DB::raw('SUM(damount) as dispensed_quantity')
            )
            ->whereYear('dispense_time', $year);

        // Apply month filter if in monthly mode
        if ($mode === 'monthly') {
            $dispensesQuery->whereMonth('dispense_time', $month);
        }

        $dispenses = $dispensesQuery->groupBy('drug_number')->get();

        $combinedData = [];
        // Index pending stocks by item_number
        foreach ($extractedStocks as $itemNumber => $drug) {
            $combinedData[$itemNumber] = [
                'item_name' => $drug['item_name'],
                'sent_quantity' => $drug['quantity'],
                'current_stock' => 0,
                'dispensed_quantity' => 0,
            ];
        }

        // Merge clinic stocks
        foreach ($allclinicstock as $itemNumber => $clinicStock) {
            if (!isset($combinedData[$itemNumber])) {
                $combinedData[$itemNumber] = [
                    'item_name' => $clinicStock['item_name'],
                    'sent_quantity' => 0,
                    'current_stock' => $clinicStock['current_stock'],
                    'dispensed_quantity' => 0,
                ];
            } else {
                $combinedData[$itemNumber]['current_stock'] = $clinicStock['current_stock'];
            }
        }

        // Merge dispense data
        foreach ($dispenses as $dispense) {
            if (!isset($combinedData[$dispense->item_number])) {
                $combinedData[$dispense->item_number] = [
                    'item_name' => $dispense->item_name,
                    'sent_quantity' => 0,
                    'current_stock' => 0,
                    'dispensed_quantity' => $dispense->dispensed_quantity,
                ];
            } else {
                $combinedData[$dispense->item_number]['dispensed_quantity'] = $dispense->dispensed_quantity;
            }
        }

        // Prepare HTML and chart data
        $html = '';
        $labels = [];
        $values = [];
        // Merge dispense data
        foreach ($combinedData as $drug) {
            // Calculate stock percentage
            $sentQuantity = $drug['sent_quantity'];
            $currentStock = $drug['current_stock'];

            if ($sentQuantity > 0 && ($currentStock / $sentQuantity) < 0.05) {
                $fontColor = 'red'; // Less than 5% of sent stock
            } else {
                $fontColor = 'black';
            }

            // Add table row
            $html .= "<tr>
                    <td>{$drug['item_name']}</td>
                    <td>{$drug['sent_quantity']}</td>
                    <td style='color: {$fontColor};'>{$drug['current_stock']}</td>
                    <td>{$drug['dispensed_quantity']}</td>
                  </tr>";

            // Prepare chart data
            $labels[] = $drug['item_name'];
            $values[] = $drug['sent_quantity'];
        }

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
    public function showDrugReport()
    {
        return view('admin.drugreportpage'); // Replace with the correct path to your Blade file
    }
}
