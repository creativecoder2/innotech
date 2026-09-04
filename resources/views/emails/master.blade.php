<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $subject ?? 'INNOTECH MEDICAL PVT LTD' }}</title>
    <!--[if mso]>
    <style type="text/css">
        table {border-collapse:collapse;border-spacing:0;margin:0;}
        div, td {padding:0;}
        div {margin:0 !important;}
    </style>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
            <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style type="text/css">
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #F1F5F9; font-family: 'Segoe UI', Arial, sans-serif; }
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
        @media screen and (max-width: 600px) {
            .email-container { width: 100% !important; margin: auto !important; }
            .content-padding { padding: 24px 18px !important; }
            .header-padding { padding: 24px 18px !important; }
            .footer-padding { padding: 24px 18px !important; }
            .stack-column { display: block !important; width: 100% !important; max-width: 100% !important; direction: ltr !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #F1F5F9; -webkit-font-smoothing: antialiased;">

    <!-- Preheader preview text (hidden in display, visible in inbox preview snippet) -->
    <div style="display: none; font-size: 1px; color: #F1F5F9; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
        {{ $preheader ?? 'Important update from INNOTECH MEDICAL PVT LTD - Biomedical Equipment & Healthcare Technology Solutions.' }}
    </div>

    <!-- Main Container Table -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F1F5F9; table-layout: fixed;">
        <tr>
            <td align="center" style="padding: 30px 10px 40px 10px;">

                <!-- Email Box Wrapper (Max 620px) -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" class="email-container" style="max-width: 620px; background-color: #FFFFFF; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08); border: 1px solid #E2E8F0;">

                    <!-- Top Theme Accent Bar -->
                    <tr>
                        <td style="height: 5px; background: linear-gradient(90deg, #0E63FF 0%, #00D26A 50%, #0E63FF 100%);"></td>
                    </tr>

                    <!-- Header Area: Clean White with Crisp Brand Logo -->
                    <tr>
                        <td align="center" class="header-padding" style="padding: 30px 35px 22px 35px; background-color: #FFFFFF; border-bottom: 1px solid #F1F5F9;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        @php
                                            $logoUrl = asset(\App\Models\Setting::get('logo_path', 'assets/img/logo/logo.png'));
                                            if (!str_starts_with($logoUrl, 'http')) {
                                                $logoUrl = url($logoUrl);
                                            }
                                            $siteTitle = \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD');
                                        @endphp
                                        <a href="{{ url('/') }}" target="_blank" style="text-decoration: none; display: inline-block;">
                                            <img src="{{ $logoUrl }}" alt="{{ $siteTitle }}" width="190" style="display: block; max-width: 210px; width: 190px; height: auto; margin: 0 auto;" />
                                        </a>
                                        <div style="margin-top: 10px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #0E63FF;">
                                            Innovating Health Care With Advance Technologies
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Email Main Body Content -->
                    <tr>
                        <td class="content-padding" style="padding: 35px 35px 30px 35px; color: #334155; font-size: 14.5px; line-height: 1.7; font-family: 'Segoe UI', Arial, sans-serif;">
                            
                            @if(isset($badgeText) && $badgeText)
                            <div style="margin-bottom: 20px;">
                                <span style="display: inline-block; background-color: #EFF6FF; color: #0E63FF; font-size: 12px; font-weight: 700; padding: 5px 12px; border-radius: 20px; border: 1px solid #BFDBFE;">
                                    {{ $badgeText }}
                                </span>
                            </div>
                            @endif

                            @if(isset($heading) && $heading)
                            <h1 style="color: #0F172A; font-size: 20px; font-weight: 700; margin: 0 0 16px 0; line-height: 1.35;">
                                {{ $heading }}
                            </h1>
                            @endif

                            <!-- Dynamic Message Content -->
                            <div style="color: #334155; font-size: 14.5px; line-height: 1.75;">
                                {!! $contentHtml ?? nl2br(e($contentText ?? '')) !!}
                            </div>

                            <!-- Helpdesk & Quick Contact Highlight Box -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 30px; background-color: #F8FAFC; border-radius: 8px; border: 1px solid #E2E8F0; overflow: hidden;">
                                <tr>
                                    <td style="padding: 18px 22px;">
                                        <div style="font-size: 13px; font-weight: 700; color: #0F172A; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; display: flex; align-items: center;">
                                            🏥 Need Immediate Biomedical Assistance?
                                        </div>
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; color: #475569; line-height: 1.6;">
                                            <tr>
                                                <td style="padding: 3px 0; width: 22px; vertical-align: top;">📞</td>
                                                <td style="padding: 3px 0;"><strong>Help Desk / WhatsApp:</strong> <a href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992')) }}" style="color: #0E63FF; text-decoration: none; font-weight: 600;">{{ \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992') }}</a></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 3px 0; width: 22px; vertical-align: top;">✉️</td>
                                                <td style="padding: 3px 0;"><strong>Official Email:</strong> <a href="mailto:{{ \App\Models\Setting::get('support_email', 'info@innotechmed.com') }}" style="color: #0E63FF; text-decoration: none; font-weight: 600;">{{ \App\Models\Setting::get('support_email', 'info@innotechmed.com') }}</a></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 3px 0; width: 22px; vertical-align: top;">🌐</td>
                                                <td style="padding: 3px 0;"><strong>Web Portal:</strong> <a href="{{ url('/') }}" target="_blank" style="color: #0E63FF; text-decoration: none; font-weight: 600;">www.innotechmed.com</a></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            @if(isset($actionUrl) && $actionUrl)
                            <!-- Action Button -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 25px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $actionUrl }}" target="_blank" style="display: inline-block; background-color: #0E63FF; color: #FFFFFF; font-size: 14px; font-weight: 600; text-decoration: none; padding: 12px 28px; border-radius: 6px; box-shadow: 0 4px 10px rgba(14, 99, 255, 0.25);">
                                            {{ $actionText ?? 'Visit Website' }} &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            @endif

                        </td>
                    </tr>

                    <!-- Footer Area -->
                    <tr>
                        <td class="footer-padding" style="padding: 26px 35px 28px 35px; background-color: #0F172A; color: #94A3B8; font-size: 12px; line-height: 1.6; text-align: center;">
                            
                            <!-- Company Name & Description -->
                            <div style="font-size: 13.5px; font-weight: 700; color: #FFFFFF; margin-bottom: 6px;">
                                {{ $siteTitle }}
                            </div>
                            <div style="font-size: 12px; color: #94A3B8; margin-bottom: 12px; max-width: 500px; margin-left: auto; margin-right: auto;">
                                Pakistan's leading distributor of top-quality medical equipment, biomedical engineering systems, and clinical healthcare technologies.
                            </div>

                            <!-- Physical Office Address -->
                            <div style="font-size: 11.5px; color: #64748B; margin-bottom: 14px; padding: 0 10px;">
                                📍 {{ \App\Models\Setting::get('office_address', '1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.') }}
                            </div>

                            <div style="height: 1px; background-color: #1E293B; margin: 15px auto; width: 80%;"></div>

                            <!-- Copyright Notice -->
                            <div style="font-size: 11px; color: #64748B;">
                                {{ \App\Models\Setting::get('copyright_text', '© 2026 INNOTECH MEDICAL PVT LTD. All Rights Reserved.') }}
                            </div>

                            <div style="font-size: 11px; color: #475569; margin-top: 8px;">
                                You received this communication because of an inquiry, quotation request, or newsletter subscription on <a href="{{ url('/') }}" target="_blank" style="color: #60A5FA; text-decoration: none;">innotechmed.com</a>.
                            </div>

                        </td>
                    </tr>

                </table>
                <!-- End Email Box -->

            </td>
        </tr>
    </table>

</body>
</html>
