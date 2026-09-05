<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inquiry::query();

        // Keyword search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('service_interested', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->input('status')) {
            if (in_array($status, ['unread', 'read', 'replied'])) {
                $query->where('status', $status);
            }
        }

        $inquiries = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total' => Inquiry::count(),
            'unread' => Inquiry::where('status', 'unread')->count(),
            'read' => Inquiry::where('status', 'read')->count(),
            'replied' => Inquiry::where('status', 'replied')->count(),
        ];

        return view('admin.inquiries.index', compact('inquiries', 'stats'));
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

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status updated to ' . ucfirst($request->status),
                'new_status' => $request->status,
                'unread_count' => Inquiry::where('status', 'unread')->count()
            ]);
        }

        return back()->with('success', 'Inquiry status updated to ' . $request->status);
    }

    public function unreadCount()
    {
        $count = Inquiry::where('status', 'unread')->count();
        return response()->json(['unread_count' => $count]);
    }

    public function destroy(Request $request, Inquiry $inquiry)
    {
        $inquiry->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Inquiry deleted successfully!',
                'total_count' => Inquiry::count(),
                'unread_count' => Inquiry::where('status', 'unread')->count()
            ]);
        }

        return redirect()->route('admin.inquiries.index')->with('success', 'Inquiry deleted successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        $count = 0;

        if (is_array($ids) && count($ids) > 0) {
            $count = Inquiry::whereIn('id', $ids)->delete();
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} selected inquiries deleted successfully!",
            'total_count' => Inquiry::count(),
            'unread_count' => Inquiry::where('status', 'unread')->count()
        ]);
    }

    public function deleteAll(Request $request)
    {
        $count = Inquiry::count();
        Inquiry::query()->delete();

        return response()->json([
            'success' => true,
            'message' => "All {$count} customer inquiries have been deleted successfully!",
            'total_count' => 0,
            'unread_count' => 0
        ]);
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
            return back()->with('warning', "Inquiry marked as replied in database, but email delivery encountered an issue: " . $sendResult['error']);
        }
    }
}
