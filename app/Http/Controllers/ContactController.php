<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'service_interested' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $normalizedEmail = strtolower(trim($validated['email']));
        $emailHash = sha1($normalizedEmail);
        $lockKey = 'inquiry_locked_' . $emailHash;
        $attemptKey = 'inquiry_attempts_' . $emailHash;

        // 1. Check if email is currently suspended for 2 hours
        if (Cache::has($lockKey)) {
            $expiryTime = Cache::get($lockKey);
            $remainingSeconds = max(0, $expiryTime - time());
            $remainingMinutes = ceil($remainingSeconds / 60);
            $remainingHours = round($remainingSeconds / 3600, 1);

            $timeLeftStr = ($remainingHours >= 1) ? "{$remainingHours} hours" : "{$remainingMinutes} minutes";
            $lockMessage = "Security Alert: Too many submission attempts detected for {$normalizedEmail}. Submissions from this email address are temporarily suspended for 2 hours. Please try again after {$timeLeftStr}.";

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'suspended' => true,
                    'remaining_seconds' => $remainingSeconds,
                    'message' => $lockMessage,
                ], 429);
            }

            return back()->with('error', $lockMessage)->withInput();
        }

        // 2. Track attempts within a 15-minute sliding window
        $attempts = (int) Cache::get($attemptKey, 0) + 1;

        if ($attempts > 3) {
            // Suspend email for 2 hours (7200 seconds)
            $lockUntil = time() + 7200;
            Cache::put($lockKey, $lockUntil, 7200);
            Cache::forget($attemptKey);

            $lockMessage = "Security Alert: You have exceeded the limit of 3 submission attempts for {$normalizedEmail}. For security purposes, this email address has been suspended for 2 hours. Please try again later.";

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'suspended' => true,
                    'remaining_seconds' => 7200,
                    'message' => $lockMessage,
                ], 429);
            }

            return back()->with('error', $lockMessage)->withInput();
        } else {
            // Store updated attempt count with 15-minute expiration
            Cache::put($attemptKey, $attempts, 900);
        }

        $inquiry = Inquiry::create($validated);

        // Send automated acknowledgment / welcome email and notify admin safely
        try {
            $isNewsletter = (
                (isset($validated['name']) && $validated['name'] === 'Newsletter Subscriber') ||
                (isset($validated['message']) && str_contains(strtolower($validated['message']), 'newsletter')) ||
                (isset($validated['subject']) && str_contains(strtolower($validated['subject']), 'newsletter'))
            );

            if ($isNewsletter) {
                \App\Helpers\MailHelper::sendNewsletterWelcome($inquiry->email);
            } else {
                \App\Helpers\MailHelper::sendInquiryAcknowledgement($inquiry);
            }

            // Also alert the admin team
            \App\Helpers\MailHelper::sendAdminInquiryAlert($inquiry);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Automatic email notification on inquiry submission failed: ' . $e->getMessage());
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your message has been sent successfully. Our team will contact you shortly.'
            ]);
        }

        return back()->with('success', 'Thank you! Your inquiry has been received. Our team will get back to you shortly.');
    }
}
