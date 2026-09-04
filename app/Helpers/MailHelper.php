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

        $bodyText = "Dear Valued Partner,\n\n"
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

        $bodyHtml = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
            <div style="background: #0E63FF; color: #ffffff; padding: 24px; text-align: center;">
                <h2 style="margin: 0; font-size: 20px; font-weight: 700;">' . htmlspecialchars($siteTitle) . '</h2>
                <p style="margin: 5px 0 0; font-size: 13px; opacity: 0.9;">Biomedical Engineering & Medical Technology Solutions</p>
            </div>
            <div style="padding: 28px 24px; color: #334155; line-height: 1.6; font-size: 14px;">
                <h3 style="color: #0F172A; margin-top: 0;">Thank You for Subscribing!</h3>
                <p>Dear Valued Partner,</p>
                <p>You have successfully subscribed to the <strong>' . htmlspecialchars($siteTitle) . '</strong> newsletter. You will now receive timely updates on healthcare technology advancements, hospital equipment releases, preventative maintenance protocols, and exclusive biomedical insights across Pakistan.</p>
                <div style="background: #F8FAFC; border-left: 4px solid #0E63FF; padding: 14px 18px; margin: 20px 0; border-radius: 4px;">
                    <div style="font-weight: 700; color: #0F172A; margin-bottom: 6px;">Need Clinical Equipment or Demonstration?</div>
                    <div style="font-size: 13px; color: #475569;">Our engineering desk is always ready to assist hospitals, clinics, and diagnostic labs.</div>
                    <div style="margin-top: 8px; font-size: 13px;">
                        <strong>Phone / WhatsApp:</strong> ' . htmlspecialchars($phone) . '<br>
                        <strong>Email:</strong> <a href="mailto:' . htmlspecialchars($supportEmail) . '" style="color: #0E63FF;">' . htmlspecialchars($supportEmail) . '</a>
                    </div>
                </div>
                <p style="margin-bottom: 0;">Warm regards,<br><strong>Customer Engagement & Technical Desk</strong><br>' . htmlspecialchars($siteTitle) . '<br><a href="' . htmlspecialchars($website) . '" style="color: #0E63FF; text-decoration: none;">' . htmlspecialchars($website) . '</a></p>
            </div>
            <div style="background: #F1F5F9; padding: 14px 24px; text-align: center; font-size: 12px; color: #64748B; border-top: 1px solid #E2E8F0;">
                ' . htmlspecialchars($address) . '<br>
                &copy; ' . date('Y') . ' ' . htmlspecialchars($siteTitle) . '. All Rights Reserved.
            </div>
        </div>';

        return self::sendSafeMail($subscriberEmail, 'Newsletter Subscriber', $subject, $bodyText, $bodyHtml);
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
            . "Thank you for contacting {$siteTitle}. We have safely received your message regarding: \"" . ($inquiry->subject ?: ($inquiry->service_interested ?: 'Biomedical Equipment / Consultation')) . "\".\n\n"
            . "Our biomedical engineering and consultation team has been notified and a dedicated representative will review your request and get back to you shortly.\n\n"
            . "Your Submitted Message:\n"
            . "----------------------------------------\n"
            . "{$inquiry->message}\n"
            . "----------------------------------------\n\n"
            . "If your requirement is urgent, please feel free to reach our desk directly:\n"
            . "- Phone / WhatsApp: {$phone}\n"
            . "- Support Email: {$supportEmail}\n"
            . "- Office: {$address}\n\n"
            . "Warm regards,\n"
            . "Biomedical Support Team\n"
            . "{$siteTitle}\n"
            . "{$website}";

        $bodyHtml = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
            <div style="background: #0E63FF; color: #ffffff; padding: 24px; text-align: center;">
                <h2 style="margin: 0; font-size: 20px; font-weight: 700;">' . htmlspecialchars($siteTitle) . '</h2>
                <p style="margin: 5px 0 0; font-size: 13px; opacity: 0.9;">Inquiry Acknowledgement</p>
            </div>
            <div style="padding: 28px 24px; color: #334155; line-height: 1.6; font-size: 14px;">
                <h3 style="color: #0F172A; margin-top: 0;">We Have Received Your Inquiry</h3>
                <p>Dear <strong>' . htmlspecialchars($clientName) . '</strong>,</p>
                <p>Thank you for reaching out to <strong>' . htmlspecialchars($siteTitle) . '</strong>. Our team has received your message and an engineering specialist has been assigned to assist you with specifications, pricing, and consultation.</p>
                
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 14px 18px; margin: 20px 0; border-radius: 6px;">
                    <div style="font-weight: 700; color: #0F172A; font-size: 13px; margin-bottom: 8px;">Inquiry Summary:</div>
                    <div style="font-size: 13px; color: #475569; white-space: pre-line;">' . htmlspecialchars($inquiry->message) . '</div>
                </div>

                <p>If you have urgent clinical or equipment needs, please contact our helpdesk directly:</p>
                <p style="font-size: 13px;">
                    <strong>Help Desk:</strong> ' . htmlspecialchars($phone) . '<br>
                    <strong>Email:</strong> <a href="mailto:' . htmlspecialchars($supportEmail) . '" style="color: #0E63FF;">' . htmlspecialchars($supportEmail) . '</a>
                </p>

                <p style="margin-bottom: 0;">Best regards,<br><strong>Biomedical Support & Sales Team</strong><br>' . htmlspecialchars($siteTitle) . '</p>
            </div>
            <div style="background: #F1F5F9; padding: 14px 24px; text-align: center; font-size: 12px; color: #64748B; border-top: 1px solid #E2E8F0;">
                ' . htmlspecialchars($address) . '<br>
                &copy; ' . date('Y') . ' ' . htmlspecialchars($siteTitle) . '. All Rights Reserved.
            </div>
        </div>';

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

        return self::sendSafeMail($adminEmail, 'Admin Desk', $subject, $bodyText);
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

        // Step 2: Attempt actual test email send
        $subject = "Innotech Medical SMTP Diagnostic Test - " . date('Y-m-d H:i:s');
        $body = "This is a diagnostic test email from INNOTECH MEDICAL PVT LTD.\n\n"
              . "Server Time: " . date('r') . "\n"
              . "Default Mailer: {$configMailer}\n"
              . "SMTP Host: {$host}:{$port} ({$encryption})\n"
              . "From: {$fromAddress}\n\n"
              . "If you are reading this email, your live email configuration is active and working properly!";

        $sendResult = self::sendSafeMail($testToEmail, 'Admin Tester', $subject, $body);

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
