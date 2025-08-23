<!-- Modal de paiement CinetPay -->
<div id="cinetpay-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Paiement électronique CinetPay
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 mb-4">
                                Vous allez être redirigé vers CinetPay pour effectuer votre paiement sécurisé.
                            </p>
                            
                            <!-- Informations de commande -->
                            <div class="bg-gray-50 p-3 rounded-lg mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-700">Commande:</span>
                                    <span class="text-sm text-gray-900">{{ $order->order_number }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">Montant total:</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ number_format($order->total_amount + $order->shipping_fee) }} FCFA</span>
                                </div>
                            </div>

                            <!-- Méthodes de paiement acceptées -->
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 mb-2">Méthodes acceptées:</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-2 py-1 text-xs bg-orange-100 text-orange-800 rounded">MTN Money</span>
                                    <span class="px-2 py-1 text-xs bg-orange-100 text-orange-800 rounded">Orange Money</span>
                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">Visa</span>
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">MasterCard</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" 
                        onclick="initiateCinetPayPayment()" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Procéder au paiement
                </button>
                <button type="button" 
                        onclick="closeCinetPayModal()" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openCinetPayModal() {
    document.getElementById('cinetpay-modal').classList.remove('hidden');
}

function closeCinetPayModal() {
    document.getElementById('cinetpay-modal').classList.add('hidden');
}

function initiateCinetPayPayment() {
    // Afficher un loader
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Traitement...';
    button.disabled = true;

    // Appel AJAX pour initier le paiement CinetPay
    fetch('{{ route("cinetpay.initiate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            order_id: {{ $order->id }}
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirection vers la page de paiement CinetPay
            window.location.href = data.payment_url;
        } else {
            // Afficher l'erreur
            alert('Erreur: ' + (data.error || 'Une erreur est survenue'));
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur de communication. Veuillez réessayer.');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Auto-ouvrir le modal si demandé
@if(session('initiate_payment'))
document.addEventListener('DOMContentLoaded', function() {
    openCinetPayModal();
});
@endif
</script>
