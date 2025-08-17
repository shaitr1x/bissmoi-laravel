<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }} - BISSMOI</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f8f8fa; margin:0; padding:0;">
    <div style="max-width:600px; margin:40px auto; background:#fff; border-radius:8px; box-shadow:0 2px 8px #eee; padding:32px;">
        <div style="text-align:center; margin-bottom:24px;">
            <img src="{{ $logoUrl }}" alt="BISSMOI" style="height:60px; margin-bottom:20px;">
        </div>
        <h2 style="color:#2d3748; text-align:center; margin-bottom:24px;">{{ $title }}</h2>
        <p style="font-size:16px; color:#444; margin-bottom:24px;">Bonjour {{ $userName }},</p>
    <p style="font-size:16px; color:#444; margin-bottom:32px;">{!! nl2br((string) $message) !!}</p>
        @if($actionUrl && $actionText)
            <div style="text-align:center; margin-bottom:32px;">
                <a href="{{ $actionUrl }}" style="display:inline-block; background:#2563eb; color:#fff; padding:12px 32px; border-radius:6px; text-decoration:none; font-weight:bold;">{{ $actionText }}</a>
            </div>
        @endif
        <hr style="margin:32px 0; border:none; border-top:1px solid #eee;">
        <p style="font-size:13px; color:#888; text-align:center;">Ceci est un message automatique de la plateforme BISSMOI.<br>© {{ date('Y') }} Bissmoi. Tous droits réservés.</p>
    </div>
</body>
</html>
