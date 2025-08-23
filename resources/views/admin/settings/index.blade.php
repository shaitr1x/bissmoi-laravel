<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Paramètres Système') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <!-- Informations système -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations système</h3>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    <!-- Option inscription avec choix du rôle -->
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                    <!-- Bloc Choix du rôle à l'inscription supprimé -->
        <!-- Actions système -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Actions système</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom du site</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $settings['site_name'] }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Version Laravel</label>
                        <p class="mt-1 text-sm text-gray-900">{{ app()->version() }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Version PHP</label>
                        <p class="mt-1 text-sm text-gray-900">{{ phpversion() }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Environnement</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1
                            {{ app()->environment('production') ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ strtoupper(app()->environment()) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mode maintenance</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1
                            {{ $settings['maintenance_mode'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $settings['maintenance_mode'] ? 'Activé' : 'Désactivé' }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Espace de stockage</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $settings['storage_usage']['total'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions système -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Actions système</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Mode maintenance -->
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="p-2 bg-yellow-100 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900">Mode maintenance</h4>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">
                            {{ $settings['maintenance_mode'] ? 'Désactiver' : 'Activer' }} le mode maintenance pour effectuer des mises à jour.
                        </p>
                        <form action="{{ route('admin.settings.maintenance') }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="{{ $settings['maintenance_mode'] ? 'disable' : 'enable' }}">
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest 
                                    {{ $settings['maintenance_mode'] ? 'bg-green-600 hover:bg-green-700' : 'bg-yellow-600 hover:bg-yellow-700' }}"
                                    onclick="return confirm('Êtes-vous sûr de vouloir {{ $settings['maintenance_mode'] ? 'désactiver' : 'activer' }} le mode maintenance ?')">
                                {{ $settings['maintenance_mode'] ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                    </div>

                    <!-- Nettoyer le cache -->
                        <!-- Télécharger le sitemap -->
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16V4H4zm4 8h8m-4-4v8" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-gray-900">Sitemap Google</h4>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">
                                Téléchargez le fichier sitemap.xml à soumettre à Google Search Console.
                            </p>
                            <a href="{{ url('/sitemap.xml') }}" target="_blank" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-blue-600 hover:bg-blue-700">
                                Télécharger le sitemap
                            </a>
                        </div>
                        <!-- Emailing admin -->
                        <div class="border rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12v1m0 4v1m-8-5v1m0 4v1m8-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900">Emailing commerçants</h4>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">
                                Envoyez un email à tous les commerçants inscrits sur la plateforme.
                            </p>
                            <a href="{{ route('admin.emailing') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest bg-green-600 hover:bg-green-700">
                                Accéder à l'emailing
                            </a>
                        </div>
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900">Nettoyer le cache</h4>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">
                            Vider tous les caches du système (config, routes, vues).
                        </p>
                        <form action="{{ route('admin.settings.clear-cache') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"
                                    onclick="return confirm('Êtes-vous sûr de vouloir vider le cache ?')">
                                Nettoyer
                            </button>
                        </form>
                    </div>

                    <!-- Optimiser le système -->
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900">Optimiser</h4>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">
                            Optimiser les performances en mettant en cache la configuration et les routes.
                        </p>
                        <form action="{{ route('admin.settings.optimize') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Optimiser
                            </button>
                        </form>
                    </div>

                    <!-- Maintenance automatique -->
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900">Nettoyage auto</h4>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">
                            Effectuer un nettoyage automatique du système (logs anciens, notifications).
                        </p>
                        <form action="{{ route('admin.settings.cleanup') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700">
                                Exécuter
                            </button>
                        </form>
                    </div>

                    <!-- Sauvegardes -->
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="p-2 bg-indigo-100 rounded-lg">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900">Sauvegarde</h4>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">
                            Créer une sauvegarde complète du système et de la base de données.
                        </p>
                        <form action="{{ route('admin.settings.backup') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Sauvegarder
                            </button>
                        </form>
                    </div>

                    <!-- Statistiques -->
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="p-2 bg-red-100 rounded-lg">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900">Statistiques</h4>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">
                            Voir les statistiques détaillées du système.
                        </p>
                        <button type="button" onclick="fetchSystemStats()" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                            Afficher
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Utilisation du stockage -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Utilisation du stockage</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $settings['storage_usage']['total'] }}</div>
                        <div class="text-sm text-gray-500">Total utilisé</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $settings['storage_usage']['app'] }}</div>
                        <div class="text-sm text-gray-500">Fichiers app</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $settings['storage_usage']['logs'] }}</div>
                        <div class="text-sm text-gray-500">Logs système</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function fetchSystemStats() {
            fetch("{{ route('admin.settings.stats') }}")
                .then(response => response.json())
                .then(data => {
                    alert(`📊 Statistiques du système:\n\n👥 Utilisateurs: ${data.users}\n🛍️ Produits: ${data.products}\n🛒 Commandes: ${data.orders}\n🔔 Notifications: ${data.notifications}\n💾 Espace libre: ${data.free_space}`);
                });
        }
    </script>
    @endpush
</x-admin-layout>
