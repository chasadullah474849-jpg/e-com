<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL', 'chasadullah474849@gmail.com'));

        // Send mail to admin
        Mail::to($adminEmail)->send(new ContactFormMail($validated));

        return redirect()->back()->with('success', 'Your message has been sent successfully to the admin!');
    }
}
