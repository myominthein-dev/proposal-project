<?php

namespace App\Http\Controllers;

use App\Mail\FollowUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendMail(Request $request)
    {

        $request->validate([
            'to' => ['required', 'email'],
            'subject' => ['required', 'string'],
            'message' => ['required', 'string'],
        ]);

        Mail::to($request->to)->send(new FollowUp($request->subject, $request->message));
        
        return to_route('appointments.list');
    }
}
