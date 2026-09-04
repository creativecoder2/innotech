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

        $sendResult = \App\Helpers\MailHelper::sendSafeMail(
            $inquiry->email,
            $inquiry->name,
            $request->subject,
            $request->reply_body
        );

        $fromAddress = config('mail.from.address') ?: \App\Models\Setting::get('support_email', 'info@innotechmed.com');

        if ($sendResult['success']) {
            $viaText = (str_contains($sendResult['mailer'], 'sendmail') || str_contains($sendResult['mailer'], 'fallback'))
                ? " (via {$sendResult['mailer']})"
                : "";
            return back()->with('success', "Email reply successfully delivered to {$inquiry->email} from {$fromAddress}{$viaText}!");
        } else {
            \Illuminate\Support\Facades\Log::error('Inquiry reply mail error: ' . $sendResult['error']);
            return back()->with('warning', "Inquiry marked as replied in database, but email delivery encountered an issue: " . $sendResult['error'] . ". You can also use the 'Open in Outlook / Gmail' button in the reply modal.");
        }
    }
}
