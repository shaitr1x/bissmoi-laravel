<!-- Modal Paiement Campay -->
<div id="campay-payment-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Paiement Mobile</h3>
                <button type="button" onclick="closeCampayModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Contenu -->
            <div id="payment-content">
                <div class="text-center mb-4">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">Montant à payer :</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de téléphone</label>
                    <input type="tel" 
                           id="payment-phone" 
                           value="{{ session('payment_phone', '') }}"
                           placeholder="237670123456"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Format : 237XXXXXXXXX ou XXXXXXXXX</p>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="initiateCampayPayment()" 
                            id="pay-button"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Payer
                    </button>
                    <button type="button" 
                            onclick="closeCampayModal()" 
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-md">
                        Annuler
                    </button>
                </div>
            </div>
            
            <!-- État du paiement -->
            <div id="payment-status" class="hidden text-center">
                <div id="payment-pending" class="hidden">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                        <svg class="animate-spin h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-yellow-800">Paiement en cours...</p>
                    <p class="text-xs text-gray-600 mt-2">Composez le code USSD affiché pour confirmer</p>
                    <div id="ussd-code" class="hidden mt-3 p-3 bg-gray-100 rounded-md">
                        <p class="text-sm font-medium">Code USSD :</p>
                        <p id="ussd-display" class="text-lg font-mono text-blue-600"></p>
                    </div>
                </div>
                
                <div id="payment-success" class="hidden">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-green-800">Paiement confirmé !</p>
                </div>
                
                <div id="payment-error" class="hidden">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-red-800">Échec du paiement</p>
                    <p id="error-message" class="text-xs text-gray-600 mt-2"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let paymentCheckInterval;

function openCampayModal() {
    document.getElementById('campay-payment-modal').classList.remove('hidden');
}

function closeCampayModal() {
    document.getElementById('campay-payment-modal').classList.add('hidden');
    if (paymentCheckInterval) {
        clearInterval(paymentCheckInterval);
    }
}

function initiateCampayPayment() {
    const phoneNumber = document.getElementById('payment-phone').value;
    const payButton = document.getElementById('pay-button');
    
    if (!phoneNumber.trim()) {
        alert('Veuillez saisir votre numéro de téléphone');
        return;
    }
    
    // Désactiver le bouton
    payButton.disabled = true;
    payButton.textContent = 'Traitement...';
    
    // Appel AJAX pour initier le paiement
    fetch('{{ route("campay.initiate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            order_id: {{ $order->id }},
            phone_number: phoneNumber
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Masquer le formulaire et afficher le statut pending
            document.getElementById('payment-content').classList.add('hidden');
            document.getElementById('payment-status').classList.remove('hidden');
            document.getElementById('payment-pending').classList.remove('hidden');
            
            // Afficher le code USSD si disponible
            if (data.ussd_code) {
                document.getElementById('ussd-code').classList.remove('hidden');
                document.getElementById('ussd-display').textContent = data.ussd_code;
            }
            
            // Commencer à vérifier le statut du paiement
            startPaymentStatusCheck();
        } else {
            showPaymentError(data.message);
            payButton.disabled = false;
            payButton.textContent = 'Payer';
        }
    })
    .catch(error => {
        showPaymentError('Erreur de connexion');
        payButton.disabled = false;
        payButton.textContent = 'Payer';
    });
}

function startPaymentStatusCheck() {
    paymentCheckInterval = setInterval(() => {
        fetch('{{ route("campay.check.status") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                order_id: {{ $order->id }}
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.status === 'completed') {
                    clearInterval(paymentCheckInterval);
                    showPaymentSuccess();
                } else if (data.status === 'failed') {
                    clearInterval(paymentCheckInterval);
                    showPaymentError(data.message);
                }
            }
        })
        .catch(error => {
            console.log('Erreur vérification paiement:', error);
        });
    }, 5000); // Vérifier toutes les 5 secondes
}

function showPaymentSuccess() {
    document.getElementById('payment-pending').classList.add('hidden');
    document.getElementById('payment-success').classList.remove('hidden');
    
    // Recharger la page après 2 secondes
    setTimeout(() => {
        location.reload();
    }, 2000);
}

function showPaymentError(message) {
    document.getElementById('payment-pending').classList.add('hidden');
    document.getElementById('payment-error').classList.remove('hidden');
    document.getElementById('error-message').textContent = message;
}

// Ouvrir automatiquement le modal si nécessaire
@if(session('initiate_payment'))
    document.addEventListener('DOMContentLoaded', function() {
        openCampayModal();
    });
@endif
</script>
