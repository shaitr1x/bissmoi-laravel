<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notification - {{ $title }}</title>
</head>
<body>
    <h2>Bonjour {{ $merchant->name }},</h2>
    <h3>{{ $title }}</h3>
        <p>{{ is_string($message) ? $message : (method_exists($message, '__toString') ? (string)$message : '') }}</p>
    <p>Vous pouvez accéder à votre espace marchand pour plus de détails.</p>
    <p>— L'équipe Bissmoi</p>
</body>
</html>
