<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notification - {{ $title }}</title>
</head>
<body>
    <h2>Bonjour {{ $client->name }},</h2>
    <h3>{{ $title }}</h3>
    <p>{{ $message }}</p>
    <p>Vous pouvez consulter votre compte pour plus de détails.</p>
    <p>— L'équipe Bissmoi</p>
</body>
</html>
