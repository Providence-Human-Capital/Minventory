<?php

namespace App\Http\Controllers;

use App\Models\stock_request;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailerController extends Controller
{
    public function sendEmail(Request $request)
{
    $request->validate([
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
        'requestedId' => 'required|integer',
    ]);

    $data = [
        'subject' => $request->subject,
        'message' => (string)$request->message,
        'requestedId' => $request->requestedId,
    ];
    $requester=stock_request::where('id','=',$request->requestedId)->first()->requester;
    $requesteremail=User::where('name','=',$requester)->first()->email;
    Mail::send('rejectionemail', $data, function ($message) use ($data, $requesteremail) {
        $message->to($requesteremail) // Change to the recipient's email address
                ->subject($data['subject']);
    });


    return redirect()->route('mainstock')->with('success','Email sent successfully!');
}
}
