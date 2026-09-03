<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function show(Inquiry $inquiry)
    {
        if ($inquiry->status === 'unread') {
            $inquiry->update(['status' => 'read']);
        }
        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function updateStatus(Request $request, Inquiry $inquiry)
    {
        $request->validate(['status' => 'required|in:unread,read,replied']);
        $inquiry->update(['status' => $request->status]);
        return back()->with('success', 'Inquiry status updated to ' . $request->status);
    }

    public function unreadCount()
    {
        $count = Inquiry::where('status', 'unread')->count();
        return response()->json(['unread_count' => $count]);
    }

    public function reply(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'subject' => 'required|string',
            'reply_body' => 'required|string',
        ]);

        $inquiry->update(['status' => 'replied']);

        try {
            $fromAddress = config('mail.from.address', 'info@innotechmed.com');
            $fromName = config('mail.from.name', 'INNOTECH MEDICAL PVT LTD');

            \Illuminate\Support\Facades\Mail::raw($request->reply_body, function ($msg) use ($inquiry, $request, $fromAddress, $fromName) {
                $msg->to($inquiry->email, $inquiry->name)
                    ->from($fromAddress, $fromName)
                    ->replyTo($fromAddress, $fromName)
                    ->subject($request->subject);
            });

            return back()->with('success', 'Email reply successfully sent to ' . $inquiry->email . ' from ' . $fromAddress . '!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SMTP Mail Error: ' . $e->getMessage());
            return back()->with('warning', 'Inquiry marked as replied in database, but SMTP delivery could not connect: ' . $e->getMessage() . '. Please verify your cPanel email password in .env.');
        }
    }
}
