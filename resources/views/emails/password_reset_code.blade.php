<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('mobile.reset_mail_subject') }}</title>
</head>
<body style="margin:0;padding:24px;background:#0D1F3C;font-family:Arial,sans-serif;color:#F0E8D5;">
    <div style="max-width:420px;margin:0 auto;background:#14294A;border:1px solid rgba(201,150,42,0.3);border-radius:16px;padding:32px;text-align:center;">
        <div style="color:#C9962A;font-size:13px;font-weight:700;letter-spacing:4px;margin-bottom:20px;">QR NOTIFY</div>
        <p style="font-size:15px;line-height:1.6;margin:0 0 24px;">{{ __('mobile.reset_mail_intro') }}</p>
        <div style="display:inline-block;background:#F0E8D5;color:#0D1F3C;font-size:32px;font-weight:700;letter-spacing:8px;padding:14px 24px;border-radius:12px;">{{ $code }}</div>
        <p style="font-size:13px;color:rgba(240,232,213,0.6);margin:24px 0 8px;">{{ __('mobile.reset_mail_expiry', ['minutes' => $expiresInMinutes]) }}</p>
        <p style="font-size:12px;color:rgba(240,232,213,0.4);margin:0;">{{ __('mobile.reset_mail_ignore') }}</p>
    </div>
</body>
</html>
