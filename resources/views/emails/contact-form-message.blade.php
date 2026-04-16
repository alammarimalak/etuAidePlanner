<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>New contact message</title>
</head>
@php($logoSrc = $message->embed(storage_path('app/public/EtuAide_lightmode.png')))
<body style="margin:0; padding:32px 16px; background-color:#EEF3FF; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color:#111827; line-height:1.6;">

    <div style="text-align:center; margin-bottom:24px;">
        <div style="display:inline-flex; align-items:center; gap:8px; font-size:18px; font-weight:600; color:#1a1a2e;">
            <img src="{{ $logoSrc }}" alt="EtuAide" height="32" style="vertical-align:middle;">
        </div>
    </div>

    <div style="max-width:580px; margin:0 auto; background:#ffffff; border:1px solid #d4deff; border-radius:10px; overflow:hidden;">

        <div style="padding:32px 32px 28px; border-bottom:1px solid #e8edff;">
            <div style="font-size:13px; font-weight:600; color:#8a9cc4; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:10px;">
                EtuAide Contact Form
            </div>
            <h1 style="margin:0; font-size:22px; font-weight:700; color:#111827; line-height:1.35;">
                Message received from {{ $senderName }}
            </h1>
        </div>

        <div style="padding:28px 32px;">

            <table width="100%" cellpadding="0" cellspacing="0" style="font-size:15px; margin-bottom:24px;">
                <tr>
                    <td style="padding-bottom:10px; width:60px; color:#6b7280; font-weight:500;">Name:</td>
                    <td style="padding-bottom:10px; font-weight:600; color:#111827;">{{ $senderName }}</td>
                </tr>
                <tr>
                    <td style="padding-bottom:4px; color:#6b7280; font-weight:500;">Email:</td>
                    <td style="padding-bottom:4px; font-weight:600;">
                        <a href="mailto:{{ $senderEmail }}" style="color:#2156f5; text-decoration:none;">{{ $senderEmail }}</a>
                    </td>
                </tr>
            </table>

            <a href="mailto:{{ $senderEmail }}" style="display:block; background:#2156f5; color:#ffffff; text-align:center; padding:14px 24px; border-radius:6px; font-size:15px; font-weight:600; text-decoration:none; margin-bottom:28px;">
                Reply to {{ $senderName }}
            </a>

            <hr style="border:none; border-top:1px solid #e8edff; margin:0 0 24px;">

            <div style="font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:12px;">
                Message
            </div>
            <div style="background:#f5f7ff; border:1px solid #d4deff; border-radius:6px; padding:20px; font-size:15px; color:#374151; line-height:1.7;">
                @foreach (preg_split("/(\r\n|\n|\r)/", $messageBody) as $paragraph)
                    @if (trim($paragraph) !== '')
                        <p style="margin:0 0 16px;">{{ $paragraph }}</p>
                    @endif
                @endforeach
            </div>

        </div>

        <div style="padding:20px 32px; background:#fafbff; border-top:1px solid #e8edff; text-align:center;">
            <p style="margin:0; font-size:13px; color:#9ca3af;">
                This is an automated message sent from the EtuAide platform.
            </p>
        </div>

    </div>

    <div style="text-align:center; margin-top:20px;">
        <p style="font-size:12px; color:#9ca3af; margin:0;">&copy; 2026 EtuAide &middot; All rights reserved</p>
    </div>

</body>
</html>
