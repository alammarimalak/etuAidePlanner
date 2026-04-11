<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>New contact message</title>
</head>
<body style="margin:0; padding:32px 16px; background-color:#f5f7ff; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color:#111827; line-height:1.6;">
    
    <div style="max-width:600px; margin:0 auto; background-color:#ffffff; border:1px solid #dbe3ff; border-radius:8px; box-shadow:0 4px 6px -1px rgba(0, 0, 0, 0.05);">

        <div style="padding:32px; background:linear-gradient(135deg, #5074ea 0%, #2156f5 60%, #7c66ea 100%); color:#ffffff; border-radius:8px 8px 0 0;">
            <div style="font-size:12px; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; color:#dbe3ff; margin-bottom:8px;">
                EtuAide Contact Form
            </div>
            <h1 style="margin:0; font-size:24px; font-weight:600; line-height:1.3;">
                Message received from {{ $senderName }}
            </h1>
        </div>

        <div style="padding:32px;">
            
            <div style="margin-bottom:28px;">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-size:15px;">
                    <tr>
                        <td style="padding-bottom:12px; width:70px; color:#4b5563; font-weight:500;">Name:</td>
                        <td style="padding-bottom:12px; font-weight:600; color:#111827;">{{ $senderName }}</td>
                    </tr>
                    <tr>
                        <td style="padding-bottom:12px; width:70px; color:#4b5563; font-weight:500;">Email:</td>
                        <td style="padding-bottom:12px; font-weight:600;">
                            <a href="mailto:{{ $senderEmail }}" style="color:#2156f5; text-decoration:none;">{{ $senderEmail }}</a>
                        </td>
                    </tr>
                </table>
            </div>

            <hr style="border:none; border-top:1px solid #dbe3ff; margin:0 0 28px 0;">

            <div>
                <h2 style="margin:0 0 16px; font-size:14px; font-weight:600; color:#4b5563; text-transform:uppercase; letter-spacing:0.05em;">
                    Message Details
                </h2>

                <div style="background-color:#f5f7ff; padding:24px; border-radius:6px; border:1px solid #dbe3ff; color:#374151; font-size:15px;">
                    @foreach (preg_split("/(\r\n|\n|\r)/", $messageBody) as $paragraph)
                        @if (trim($paragraph) !== '')
                            <p style="margin:0 0 16px; line-height:1.7;">{{ $paragraph }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
            
        </div>

        <div style="padding:24px 32px; background-color:#fafafa; border-top:1px solid #dbe3ff; border-radius:0 0 8px 8px; text-align:center;">
            <p style="margin:0; font-size:13px; color:#6b7280;">
                This is an automated message sent from the EtuAide platform.
            </p>
        </div>

    </div>

</body>
</html>