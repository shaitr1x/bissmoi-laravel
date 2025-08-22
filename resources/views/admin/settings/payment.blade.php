<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Paramètres Paiement Mobile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="mb-4 p-4 text-green-700 bg-green-100 border border-green-300 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Activer/Désactiver le Paiement Mobile</h3>
                        <p class="text-sm text-gray-600">
                            Permet d'activer ou de désactiver l'option de paiement mobile (MTN, Orange) sur le site.
                        </p>
                    </div>
                    <form action="{{ route('admin.settings.payment.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-6">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="mobile_payment_enabled" value="1" class="form-checkbox h-5 w-5 text-indigo-600" {{ $mobilePaymentEnabled ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700 font-medium">Paiement mobile (MTN, Orange)</span>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Retour au Dashboard
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
