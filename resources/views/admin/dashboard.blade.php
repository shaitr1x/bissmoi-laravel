<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Admin - Bissmoi') }}
            </h2>
            <!-- Cloche de notification retirée -->
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-500">Total Utilisateurs</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                            </div>
                            <div class="text-blue-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-500">Commerçants</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_merchants'] }}</p>
                                @if($stats['pending_merchants'] > 0)
                                    <p class="text-sm text-orange-500">{{ $stats['pending_merchants'] }} en attente</p>
                                @endif
                            </div>
                            <div class="text-green-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-500">Produits</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
                                @if($stats['pending_products'] > 0)
                                    <p class="text-sm text-orange-500">{{ $stats['pending_products'] }} en attente</p>
                                @endif
                            </div>
                            <div class="text-purple-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-500">Commandes</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
                            </div>
                            <div class="text-yellow-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Dernières commandes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Dernières Commandes</h3>
                        <div class="space-y-3">
                            @forelse($recentOrders as $order)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                    <div>
                                        <p class="font-medium">#{{ $order->order_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->user?->shop_name ?? $order->user?->name ?? 'Utilisateur inconnu' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium">{{ number_format($order->total_amount, 2) }} FCFA</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $order->status === 'pending' ? 'yellow' : 'green' }}-100 text-{{ $order->status === 'pending' ? 'yellow' : 'green' }}-800">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500">Aucune commande récente</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Produits en attente -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Produits en Attente de Validation</h3>
                        <div class="space-y-3">
                            @forelse($pendingProducts as $product)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                    <div>
                                        <p class="font-medium">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-600">par {{ $product->user?->shop_name ?? $product->user?->name ?? 'Utilisateur inconnu' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium">{{ number_format($product->price, 2) }} FCFA</p>
                                        <p class="text-sm text-gray-600">{{ $product->category->name }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500">Aucun produit en attente</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="mt-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions Rapides</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <a href="{{ route('admin.merchant_verification_requests') }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                                <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 17v1a3 3 0 11-6 0v-1m6 0H9m6 0V9a4 4 0 10-8 0v8m8 0a4 4 0 01-8 0" />
                                </svg>
                                <div>
                                    <p class="font-medium flex items-center">Demandes de badge
                                        @php
                                            $pendingBadgeCount = \App\Models\MerchantVerificationRequest::where('status', 'pending')->count();
                                        @endphp
                                        @if($pendingBadgeCount > 0)
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-600 text-white">{{ $pendingBadgeCount }}</span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        @if($pendingBadgeCount > 0)
                                            {{ $pendingBadgeCount }} personne{{ $pendingBadgeCount > 1 ? 's' : '' }} en attente
                                        @else
                                            Aucune demande en attente
                                        @endif
                                    </p>
                                </div>
                            </a>

                            <a href="{{ route('admin.merchants') }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition">
                                <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <div>
                                    <p class="font-medium">Gérer les Commerçants</p>
                                    <p class="text-sm text-gray-600">{{ $stats['pending_merchants'] }} en attente</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.products.index') }}" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                                <svg class="w-8 h-8 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <div>
                                    <p class="font-medium">Modérer les Produits</p>
                                    <p class="text-sm text-gray-600">{{ $stats['pending_products'] }} en attente</p>
                                </div>
                            </a>

                            <a href="{{ route('remerciements.admin') }}" class="flex items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition">
                                <svg class="w-8 h-8 text-yellow-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8M12 8v8" />
                                </svg>
                                <div>
                                    <p class="font-medium">Remerciements</p>
                                    <p class="text-sm text-gray-600">Gérer les remerciements & fondateurs</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.settings.shipping') }}" class="flex items-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                                <svg class="w-8 h-8 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                                </svg>
                                <div>
                                    <p class="font-medium">Frais de Livraison</p>
                                    <p class="text-sm text-gray-600">Configurer les frais de livraison du site</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.settings.payment') }}" class="flex items-center p-4 bg-pink-50 hover:bg-pink-100 rounded-lg transition">
                                <svg class="w-8 h-8 text-pink-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2M5 9h14M5 9v10a2 2 0 002 2h10a2 2 0 002-2V9"></path>
                                </svg>
                                <div>
                                    <p class="font-medium">Paiement Mobile</p>
                                    <p class="text-sm text-gray-600">Activer/désactiver MTN & Orange</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
