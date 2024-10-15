<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Response;

class printcontroller extends Controller
{
    public function printstransactionresults(Request $request)
    {$results = json_decode($request->input('results'), true); // true to convert to an associative array

        // Create CSV response
        $response = new StreamedResponse(function() use ($results) {
            $handle = fopen('php://output', 'w');
    
            // Output the column headings
            fputcsv($handle, ['Item Name', 'Item Number', 'Quantity', 'Price ($USD)', 'Clinic', 'Expiry Date', 'Procurer', 'Completed At', 'Received By', 'Received At']);
            
            // Output each row of the results
            foreach ($results as $result) {
                fputcsv($handle, [
                    $result->item_name,
                    $result->item_number,
                    $result->item_quantity,
                    $result->price,
                    $result->clinics,
                    $result->expiry_date,
                    $result->procurer,
                    $result->created_at,
                    $result->recieved_by,
                    $result->updated_at,
                ]);
            }
    
            fclose($handle);
        });
    
        // Set headers for the CSV download
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="transaction_results.csv"');
    
        return $response;
    }
}
