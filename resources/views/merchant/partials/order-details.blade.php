<div>
    <h4 class="text-md font-bold mb-2">Commande #{{ $order->id }}</h4>
    <p><span class="font-semibold">Client :</span> {{ $order->user->name ?? 'N/A' }}</p>
    <p><span class="font-semibold">Date :</span> {{ $order->created_at->format('d/m/Y H:i') }}</p>
    <p><span class="font-semibold">Statut :</span> 
        @switch($order->status)
            @case('pending') En attente @break
            @case('processing') En cours @break
            @case('shipped') Expédiée @break
            @case('delivered') Livrée @break
            @case('cancelled') Annulée @break
            @default {{ ucfirst($order->status) }}
        @endswitch
    </p>
    <hr class="my-2">
    <h5 class="font-semibold mb-1">Produits :</h5>
    <ul class="mb-2">
        @foreach($order->items as $item)
            @if($item->product && $item->product->user_id === auth()->id())
            <li class="mb-1">
                {{ $item->product->name }} x {{ $item->quantity }}<br>
                <span class="text-xs text-gray-500">Prix : {{ number_format($item->price, 2) }} FCFA</span>
            </li>
            @endif
        @endforeach
    </ul>
    <p><span class="font-semibold">Total :</span> {{ number_format($order->items->where('product.user_id', auth()->id())->sum(function($item) { return $item->price * $item->quantity; }), 2, ',', ' ') }} FCFA</p>
</div>
