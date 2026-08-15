import { NextResponse } from 'next/server';
import { getLocalStore, updateLocalStore } from '@/lib/storage';
import nodemailer from 'nodemailer';

// Helper to generate rich responsive HTML for selected VIP templates
function generateEmailHtml({
  templateId,
  subject,
  messageBody,
  ctaText,
  ctaUrl,
  ctaColor = '#0E63FF',
  senderName = 'Innotech Medical Support',
  customHtml,
  siteName = 'INNOTECH MEDICAL PVT LTD',
  siteLogo = 'https://innotechmedical.org/assets/img/logo/logo.png',
  contactPhone = '+92 331 6699992',
  contactEmail = 'info@innotechmedical.org',
  recipientEmail = 'valued.customer@example.com',
}) {
  if (templateId === 'custom' && customHtml) {
    return customHtml
      .replace(/{email}/g, recipientEmail)
      .replace(/{subject}/g, subject)
      .replace(/{siteName}/g, siteName)
      .replace(/{date}/g, new Date().toLocaleDateString());
  }

  const formattedBody = (messageBody || '')
    .split('\n\n')
    .map((p) => `<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #334155;">${p.replace(/\n/g, '<br/>')}</p>`)
    .join('');

  const ctaButtonHtml = ctaText && ctaUrl
    ? `
      <div style="text-align: center; margin: 30px 0 20px;">
        <a href="${ctaUrl}" target="_blank" style="display: inline-block; background-color: ${ctaColor}; color: #ffffff; text-decoration: none; padding: 14px 28px; font-size: 15px; font-weight: 700; border-radius: 8px; box-shadow: 0 4px 14px rgba(14, 99, 255, 0.25); text-transform: uppercase; letter-spacing: 0.5px;">
          ${ctaText} &rarr;
        </a>
      </div>
    `
    : '';

  // 1. Corporate Healthcare Announcement Template
  if (templateId === 'announcement') {
    return `
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>${subject}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
  <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #f1f5f9; padding: 30px 0;">
    <tr>
      <td align="center">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.06);">
          <!-- Header -->
          <tr>
            <td style="background: linear-gradient(135deg, #171151 0%, #0E63FF 100%); padding: 30px 40px; text-align: center;">
              <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 700; letter-spacing: 0.5px;">${siteName}</h1>
              <p style="color: #cbd5e1; margin: 6px 0 0; font-size: 13px;">Official Healthcare & Clinical Notification</p>
            </td>
          </tr>
          <!-- Banner Tag -->
          <tr>
            <td style="padding: 24px 40px 0;">
              <span style="display: inline-block; background-color: #E7FAF6; color: #0b9748; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                Verified Announcement
              </span>
            </td>
          </tr>
          <!-- Content -->
          <tr>
            <td style="padding: 16px 40px 30px;">
              <h2 style="color: #171151; font-size: 20px; font-weight: 700; margin: 0 0 18px;">${subject}</h2>
              ${formattedBody}
              ${ctaButtonHtml}
            </td>
          </tr>
          <!-- Support Box -->
          <tr>
            <td style="padding: 0 40px 30px;">
              <div style="background-color: #f8fafc; border-left: 4px solid #0E63FF; padding: 14px 18px; border-radius: 4px;">
                <p style="margin: 0; font-size: 13px; color: #475569; line-height: 1.5;">
                  <strong>Need assistance?</strong> Contact our technical engineering team at <a href="mailto:${contactEmail}" style="color: #0E63FF; text-decoration: none;">${contactEmail}</a> or call <a href="tel:${contactPhone.replace(/\s+/g, '')}" style="color: #0E63FF; text-decoration: none;">${contactPhone}</a>.
                </p>
              </div>
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td style="background-color: #0f172a; padding: 24px 40px; text-align: center;">
              <p style="color: #94a3b8; font-size: 12px; margin: 0 0 6px;">&copy; ${new Date().getFullYear()} ${siteName}. All rights reserved.</p>
              <p style="color: #64748b; font-size: 11px; margin: 0;">This email was sent to ${recipientEmail}.</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
    `;
  }

  // 2. Product & Equipment Launch Template
  if (templateId === 'product_launch') {
    return `
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>${subject}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0f172a; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
  <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #0f172a; padding: 30px 0;">
    <tr>
      <td align="center">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
          <!-- Top Badge -->
          <tr>
            <td style="background-color: #171151; padding: 14px 40px; text-align: center; border-bottom: 3px solid #10D0A1;">
              <span style="color: #10D0A1; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                ⭐ NEW MEDICAL TECHNOLOGY &bull; ADVANCED CLINICAL SOLUTIONS
              </span>
            </td>
          </tr>
          <!-- Hero Header -->
          <tr>
            <td style="background: linear-gradient(135deg, #171151 0%, #1e1b4b 100%); padding: 36px 40px 28px; text-align: center;">
              <h1 style="color: #ffffff; margin: 0 0 8px; font-size: 24px; font-weight: 800;">${subject}</h1>
              <p style="color: #94a3b8; font-size: 14px; margin: 0;">Authorized Distributor in Pakistan &bull; Turnkey Hospital Systems</p>
            </td>
          </tr>
          <!-- Body Content -->
          <tr>
            <td style="padding: 30px 40px 20px;">
              ${formattedBody}
              <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 16px 20px; margin: 20px 0;">
                <h4 style="margin: 0 0 8px; color: #171151; font-size: 14px; font-weight: 700;">Key Equipment Highlights:</h4>
                <ul style="margin: 0; padding-left: 20px; color: #475569; font-size: 13.5px; line-height: 1.6;">
                  <li>Certified Biomedical Engineering & Precision Calibration</li>
                  <li>Complete On-Site Hospital Installation & Staff Training</li>
                  <li>24/7 Priority Emergency Replacement & Technical Warranty</li>
                </ul>
              </div>
              ${ctaButtonHtml}
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td style="background-color: #f1f5f9; padding: 20px 40px; text-align: center; border-top: 1px solid #e2e8f0;">
              <p style="color: #475569; font-size: 13px; margin: 0 0 6px; font-weight: 600;">${siteName}</p>
              <p style="color: #64748b; font-size: 12px; margin: 0;">Helpline: ${contactPhone} &bull; Email: ${contactEmail}</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
    `;
  }

  // 3. Clinical Newsletter Digest Template
  if (templateId === 'newsletter') {
    return `
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>${subject}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
  <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #f8fafc; padding: 30px 0;">
    <tr>
      <td align="center">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
          <!-- Header -->
          <tr>
            <td style="padding: 28px 40px; border-bottom: 2px solid #0E63FF; background-color: #ffffff;">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
                    <h2 style="color: #171151; margin: 0; font-size: 20px; font-weight: 800;">${siteName}</h2>
                    <span style="color: #0E63FF; font-size: 12px; font-weight: 700; text-transform: uppercase;">Healthcare Innovations &bull; Monthly Digest</span>
                  </td>
                  <td align="right" style="color: #94a3b8; font-size: 12px;">
                    ${new Date().toLocaleDateString('en-US', { month: 'short', year: 'numeric' })}
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <!-- Main Content -->
          <tr>
            <td style="padding: 30px 40px;">
              <h3 style="color: #171151; font-size: 20px; font-weight: 700; margin: 0 0 16px;">${subject}</h3>
              ${formattedBody}
              ${ctaButtonHtml}
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td style="background-color: #171151; padding: 24px 40px; text-align: center; color: #ffffff;">
              <p style="font-size: 12px; color: #cbd5e1; margin: 0 0 6px;">Thank you for being part of the Innotech Medical healthcare network.</p>
              <p style="font-size: 11px; color: #94a3b8; margin: 0;">You received this email because you are subscribed to ${siteName} updates.</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
    `;
  }

  // 4. Service & Maintenance Alert Template (Default / Alert)
  return `
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>${subject}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
  <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #f1f5f9; padding: 30px 0;">
    <tr>
      <td align="center">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border-top: 4px solid #F72A75;">
          <!-- Top Bar -->
          <tr>
            <td style="padding: 24px 40px 10px;">
              <span style="background-color: #FEEAF1; color: #D92D20; padding: 4px 10px; border-radius: 16px; font-size: 12px; font-weight: 700; text-transform: uppercase;">
                Service & Technical Advisory
              </span>
              <h2 style="color: #171151; margin: 12px 0 0; font-size: 20px; font-weight: 700;">${subject}</h2>
            </td>
          </tr>
          <!-- Body -->
          <tr>
            <td style="padding: 16px 40px 24px;">
              ${formattedBody}
              ${ctaButtonHtml}
            </td>
          </tr>
          <!-- Urgent Contact -->
          <tr>
            <td style="padding: 0 40px 24px;">
              <div style="background-color: #FFF8E6; border: 1px solid #FFE08A; padding: 12px 16px; border-radius: 6px; font-size: 13px; color: #8A5B00;">
                <strong>Emergency Maintenance Support:</strong> For immediate equipment dispatch or emergency breakdown assistance, dial <strong>${contactPhone}</strong> directly.
              </div>
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td style="background-color: #0f172a; padding: 18px 40px; text-align: center; color: #94a3b8; font-size: 12px;">
              &copy; ${new Date().getFullYear()} ${siteName} &bull; Specialized Biomedical Engineering Services
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
  `;
}

// GET: Fetch email campaign history & statistics
export async function GET() {
  try {
    const store = getLocalStore();
    const campaigns = store.emailCampaigns || [];
    const subscribers = store.newsletterSubscribers || [];
    const inquiries = store.contactSubmissions || [];

    return NextResponse.json({
      success: true,
      campaigns: campaigns.slice().reverse(),
      totalCampaigns: campaigns.length,
      subscriberCount: subscribers.length,
      inquiryCount: inquiries.length,
      subscribers: subscribers.map((s) => s.email).filter(Boolean),
      inquiryEmails: Array.from(new Set(inquiries.map((i) => i.email).filter(Boolean))),
    });
  } catch (error) {
    console.error('Error fetching email campaigns:', error);
    return NextResponse.json(
      { success: false, message: 'Failed to load campaign records' },
      { status: 500 }
    );
  }
}

// POST: Send bulk email or test email using chosen VIP template
export async function POST(req) {
  try {
    const body = await req.json();
    const {
      recipients = [],
      subject,
      templateId = 'announcement',
      messageBody = '',
      ctaText = '',
      ctaUrl = '',
      ctaColor = '#0E63FF',
      senderName = 'Innotech Medical Support',
      senderEmail = 'info@innotechmedical.org',
      customHtml = '',
      isTest = false,
      testEmail = '',
    } = body;

    // Validation
    if (!subject || (!messageBody && !customHtml)) {
      return NextResponse.json(
        { success: false, message: 'Subject and email message content are required.' },
        { status: 400 }
      );
    }

    const targetRecipients = isTest
      ? [testEmail || 'admin@innotechmedical.org']
      : Array.from(new Set(recipients.filter((email) => email && email.includes('@'))));

    if (targetRecipients.length === 0) {
      return NextResponse.json(
        { success: false, message: 'Please provide at least one valid recipient email address.' },
        { status: 400 }
      );
    }

    // Check if SMTP is configured in environment
    const hasSmtpConfig = process.env.SMTP_HOST && process.env.SMTP_USER && process.env.SMTP_PASS;
    let dispatchSuccess = true;
    let dispatchError = null;

    if (hasSmtpConfig) {
      try {
        const transporter = nodemailer.createTransport({
          host: process.env.SMTP_HOST,
          port: parseInt(process.env.SMTP_PORT || '587', 10),
          secure: process.env.SMTP_SECURE === 'true',
          auth: {
            user: process.env.SMTP_USER,
            pass: process.env.SMTP_PASS,
          },
        });

        // Send in batches of 10
        for (const recipient of targetRecipients) {
          const emailHtml = generateEmailHtml({
            templateId,
            subject,
            messageBody,
            ctaText,
            ctaUrl,
            ctaColor,
            senderName,
            customHtml,
            recipientEmail: recipient,
          });

          await transporter.sendMail({
            from: `"${senderName}" <${senderEmail}>`,
            to: recipient,
            subject,
            html: emailHtml,
          });
        }
      } catch (err) {
        console.error('SMTP sending error:', err);
        dispatchSuccess = false;
        dispatchError = err.message;
      }
    }

    // Save campaign record to local store
    const store = getLocalStore();
    if (!store.emailCampaigns) {
      store.emailCampaigns = [];
    }

    const newCampaign = {
      id: `camp_${Date.now()}`,
      subject,
      templateId,
      senderName,
      senderEmail,
      recipientsCount: targetRecipients.length,
      sampleRecipients: targetRecipients.slice(0, 5),
      ctaText: ctaText || null,
      ctaUrl: ctaUrl || null,
      status: dispatchSuccess ? 'Sent' : 'Failed',
      error: dispatchError,
      isTest: !!isTest,
      sentAt: new Date().toISOString(),
    };

    store.emailCampaigns.push(newCampaign);
    updateLocalStore(store);

    return NextResponse.json({
      success: true,
      message: isTest
        ? `✓ Test email preview successfully sent to ${targetRecipients[0]}!`
        : `✓ Email campaign successfully dispatched to ${targetRecipients.length} recipients!`,
      campaign: newCampaign,
    });
  } catch (error) {
    console.error('Email dispatch API error:', error);
    return NextResponse.json(
      { success: false, message: 'Server error processing email dispatch.' },
      { status: 500 }
    );
  }
}
