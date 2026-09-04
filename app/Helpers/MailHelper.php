<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailHelper
{
    /**
     * Send email with automatic fallback:
     * 1. Attempt primary configured mailer (e.g. SMTP)
     * 2. If SMTP fails (socket error, timeout, auth error), attempt cPanel server sendmail
     * Automatically wraps plain-text in the branded Innotech Medical HTML template.
     *
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     * @param string $bodyText
     * @param string|null $bodyHtml
     * @return array ['success' => bool, 'mailer' => string, 'error' => string|null]
     */
    public static function sendSafeMail(string $toEmail, string $toName, string $subject, string $bodyText, ?string $bodyHtml = null): array
    {
        $toEmail = trim($toEmail);
        if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'mailer' => 'none', 'error' => 'Invalid recipient email address: ' . $toEmail];
        }

        $fromAddress = config('mail.from.address') ?: Setting::get('support_email', 'info@innotechmed.com');
        $fromName = config('mail.from.name') ?: Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD');

        // Automatically wrap in master branded template if custom HTML is not provided
        if (!$bodyHtml) {
            try {
                $bodyHtml = view('emails.master', [
                    'subject' => $subject,
                    'heading' => $subject,
                    'contentText' => $bodyText,
                    'badgeText' => 'Official Communication',
                    'actionUrl' => url('/'),
                    'actionText' => 'Visit Web Portal',
                ])->render();
            } catch (\Throwable $e) {
                Log::warning('Email master template rendering failed: ' . $e->getMessage());
                $bodyHtml = '<div style="font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; color: #334155;">' . nl2br(e($bodyText)) . '</div>';
            }
        }

        $primaryMailer = config('mail.default', 'smtp');
        $errorMessage = null;

        // Attempt 1: Primary mailer (SMTP)
        try {
            self::dispatchMessage($primaryMailer, $toEmail, $toName, $fromAddress, $fromName, $subject, $bodyText, $bodyHtml);
            return ['success' => true, 'mailer' => $primaryMailer, 'error' => null];
        } catch (\Throwable $e) {
            $errorMessage = $e->getMessage();
            Log::warning("Primary mailer [{$primaryMailer}] failed: {$errorMessage}. Attempting server sendmail fallback...");
        }

        // Attempt 2: Sendmail fallback (Linux / cPanel native mail transport)
        if ($primaryMailer !== 'sendmail') {
            try {
                self::dispatchMessage('sendmail', $toEmail, $toName, $fromAddress, $fromName, $subject, $bodyText, $bodyHtml);
                Log::info("Mail successfully dispatched via sendmail fallback to {$toEmail}");
                return ['success' => true, 'mailer' => 'sendmail (fallback)', 'error' => null];
            } catch (\Throwable $ex) {
                Log::error("Sendmail fallback also failed: " . $ex->getMessage());
                $errorMessage .= ' | Sendmail fallback error: ' . $ex->getMessage();
            }
        }

        return [
            'success' => false,
            'mailer' => $primaryMailer,
            'error' => $errorMessage
        ];
    }

    /**
     * Dispatch single message through specific mailer driver
     */
    protected static function dispatchMessage(string $mailer, string $toEmail, string $toName, string $fromAddress, string $fromName, string $subject, string $bodyText, ?string $bodyHtml = null): void
    {
        Mail::mailer($mailer)->send([], [], function ($msg) use ($toEmail, $toName, $fromAddress, $fromName, $subject, $bodyText, $bodyHtml) {
            $msg->to($toEmail, $toName ?: $toEmail)
                ->from($fromAddress, $fromName)
                ->replyTo($fromAddress, $fromName)
                ->subject($subject);

            if ($bodyHtml) {
                $msg->html($bodyHtml);
                $msg->text($bodyText);
            } else {
                $msg->text($bodyText);
            }
        });
    }

    /**
     * Send automated Welcome / Confirmation Email to new Newsletter subscriber
     */
    public static function sendNewsletterWelcome(string $subscriberEmail): array
    {
        $siteTitle = Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD');
        $phone = Setting::get('helpdesk_phone', '+92 331 6699992');
        $supportEmail = Setting::get('support_email', 'info@innotechmed.com');
        $address = Setting::get('office_address', '1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.');
        $website = url('/');

        $subject = "Welcome to {$siteTitle} - Newsletter Subscription Confirmed";

        $bodyText = "Dear Valued Healthcare Partner,\n\n"
            . "Thank you for subscribing to the {$siteTitle} newsletter!\n\n"
            . "As Pakistan's growing distributor of advanced biomedical instrumentation, diagnostic systems, and critical care solutions, we are committed to keeping healthcare professionals and institutions equipped with the latest clinical technologies.\n\n"
            . "You will now receive periodic updates regarding our equipment releases, preventative maintenance insights, and turnkey hospital solutions.\n\n"
            . "Need immediate technical assistance or clinical consultation?\n"
            . "- Phone / WhatsApp: {$phone}\n"
            . "- Support Email: {$supportEmail}\n"
            . "- Office: {$address}\n"
            . "- Website: {$website}\n\n"
            . "Warm regards,\n"
            . "Customer Engagement & Technical Desk\n"
            . "{$siteTitle}";

        $contentHtml = '
            <p>Dear <strong>Valued Healthcare Partner</strong>,</p>
            <p>Thank you for subscribing to the <strong>' . htmlspecialchars($siteTitle) . '</strong> newsletter!</p>
            <p>As Pakistan\'s growing distributor of advanced biomedical instrumentation, diagnostic systems, and critical care solutions, we are committed to keeping healthcare professionals, hospitals, and diagnostic institutions equipped with the latest clinical technologies.</p>
            <p>You will now receive periodic updates regarding our new medical equipment releases, preventative maintenance insights, special hospital packages, and turnkey clinical engineering solutions.</p>
            
            <div style="background-color: #F0FDF4; border: 1.5px solid #86EFAC; border-radius: 8px; padding: 16px 20px; margin: 22px 0;">
                <div style="font-weight: 700; color: #166534; font-size: 13.5px; margin-bottom: 4px;">
                    ✅ Subscription Status: Active & Confirmed
                </div>
                <div style="color: #15803D; font-size: 13px;">
                    Your email <strong>' . htmlspecialchars($subscriberEmail) . '</strong> is now enrolled for biomedical alerts and product updates.
                </div>
            </div>
            
            <p style="margin-bottom: 0;">Warm regards,<br>
            <strong>Customer Engagement & Technical Desk</strong><br>
            ' . htmlspecialchars($siteTitle) . '</p>
        ';

        try {
            $bodyHtml = view('emails.master', [
                'subject' => $subject,
                'heading' => 'Welcome to ' . $siteTitle,
                'badgeText' => '📬 Newsletter Subscription Confirmed',
                'contentHtml' => $contentHtml,
                'contentText' => $bodyText,
                'actionUrl' => url('/products'),
                'actionText' => 'Explore Medical Products Catalog',
            ])->render();
        } catch (\Throwable $e) {
            $bodyHtml = null;
        }

        return self::sendSafeMail($subscriberEmail, 'Valued Subscriber', $subject, $bodyText, $bodyHtml);
    }

    /**
     * Send automated acknowledgement to visitor who submitted Contact Form
     */
    public static function sendInquiryAcknowledgement($inquiry): array
    {
        $siteTitle = Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD');
        $phone = Setting::get('helpdesk_phone', '+92 331 6699992');
        $supportEmail = Setting::get('support_email', 'info@innotechmed.com');
        $address = Setting::get('office_address', '1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.');
        $website = url('/');

        $clientName = $inquiry->name ?: 'Valued Client';
        $subject = "Thank you for contacting {$siteTitle} [Inquiry #{$inquiry->id}]";

        $bodyText = "Dear {$clientName},\n\n"
            . "Thank you for reaching out to {$siteTitle}. We have received your message regarding: \"" . ($inquiry->subject ?: ($inquiry->service_interested ?: 'Biomedical Equipment / Consultation')) . "\".\n\n"
            . "Our biomedical engineering department has received your request:\n"
            . "\"{$inquiry->message}\"\n\n"
            . "A dedicated technical consultant has been assigned to your request and can arrange an on-site demonstration, technical specifications, and formal quotation.\n\n"
            . "Best regards,\n"
            . "Biomedical Support & Sales Team\n"
            . "{$siteTitle}";

        $contentHtml = '
            <p>Dear <strong>' . htmlspecialchars($clientName) . '</strong>,</p>
            <p>Thank you for reaching out to <strong>' . htmlspecialchars($siteTitle) . '</strong> regarding your biomedical and clinical equipment requirements.</p>
            
            <div style="background-color: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 8px; padding: 16px 20px; margin: 22px 0;">
                <div style="font-weight: 700; color: #0F172A; font-size: 13.5px; margin-bottom: 6px;">
                    📋 Your Submitted Inquiry Summary:
                </div>
                <div style="font-size: 13px; color: #475569; margin-bottom: 8px;">
                    <strong>Subject / Service:</strong> ' . htmlspecialchars($inquiry->subject ?: ($inquiry->service_interested ?: 'General Equipment Inquiry')) . '
                </div>
                <div style="font-size: 13px; color: #334155; background: #FFFFFF; border: 1px solid #CBD5E1; border-radius: 6px; padding: 12px 14px; white-space: pre-wrap; font-style: italic;">"' . htmlspecialchars($inquiry->message) . '"</div>
            </div>

            <p>Our biomedical engineering department has received your request. A dedicated clinical consultant has been assigned to your institution and will assist you with official technical specifications, quotation, hospital installation, and OEM warranty details.</p>
            
            <p style="margin-bottom: 0;">Best regards,<br>
            <strong>Biomedical Support & Sales Team</strong><br>
            ' . htmlspecialchars($siteTitle) . '</p>
        ';

        try {
            $bodyHtml = view('emails.master', [
                'subject' => $subject,
                'heading' => 'Inquiry Received & Under Review',
                'badgeText' => '🏥 Inquiry #' . $inquiry->id . ' Acknowledged',
                'contentHtml' => $contentHtml,
                'contentText' => $bodyText,
                'actionUrl' => url('/products'),
                'actionText' => 'Browse Equipment Catalog',
            ])->render();
        } catch (\Throwable $e) {
            $bodyHtml = null;
        }

        return self::sendSafeMail($inquiry->email, $clientName, $subject, $bodyText, $bodyHtml);
    }

    /**
     * Send notification alert to Admin when new Inquiry or Newsletter subscription arrives
     */
    public static function sendAdminInquiryAlert($inquiry): array
    {
        $adminEmail = Setting::get('support_email') ?: config('mail.from.address', 'info@innotechmed.com');
        $siteTitle = Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD');

        $isNewsletter = ($inquiry->name === 'Newsletter Subscriber' || str_contains(strtolower($inquiry->message), 'newsletter'));
        $typeLabel = $isNewsletter ? 'Newsletter Subscription' : 'New Website Inquiry';

        $subject = "[{$siteTitle}] 🔔 {$typeLabel}: " . ($inquiry->name ?: $inquiry->email);

        $bodyText = "A new {$typeLabel} has been submitted on {$siteTitle}:\n\n"
            . "Client Name: {$inquiry->name}\n"
            . "Email: {$inquiry->email}\n"
            . "Phone: " . ($inquiry->phone ?: 'N/A') . "\n"
            . "Service / Subject: " . ($inquiry->service_interested ?: ($inquiry->subject ?: 'General')) . "\n"
            . "Received At: " . now()->format('Y-m-d H:i:s') . "\n\n"
            . "Message Content:\n"
            . "----------------------------------------\n"
            . "{$inquiry->message}\n"
            . "----------------------------------------\n\n"
            . "You can view and reply to this lead directly in your Admin Panel:\n"
            . url('/admin/inquiries');

        $contentHtml = '
            <p style="margin-top: 0;">A new <strong>' . htmlspecialchars($typeLabel) . '</strong> lead has just arrived through the website portal:</p>
            
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 18px 0; background-color: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 8px; overflow: hidden; font-size: 13.5px;">
                <tr>
                    <td style="padding: 10px 16px; border-bottom: 1px solid #E2E8F0; width: 140px; font-weight: 700; color: #475569;">Lead Name:</td>
                    <td style="padding: 10px 16px; border-bottom: 1px solid #E2E8F0; color: #0F172A; font-weight: 600;">' . htmlspecialchars($inquiry->name) . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px 16px; border-bottom: 1px solid #E2E8F0; font-weight: 700; color: #475569;">Email Address:</td>
                    <td style="padding: 10px 16px; border-bottom: 1px solid #E2E8F0;"><a href="mailto:' . htmlspecialchars($inquiry->email) . '" style="color: #0E63FF; text-decoration: none;">' . htmlspecialchars($inquiry->email) . '</a></td>
                </tr>
                <tr>
                    <td style="padding: 10px 16px; border-bottom: 1px solid #E2E8F0; font-weight: 700; color: #475569;">Phone Number:</td>
                    <td style="padding: 10px 16px; border-bottom: 1px solid #E2E8F0;">' . htmlspecialchars($inquiry->phone ?: 'Not provided') . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px 16px; border-bottom: 1px solid #E2E8F0; font-weight: 700; color: #475569;">Subject / Type:</td>
                    <td style="padding: 10px 16px; border-bottom: 1px solid #E2E8F0;">' . htmlspecialchars($inquiry->service_interested ?: ($inquiry->subject ?: 'General Inquiry')) . '</td>
                </tr>
                <tr>
                    <td style="padding: 10px 16px; font-weight: 700; color: #475569; vertical-align: top;">Message:</td>
                    <td style="padding: 10px 16px; color: #334155; white-space: pre-wrap;">' . htmlspecialchars($inquiry->message) . '</td>
                </tr>
            </table>

            <p style="font-size: 13px; color: #64748B;">You can respond to this inquiry directly through your administrative portal or via the quick button below.</p>
        ';

        try {
            $bodyHtml = view('emails.master', [
                'subject' => $subject,
                'heading' => 'New Lead: ' . ($inquiry->name ?: $inquiry->email),
                'badgeText' => '⚡ Admin Lead Notification',
                'contentHtml' => $contentHtml,
                'contentText' => $bodyText,
                'actionUrl' => url('/admin/inquiries'),
                'actionText' => 'View & Reply in Admin Panel',
            ])->render();
        } catch (\Throwable $e) {
            $bodyHtml = null;
        }

        return self::sendSafeMail($adminEmail, 'Admin Desk', $subject, $bodyText, $bodyHtml);
    }

    /**
     * Perform live SMTP / Server Mail Diagnostic Test
     */
    public static function testConnection(string $testToEmail): array
    {
        $testToEmail = trim($testToEmail);
        $logs = [];
        $startTime = microtime(true);

        $configMailer = config('mail.default', 'smtp');
        $host = config('mail.mailers.smtp.host');
        $port = config('mail.mailers.smtp.port');
        $encryption = config('mail.mailers.smtp.encryption');
        $username = config('mail.mailers.smtp.username');
        $fromAddress = config('mail.from.address', 'info@innotechmed.com');
        $fromName = config('mail.from.name', 'INNOTECH MEDICAL PVT LTD');
        $verifyPeer = config('mail.mailers.smtp.verify_peer');

        $logs[] = "Mail Configuration Check:";
        $logs[] = "  - Default Driver: " . $configMailer;
        $logs[] = "  - SMTP Host: " . ($host ?: 'NOT SET');
        $logs[] = "  - SMTP Port: " . ($port ?: 'NOT SET');
        $logs[] = "  - SMTP Encryption: " . ($encryption ?: 'none');
        $logs[] = "  - SMTP Username: " . ($username ?: 'NOT SET');
        $logs[] = "  - From Address: {$fromAddress} ({$fromName})";
        $logs[] = "  - SSL Peer Verification: " . ($verifyPeer ? 'Enabled (Strict)' : 'Disabled (Compatible / Recommended for cPanel)');

        // Step 1: Socket test to SMTP host if SMTP is configured
        if ($configMailer === 'smtp' && $host && $port) {
            $logs[] = "Testing TCP socket connection to {$host}:{$port}...";
            $connectionTimeout = 5;
            $socket = @fsockopen($host, (int) $port, $errno, $errstr, $connectionTimeout);
            if ($socket) {
                $logs[] = "  ✓ Successfully opened TCP socket connection to {$host}:{$port} in " . round((microtime(true) - $startTime) * 1000, 1) . "ms";
                fclose($socket);
            } else {
                $logs[] = "  ⚠ Direct TCP socket connection failed (Error #{$errno}: {$errstr}). Outbound port {$port} may be blocked by your hosting firewall.";
            }
        }

        // Step 2: Attempt actual test email send with branded HTML template
        $subject = "Innotech Medical SMTP Diagnostic Test - " . date('Y-m-d H:i:s');
        $body = "This is a diagnostic test email from INNOTECH MEDICAL PVT LTD.\n\n"
              . "Server Time: " . date('r') . "\n"
              . "Default Mailer: {$configMailer}\n"
              . "SMTP Host: {$host}:{$port} ({$encryption})\n"
              . "From: {$fromAddress}\n\n"
              . "If you are reading this email, your live email delivery system is active, verified, and functioning properly!";

        $contentHtml = '
            <p>Dear <strong>Administrator</strong>,</p>
            <p>This is a live diagnostic test email successfully dispatched from <strong>INNOTECH MEDICAL PVT LTD</strong>.</p>
            
            <div style="background-color: #F0FDF4; border: 1.5px solid #86EFAC; border-radius: 8px; padding: 16px 20px; margin: 20px 0;">
                <div style="font-weight: 700; color: #166534; font-size: 14px; margin-bottom: 6px;">
                    ✅ Email Delivery System: Active & Online
                </div>
                <div style="color: #15803D; font-size: 13px; line-height: 1.6;">
                    <strong>Driver:</strong> ' . htmlspecialchars($configMailer) . '<br>
                    <strong>Host:</strong> ' . htmlspecialchars($host ?: 'localhost') . ':' . htmlspecialchars($port ?: '25') . ' (' . strtoupper(htmlspecialchars($encryption ?: 'NONE')) . ')<br>
                    <strong>From:</strong> ' . htmlspecialchars($fromAddress) . ' (' . htmlspecialchars($fromName) . ')<br>
                    <strong>Timestamp:</strong> ' . date('r') . '
                </div>
            </div>

            <p style="margin-bottom: 0;">Your live emails are now formatted in the official Innotech Medical corporate theme.</p>
        ';

        try {
            $bodyHtml = view('emails.master', [
                'subject' => $subject,
                'heading' => 'Diagnostic Test Verification',
                'badgeText' => '⚡ System Test Successful',
                'contentHtml' => $contentHtml,
                'contentText' => $body,
                'actionUrl' => url('/admin/settings?tab=email'),
                'actionText' => 'Return to Admin Settings',
            ])->render();
        } catch (\Throwable $e) {
            $bodyHtml = null;
        }

        $sendResult = self::sendSafeMail($testToEmail, 'Admin Tester', $subject, $body, $bodyHtml);

        $elapsed = round((microtime(true) - $startTime) * 1000, 1);

        if ($sendResult['success']) {
            $logs[] = "  ✓ Test email successfully sent to {$testToEmail} via [{$sendResult['mailer']}] in {$elapsed}ms!";
            return [
                'success' => true,
                'used_mailer' => $sendResult['mailer'],
                'elapsed_ms' => $elapsed,
                'logs' => $logs,
                'message' => "Test email successfully sent to {$testToEmail} via {$sendResult['mailer']}!"
            ];
        } else {
            $logs[] = "  ✗ Failed to send email: " . $sendResult['error'];
            return [
                'success' => false,
                'used_mailer' => $sendResult['mailer'],
                'elapsed_ms' => $elapsed,
                'logs' => $logs,
                'error' => $sendResult['error'],
                'message' => "Email delivery failed: " . $sendResult['error']
            ];
        }
    }
}
