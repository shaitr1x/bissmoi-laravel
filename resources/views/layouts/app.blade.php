<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <meta name="theme-color" content="#9DAAF2">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Scripts additionnels -->
        @auth
            <script>
                // Mettre à jour le compteur du panier
                function updateCartCount() {
                    fetch('{{ route("cart.count") }}')
                        .then(response => response.json())
                        .then(data => {
                            // Desktop
                            const cartCountEl = document.getElementById('cartCount');
                            if (cartCountEl) {
                                if (data.count > 0) {
                                    cartCountEl.textContent = data.count;
                                    cartCountEl.classList.remove('hidden');
                                } else {
                                    cartCountEl.classList.add('hidden');
                                }
                            }

                            // Mobile bottom nav
                            const bottomCartCount = document.getElementById('bottomCartCount');
                            if (bottomCartCount) {
                                if (data.count > 0) {
                                    bottomCartCount.textContent = data.count > 99 ? '99+' : data.count;
                                    bottomCartCount.classList.remove('hidden');
                                    bottomCartCount.style.display = 'flex';
                                } else {
                                    bottomCartCount.classList.add('hidden');
                                    bottomCartCount.style.display = 'none';
                                }
                            }
                        })
                        .catch(error => console.error('Erreur:', error));
                }

                // Mettre à jour au chargement de la page
                document.addEventListener('DOMContentLoaded', function() {
                    updateCartCount();
                });

                // Mettre à jour toutes les 30 secondes
                setInterval(updateCartCount, 30000);
            </script>
        @endauth

        <!-- PWA Install Popup & Service Worker -->
        <script>
            let deferredPrompt;
            let installBtn = null;

            // Écouter l'événement beforeinstallprompt
            window.addEventListener('beforeinstallprompt', (e) => {
                console.log('PWA: beforeinstallprompt event fired');
                e.preventDefault();
                deferredPrompt = e;
                showInstallPopup();
            });

            // Fonction pour afficher le popup d'installation
            function showInstallPopup() {
                // Créer le popup
                const popup = document.createElement('div');
                popup.id = 'pwa-install-popup';
                popup.innerHTML = `
                    <div style="position: fixed; top: 30px; right: 30px; width: 350px; background: white; z-index: 9999; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); text-align: center; padding: 24px;">
                        <div style="width: 48px; height: 48px; background: #9DAAF2; border-radius: 50%; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                            <span style="color: #1A2238; font-size: 22px; font-weight: bold;">B</span>
                        </div>
                        <h3 style="margin: 0 0 10px 0; color: #1A2238; font-size: 18px;">Installer BISSMOI</h3>
                        <p style="margin: 0 0 18px 0; color: #666; font-size: 13px;">Installez l'application BISSMOI sur votre appareil pour un accès rapide et une meilleure expérience.</p>
                        <div style="display: flex; gap: 10px; justify-content: center;">
                            <button id="install-pwa-btn" style="background: #9DAAF2; color: white; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer; font-weight: bold;">Installer</button>
                            <button id="dismiss-pwa-btn" style="background: #ccc; color: #666; border: none; padding: 8px 16px; border-radius: 5px; cursor: pointer;">Plus tard</button>
                        </div>
                    </div>
                `;
                document.body.appendChild(popup);

                // Gérer les clics
                document.getElementById('install-pwa-btn').addEventListener('click', () => {
                    if (deferredPrompt) {
                        deferredPrompt.prompt();
                        deferredPrompt.userChoice.then((choiceResult) => {
                            console.log('PWA: User choice:', choiceResult.outcome);
                            deferredPrompt = null;
                            popup.remove();
                        });
                    }
                });

                document.getElementById('dismiss-pwa-btn').addEventListener('click', () => {
                    popup.remove();
                    // Ne plus afficher pendant cette session
                    sessionStorage.setItem('pwa-dismissed', 'true');
                });
            }

            // Enregistrer le service worker
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then((registration) => {
                            console.log('PWA: Service Worker registered successfully');
                        })
                        .catch((error) => {
                            console.log('PWA: Service Worker registration failed');
                        });
                });
            }

            // Écouter l'installation réussie
            window.addEventListener('appinstalled', (evt) => {
                console.log('PWA: App installed successfully');
            });
        </script>
    </head>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                <!-- Messages de session -->
                @if(session('success'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Fermer</title>
                                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Fermer</title>
                                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                @endif

                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot ?? '' }}
                @endif
            </main>

            <!-- Navigation bottom mobile -->
            <x-mobile-bottom-nav />
        </div>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </body>
</html>
