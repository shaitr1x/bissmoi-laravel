@props(['product', 'size' => 'medium', 'class' => ''])

@php
$sizeClasses = [
    'small' => 'w-12 h-12',
    'medium' => 'w-48 h-48',
    'large' => 'w-full h-64',
    'xl' => 'w-full h-96'
];

$imageClass = $sizeClasses[$size] ?? $sizeClasses['medium'];
@endphp

<div class="relative {{ $imageClass }} {{ $class }}">
    @if($product->image)
        <img src="{{ $product->image_url }}" 
             alt="{{ $product->name }}" 
             class="w-full h-full object-cover rounded-lg"
             onerror="this.src='{{ asset('images/default-product.svg') }}'; this.onerror=null;">
    @else
        <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
            <svg class="w-1/3 h-1/3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
    @endif
    
    @if($product->featured)
        <div class="absolute top-2 left-2">
            <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full font-semibold">
                ⭐ Vedette
            </span>
        </div>
    @endif
    
    @if($product->sale_price)
        <div class="absolute top-2 right-2">
            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full font-semibold">
                Promo
            </span>
        </div>
    @endif
</div>
