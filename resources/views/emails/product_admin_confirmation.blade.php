<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmation/Rejet produit - BISSMOI</title>
</head>
<body>
    <h2>Nouveau produit à valider</h2>
    <p>Un commerçant a ajouté un nouveau produit sur BISSMOI :</p>
    <ul>
        <li><strong>Commerçant :</strong> {{ $merchant->name }} ({{ $merchant->email }})</li>
        <li><strong>Boutique :</strong> {{ $merchant->shop_name }}</li>
        <li><strong>Produit :</strong> {{ $product->name }}</li>
        <li><strong>Description :</strong> {{ $product->description }}</li>
        <li><strong>Prix :</strong> {{ $product->price }} FCFA</li>
    </ul>
    <p>
        <a href="{{ url('/admin/products/'.$product->id.'/confirm') }}" style="background:#38b000;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;">Confirmer</a>
        <a href="{{ url('/admin/products/'.$product->id.'/reject') }}" style="background:#d90429;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;margin-left:10px;">Rejeter</a>
    </p>
    <p>— L'équipe Bissmoi</p>
</body>
</html>
