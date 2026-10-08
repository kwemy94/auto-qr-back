<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $contact->subject }}</title>
</head>
<body style="margin:0;padding:24px;background:#f4f4f4;font-family:Arial,sans-serif;color:#0D1F3C;">
    <div style="max-width:560px;margin:0 auto;background:#ffffff;border-radius:12px;padding:28px;">
        <h2 style="margin:0 0 16px;font-size:18px;">Nouveau message de contact — QR Notify</h2>
        <table style="font-size:14px;border-collapse:collapse;margin-bottom:20px;">
            <tr><td style="padding:4px 12px 4px 0;color:#666;">De</td><td>{{ $contact->email }}</td></tr>
            <tr><td style="padding:4px 12px 4px 0;color:#666;">Utilisateur</td><td>{{ $contact->user ? '#'.$contact->user->id.' — '.$contact->user->name : '—' }}</td></tr>
            <tr><td style="padding:4px 12px 4px 0;color:#666;">Langue</td><td>{{ $contact->locale ?? '—' }}</td></tr>
            <tr><td style="padding:4px 12px 4px 0;color:#666;">Objet</td><td>{{ $contact->subject }}</td></tr>
            <tr><td style="padding:4px 12px 4px 0;color:#666;">Reçu le</td><td>{{ $contact->created_at->format('d/m/Y H:i') }}</td></tr>
        </table>
        <div style="white-space:pre-wrap;font-size:14px;line-height:1.6;border-top:1px solid #eee;padding-top:16px;">{{ $contact->message }}</div>
    </div>
</body>
</html>
