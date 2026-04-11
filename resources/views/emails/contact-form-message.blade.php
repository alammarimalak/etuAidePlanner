<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>New contact message</title>
</head>
<body style="margin:0; padding:24px; background:#f5f7ff; font-family:Segoe UI, Arial, sans-serif; color:#111827;">
    <div style="max-width:680px; margin:0 auto; background:#ffffff; border:1px solid #dbe3ff; border-radius:20px; overflow:hidden;">
        <div style="padding:24px 28px; background:linear-gradient(135deg, #0b132d 0%, #2156f5 60%, #5d3ef0 100%); color:#ffffff;">
            <div style="font-size:12px; letter-spacing:.12em; text-transform:uppercase; opacity:.82;">EtuAide Contact</div>
            <h1 style="margin:12px 0 8px; font-size:28px; line-height:1.2;">New message from {{ $senderName }}</h1>
            <div style="font-size:14px; opacity:.86;">Reply to {{ $senderEmail }}</div>
        </div>

        <div style="padding:28px;">
            <p style="margin-top:0;"><strong>Name:</strong> {{ $senderName }}</p>
            <p><strong>Email:</strong> {{ $senderEmail }}</p>

            <div style="margin-top:24px;">
                <p style="margin:0 0 10px;"><strong>Message</strong></p>

                @foreach (preg_split("/(\r\n|\n|\r)/", $messageBody) as $paragraph)
                    @if (trim($paragraph) !== '')
                        <p style="margin:0 0 16px; line-height:1.7;">{{ $paragraph }}</p>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
