<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nouvel utilisateur inscrit - BISSMOI</title>
</head>
<body>
    <h2>Nouvel utilisateur inscrit sur Bissmoi</h2>
    <p><strong>Nom :</strong> {{ $user->name }}</p>
    <p><strong>Email :</strong> {{ $user->email }}</p>
    <p><strong>Ville :</strong> {{ $user->city ?? 'Non spécifiée' }}</p>
    @if($user->created_at)
        <p><strong>Date d'inscription :</strong> {{ $user->created_at->format('d/m/Y à H:i') }}</p>
    @endif
    <p>— L'équipe Bissmoi</p>
</body>
</html>
