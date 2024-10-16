<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Admincontroller extends Controller
{
    public function getuseroptions()
    {
        $user=User::get();

        return view('admin.alluser',['user'=>$user]);
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
}
