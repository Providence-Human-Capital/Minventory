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

        $data = DB::table("pending_stocks")

            ->select(DB::raw('DATE_FORMAT(updated_at, "%M-%y") as date'), DB::raw('SUM(item_quantity) as monthsum'), 'item_name')
            ->where('clinics', 'like', $clinic)
            ->where('status', 'like', 'Received')
            ->whereYear('updated_at', $year)  // Filter by year
            ->whereMonth('updated_at', $month) // Filter by month
            ->groupBy(DB::raw('MONTH(updated_at)'), 'item_name')
            ->orderBy('updated_at', 'asc')
            ->get();

            $labels = [];
            $values = [];
            $html = '';
            
            switch ($clinic) {
                case '81 Baines Avenue(Harare)':
                    foreach ($data as $item) {
                        
                        $clinicstock = DB::table('avenue81_stocks')->where('item_name',$item->item_name)->value('item_quantity');
                        $labels[] = $item->item_name; // Drug names
                       $values[] = $item->monthsum;   // Quantities
                       $html .= "<tr><td>{$item->item_name}</td><td>{$item->monthsum}</td><td>$clinicstock</td></tr>";
                   }
                    break;
                case '52 Baines Avenue(Harare)':
                    foreach ($data as $item) {
                    $clinicstock = DB::table('avenue52_stocks')->where('item_name',$item->item_name)->value('item_quantity');
                    $labels[] = $item->item_name; // Drug names
                       $values[] = $item->monthsum;   // Quantities
                       $html .= "<tr><td>{$item->item_name}</td><td>{$item->monthsum}</td><td>$clinicstock</td></tr>";
                   }
                    break;
                case '64 Cork road Avondale(Harare)':
                    foreach ($data as $item) {
                    $clinicstock = DB::table('avondale64_stocks')->where('item_name',$item->item_name)->value('item_quantity');
                    $labels[] = $item->item_name; // Drug names
                       $values[] = $item->monthsum;   // Quantities
                       $html .= "<tr><td>{$item->item_name}</td><td>{$item->monthsum}</td><td>$clinicstock</td></tr>";
                   }
                    break;
                case '40 Josiah Chinamano Avenue(Harare)':
                    foreach ($data as $item) {
                    $clinicstock = DB::table('chimano40_stocks')->where('item_name',$item->item_name)->value('item_quantity');
                    $labels[] = $item->item_name; // Drug names
                       $values[] = $item->monthsum;   // Quantities
                       $html .= "<tr><td>{$item->item_name}</td><td>{$item->monthsum}</td><td>$clinicstock</td></tr>";
                   }
                    break;
                case 'Epworth Clinic(Harare)':
                    foreach ($data as $item) {
                    $clinicstock = DB::table('epworth_stocks')->where('item_name',$item->item_name)->value('item_quantity');
                    $labels[] = $item->item_name; // Drug names
                       $values[] = $item->monthsum;   // Quantities
                       $html .= "<tr><td>{$item->item_name}</td><td>{$item->monthsum}</td><td>$clinicstock</td></tr>";
                   }
                    break;
                case 'Fort Street and 9th Avenue(Bulawayo)':
                    foreach ($data as $item) {
                    $clinicstock = DB::table('fortstreet_stocks')->where('item_name',$item->item_name)->value('item_quantity');
                    $labels[] = $item->item_name; // Drug names
                       $values[] = $item->monthsum;   // Quantities
                       $html .= "<tr><td>{$item->item_name}</td><td>{$item->monthsum}</td><td>$clinicstock</td></tr>";
                   }
                    break;
                case 'Royal Arcade Complex(Bulawayo)':
                    foreach ($data as $item) {
                    $clinicstock = DB::table('royalarcade_stocks')->where('item_name',$item->item_name)->value('item_quantity');
                    $labels[] = $item->item_name; // Drug names
                       $values[] = $item->monthsum;   // Quantities
                       $html .= "<tr><td>{$item->item_name}</td><td>{$item->monthsum}</td><td>$clinicstock</td></tr>";
                   }
                    break;
                case '39 6th street(GWERU)':
                    foreach ($data as $item) {
                    $clinicstock = DB::table('street6gweru_stocks')->where('item_name',$item->item_name)->value('item_quantity');
                    $labels[] = $item->item_name; // Drug names
                       $values[] = $item->monthsum;   // Quantities
                       $html .= "<tr><td>{$item->item_name}</td><td>{$item->monthsum}</td><td>$clinicstock</td></tr>";
                   }
                    break;
                case '126 Herbert Chitepo Street(Mutare)':
                    foreach ($data as $item) {
                    $clinicstock = DB::table('chitepo126mutare_stock')->get();
                    $labels[] = $item->item_name; // Drug names
                       $values[] = $item->monthsum;   // Quantities
                       $html .= "<tr><td>{$item->item_name}</td><td>{$item->monthsum}</td><td>$clinicstock</td></tr>";
                   }
                    break;
                case '13 Shuvai Mahofa street(Masvingo)':
                    foreach ($data as $item) {
                    $clinicstock = DB::table('shuvaimahofa13masvingo_stocks')->where('item_name',$item->item_name)->value('item_quantity');
                    $labels[] = $item->item_name; // Drug names
                       $values[] = $item->monthsum;   // Quantities
                       $html .= "<tr><td>{$item->item_name}</td><td>{$item->monthsum}</td><td>$clinicstock</td></tr>";
                   }
                    break;
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
