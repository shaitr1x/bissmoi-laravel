<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifications Admin') }}
            </h2>
            
            <div class="flex space-x-3">
                <form action="{{ route('admin.notifications.index') }}" method="GET" class="flex items-center space-x-2">
                    <select name="type" onchange="this.form.submit()" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Tous les types</option>
                        <option value="info" {{ request('type') === 'info' ? 'selected' : '' }}>Info</option>
                        <option value="success" {{ request('type') === 'success' ? 'selected' : '' }}>Succès</option>
                        <option value="warning" {{ request('type') === 'warning' ? 'selected' : '' }}>Avertissement</option>
                        <option value="error" {{ request('type') === 'error' ? 'selected' : '' }}>Erreur</option>
                    </select>
                </form>
                
                <button onclick="markAllAsRead()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                    Tout marquer comme lu
                </button>
            </div>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-800">Total</p>
                            <p class="text-lg font-semibold text-blue-600">{{ $notifications->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-red-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-red-500 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">Non lues</p>
                            <p class="text-lg font-semibold text-red-600">{{ $notifications->where('is_read', false)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-500 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">Lues</p>
                            <p class="text-lg font-semibold text-green-600">{{ $notifications->where('is_read', true)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-500 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-yellow-800">Récentes</p>
                            <p class="text-lg font-semibold text-yellow-600">{{ $notifications->where('created_at', '>=', now()->subDays(7))->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des notifications -->
            <div class="space-y-4">
                @forelse($notifications as $notification)
                    <div class="border rounded-lg p-4 {{ $notification->is_read ? 'bg-gray-50' : 'bg-white border-l-4 border-l-blue-500' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-3">
                                <!-- Icône du type de notification -->
                                <div class="flex-shrink-0">
                                    @if($notification->type === 'success')
                                        <div class="p-2 bg-green-100 rounded-full">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    @elseif($notification->type === 'warning')
                                        <div class="p-2 bg-yellow-100 rounded-full">
                                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                    @elseif($notification->type === 'error')
                                        <div class="p-2 bg-red-100 rounded-full">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="p-2 bg-blue-100 rounded-full">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Contenu -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <h3 class="text-sm font-medium text-gray-900">{{ $notification->title }}</h3>
                                        @if(!$notification->is_read)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 notif-new-badge">
                                                Nouveau
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600">{{ $notification->message }}</p>
                                    <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                        <span>{{ $notification->created_at->format('d/m/Y à H:i') }}</span>
                                        <span>{{ $notification->created_at->diffForHumans() }}</span>
                                        @if($notification->is_read)
                                            <span>Lu le {{ $notification->read_at->format('d/m/Y à H:i') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center space-x-2 ml-4">
                                @if(!$notification->is_read)
                                    <form action="{{ route('admin.notifications.read', $notification) }}" method="POST" class="notif-read-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="text-blue-600 hover:text-blue-900 text-sm font-medium"
                                                title="Marquer comme lu">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
    <script>
    // Retire le badge et le compteur après marquage comme lu sans rechargement
    document.querySelectorAll('.notif-read-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const notifCard = form.closest('.border');
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new URLSearchParams(new FormData(form))
            }).then(resp => {
                if (resp.ok) {
                    notifCard.classList.add('bg-gray-50');
                    notifCard.classList.remove('bg-white', 'border-l-4', 'border-l-blue-500');
                    const badge = notifCard.querySelector('.notif-new-badge');
                    if (badge) badge.remove();
                    form.remove();
                    // Mettre à jour le compteur si présent
                    const notifCount = document.querySelector('.bg-red-50 .text-lg.font-semibold.text-red-600');
                    if (notifCount) {
                        let count = parseInt(notifCount.textContent.trim());
                        if (!isNaN(count) && count > 0) notifCount.textContent = count - 1;
                    }
                }
            });
        });
    });
    </script>
                                
                                <form action="{{ route('admin.notifications.delete', $notification) }}" method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 text-sm font-medium"
                                            title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune notification</h3>
                        <p class="mt-1 text-sm text-gray-500">Vous n'avez aucune notification pour le moment.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function markAllAsRead() {
            if (confirm('Êtes-vous sûr de vouloir marquer toutes les notifications comme lues ?')) {
                // Créer un formulaire temporaire pour marquer toutes les notifications comme lues
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.notifications.index") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PATCH';
                
                const actionField = document.createElement('input');
                actionField.type = 'hidden';
                actionField.name = 'action';
                actionField.value = 'mark_all_read';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                form.appendChild(actionField);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    @endpush
</x-admin-layout>
