<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class EmailingController extends Controller
{
    public function index()
    {
        return view('admin.emailing.index');
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipients' => 'required|array',
        ]);

        foreach ($request->recipients as $email) {
            Mail::raw($request->message, function ($msg) use ($email, $request) {
                $msg->to($email)->subject($request->subject);
            });
        }

        return back()->with('success', 'Email envoyé à tous les destinataires sélectionnés.');
    }
}
