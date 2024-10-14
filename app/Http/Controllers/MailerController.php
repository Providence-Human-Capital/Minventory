<?php

namespace App\Http\Controllers;

use App\Models\stock_request;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class MailerController extends Controller
{
    public function sendEmail(Request $request)
{
    $requester=stock_request::where('id','=',$request->requestedId)->first()->requester;
    $request->validate([
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
        'requestedId' => 'required|integer',
    ]);

    $data = [
        'subject' => $request->subject,
        'messages' => $request->message,
        'requestedId' => $request->requestedId,
        'requester' => $requester,
    ];

    $requesteremail=User::where('name','=',$requester)->first()->email;
    Mail::send('rejectionemail', $data, function ($message) use ($data, $requesteremail) {
        $message->to($requesteremail) // Change to the recipient's email address
            ->subject($data['subject']);
    });
    $stockRequest = stock_request::where('id', $request->requestedId)->first();
    $stockRequest->update([
        'status' => 'Rejected',
        'approver' => auth()->user()->name,
        'date_approved' => Carbon::now()->toDatetimeString(),
    ]);

    return redirect()->route('mainstock')->with('success','Email sent successfully!');
}
}
