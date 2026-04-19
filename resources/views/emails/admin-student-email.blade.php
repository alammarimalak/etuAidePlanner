<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $subjectLine }}</title>
</head>
@php($logoSrc = $message->embed(storage_path('app/public/EtuAide_darkmode.png')))
<body style="margin:0; padding:24px; background:#f5f7ff; font-family:Segoe UI, Arial, sans-serif; color:#111827;">
    <div style="max-width:680px; margin:0 auto; background:#ffffff; border:1px solid #dbe3ff; border-radius:20px; overflow:hidden;">
        <div style="padding:24px 28px; background:linear-gradient(135deg, #0b132d 0%, #2156f5 60%, #5d3ef0 100%); color:#ffffff;">
            <div style="margin-bottom:16px;">
                <img src="{{ $logoSrc }}" alt="EtuAide" height="32" style="display:block;">
            </div>
            <div style="font-size:12px; letter-spacing:.12em; text-transform:uppercase; opacity:.82;">EtuAide Admin Email</div>
            <h1 style="margin:12px 0 8px; font-size:28px; line-height:1.2;">{{ $subjectLine }}</h1>
            <div style="font-size:14px; opacity:.86;">From {{ $senderEmail }}</div>
        </div>

        <div style="padding:28px;">
            <p style="margin-top:0;">Hello {{ $recipientName }},</p>

            @foreach (preg_split("/(\r\n|\n|\r)/", $messageBody) as $paragraph)
                @if (trim($paragraph) !== '')
                    <p style="margin:0 0 16px; line-height:1.7;">{{ $paragraph }}</p>
                @endif
            @endforeach

            <p style="margin:24px 0 0; line-height:1.7;">Best regards,<br>{{ $senderEmail }}</p>
        </div>
    </div>
</body>
</html>
