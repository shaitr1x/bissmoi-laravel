<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Finaliser ma commande</h1>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Formulaire de commande -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations de livraison</h2>

                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <div>
                                <label for="delivery_address" class="block text-sm font-medium text-gray-700">Adresse de livraison</label>
                                <textarea name="delivery_address" id="delivery_address" rows="3" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                          required placeholder="Votre adresse complète de livraison">{{ old('delivery_address') }}</textarea>
                                @error('delivery_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Numéro de téléphone</label>
                    <input type="tel" name="phone" id="phone" 
                        value="{{ old('phone', Auth::user()->merchant_phone) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                        required
                        >
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes pour la livraison (optionnel)</label>
                                <textarea name="notes" id="notes" rows="2" 
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                          placeholder="Instructions spéciales pour la livraison">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Mode de paiement -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Mode de paiement</h3>
                            
                            <!-- Option 1: Paiement mobile -->
                            <div class="space-y-4">
                                @if($mobilePaymentEnabled)
                                <label class="flex items-start space-x-3 cursor-pointer">
                                    <input type="radio" name="payment_method" value="cinetpay" class="mt-1" checked>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <div class="text-sm font-medium text-gray-900">Paiement électronique</div>
                                            <div class="flex space-x-1">
                                                <span class="px-2 py-1 text-xs bg-orange-100 text-orange-800 rounded">MTN</span>
                                                <span class="px-2 py-1 text-xs bg-orange-100 text-orange-800 rounded">Orange</span>
                                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">Visa</span>
                                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">MC</span>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Payez avec MTN Money, Orange Money, Visa, MasterCard via CinetPay</p>
                                        
                                        <!-- Zone d'affichage de l'option sélectionnée -->
                                        <div class="mt-3 hidden" id="selected-payment-info">
                                            <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-sm font-medium text-blue-800" id="selected-method-text">Aucune méthode sélectionnée</span>
                                                    <button type="button" onclick="openPaymentMethodModal()" class="text-xs text-blue-600 hover:text-blue-800 underline">Changer</button>
                                                </div>
                                                <div id="payment-form-container" class="mt-3 hidden">
                                                    <!-- Le formulaire spécifique sera injecté ici -->
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Bouton pour ouvrir le modal de sélection -->
                                        <button type="button" onclick="openPaymentMethodModal()" class="mt-3 w-full px-3 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                            Choisir une méthode de paiement
                                        </button>
                                        
                                        <!-- Champs cachés pour stocker les infos de paiement -->
                                        <input type="hidden" name="cinetpay_method" id="cinetpay_method" value="">
                                        <input type="hidden" name="phone_number" id="phone_number" value="">
                                        <input type="hidden" name="card_info" id="card_info" value="">
                                    </div>
                                </label>
                                @endif
                                <!-- Option 2: Paiement à la livraison -->
                                <label class="flex items-start space-x-3 cursor-pointer">
                                    <input type="radio" name="payment_method" value="cash_on_delivery" class="mt-1">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <div class="text-sm font-medium text-gray-900">Paiement à la livraison</div>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Paiement en espèces à la réception</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <script>
                            // Gestion de l'affichage du champ téléphone
                            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                                radio.addEventListener('change', function() {
                                    // CinetPay n'a plus besoin de champ téléphone car il gère tout
                                    // Le JavaScript n'est plus nécessaire
                                });
                            });
                        </script>

                        <!-- Modal de sélection de méthode de paiement CinetPay -->
                        <div id="payment-method-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
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
                                                    Choisir votre méthode de paiement
                                                </h3>
                                                
                                                <!-- Options de paiement -->
                                                <div class="space-y-3">
                                                    <!-- MTN Money -->
                                                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50" onclick="selectPaymentMethod('mtn')">
                                                        <input type="radio" name="modal_payment_method" value="mtn" class="mr-3">
                                                        <div class="flex items-center space-x-3 flex-1">
                                                            <span class="px-3 py-1 text-sm bg-orange-100 text-orange-800 rounded-lg font-medium">MTN</span>
                                                            <div>
                                                                <div class="font-medium text-gray-900">MTN Mobile Money</div>
                                                                <div class="text-sm text-gray-500">Payez avec votre compte MTN MoMo</div>
                                                            </div>
                                                        </div>
                                                    </label>

                                                    <!-- Orange Money -->
                                                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50" onclick="selectPaymentMethod('orange')">
                                                        <input type="radio" name="modal_payment_method" value="orange" class="mr-3">
                                                        <div class="flex items-center space-x-3 flex-1">
                                                            <span class="px-3 py-1 text-sm bg-orange-100 text-orange-800 rounded-lg font-medium">OM</span>
                                                            <div>
                                                                <div class="font-medium text-gray-900">Orange Money</div>
                                                                <div class="text-sm text-gray-500">Payez avec votre compte Orange Money</div>
                                                            </div>
                                                        </div>
                                                    </label>

                                                    <!-- Visa -->
                                                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50" onclick="selectPaymentMethod('visa')">
                                                        <input type="radio" name="modal_payment_method" value="visa" class="mr-3">
                                                        <div class="flex items-center space-x-3 flex-1">
                                                            <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-lg font-medium">VISA</span>
                                                            <div>
                                                                <div class="font-medium text-gray-900">Carte Visa</div>
                                                                <div class="text-sm text-gray-500">Payez avec votre carte Visa</div>
                                                            </div>
                                                        </div>
                                                    </label>

                                                    <!-- MasterCard -->
                                                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50" onclick="selectPaymentMethod('mastercard')">
                                                        <input type="radio" name="modal_payment_method" value="mastercard" class="mr-3">
                                                        <div class="flex items-center space-x-3 flex-1">
                                                            <span class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-lg font-medium">MC</span>
                                                            <div>
                                                                <div class="font-medium text-gray-900">MasterCard</div>
                                                                <div class="text-sm text-gray-500">Payez avec votre carte MasterCard</div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="button" onclick="confirmPaymentMethod()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                            Continuer
                                        </button>
                                        <button type="button" onclick="closePaymentMethodModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                            Annuler
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                        let selectedMethod = '';

                        function openPaymentMethodModal() {
                            document.getElementById('payment-method-modal').classList.remove('hidden');
                        }

                        function closePaymentMethodModal() {
                            document.getElementById('payment-method-modal').classList.add('hidden');
                        }

                        function selectPaymentMethod(method) {
                            selectedMethod = method;
                            // Cocher le radio button correspondant
                            document.querySelector(`input[name="modal_payment_method"][value="${method}"]`).checked = true;
                        }

                        function confirmPaymentMethod() {
                            if (!selectedMethod) {
                                alert('Veuillez sélectionner une méthode de paiement');
                                return;
                            }

                            // Mettre à jour l'affichage
                            const selectedInfo = document.getElementById('selected-payment-info');
                            const methodText = document.getElementById('selected-method-text');
                            const formContainer = document.getElementById('payment-form-container');
                            
                            selectedInfo.classList.remove('hidden');
                            
                            // Définir le texte et le formulaire selon la méthode
                            switch(selectedMethod) {
                                case 'mtn':
                                    methodText.textContent = 'MTN Mobile Money';
                                    formContainer.innerHTML = getMobileMoneyForm('MTN');
                                    break;
                                case 'orange':
                                    methodText.textContent = 'Orange Money';
                                    formContainer.innerHTML = getMobileMoneyForm('Orange');
                                    break;
                                case 'visa':
                                    methodText.textContent = 'Carte Visa';
                                    formContainer.innerHTML = getCardForm('Visa');
                                    break;
                                case 'mastercard':
                                    methodText.textContent = 'MasterCard';
                                    formContainer.innerHTML = getCardForm('MasterCard');
                                    break;
                            }
                            
                            formContainer.classList.remove('hidden');
                            document.getElementById('cinetpay_method').value = selectedMethod;
                            
                            closePaymentMethodModal();
                        }

                        function getMobileMoneyForm(provider) {
                            return `
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de téléphone ${provider}</label>
                                        <input type="tel" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="Ex: 670123456"
                                               onchange="updatePhoneNumber(this.value)"
                                               required>
                                        <p class="text-xs text-gray-500 mt-1">Format: 9 chiffres sans le code pays</p>
                                    </div>
                                </div>
                            `;
                        }

                        function getCardForm(cardType) {
                            return `
                                <div class="space-y-3">
                                    <div class="text-sm text-gray-600 p-3 bg-blue-50 rounded-md">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            <span class="font-medium">Paiement sécurisé ${cardType}</span>
                                        </div>
                                        <p>Vous serez redirigé vers la page de paiement sécurisée CinetPay pour saisir vos informations de carte.</p>
                                    </div>
                                </div>
                            `;
                        }

                        function updatePhoneNumber(phone) {
                            document.getElementById('phone_number').value = phone;
                        }

                        // Validation du formulaire avant soumission
                        function validateForm() {
                            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
                            
                            if (paymentMethod && paymentMethod.value === 'cinetpay') {
                                const cinetpayMethod = document.getElementById('cinetpay_method').value;
                                
                                if (!cinetpayMethod) {
                                    alert('Veuillez choisir une méthode de paiement CinetPay');
                                    return false;
                                }
                                
                                // Vérifier si un numéro de téléphone est requis pour mobile money
                                if (['mtn', 'orange'].includes(cinetpayMethod)) {
                                    const phoneNumber = document.getElementById('phone_number').value;
                                    if (!phoneNumber || phoneNumber.trim() === '') {
                                        alert('Veuillez saisir votre numéro de téléphone pour le paiement mobile');
                                        return false;
                                    }
                                }
                            }
                            
                            return true;
                        }
                        </script>

                        <div class="mt-6 flex space-x-4">
                            <a href="{{ route('cart.index') }}" 
                               class="flex-1 text-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-150">
                                Retour au panier
                            </a>
                            <button type="submit" 
                                    class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150"
                                    onclick="return validateForm()">
                                Confirmer la commande
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Résumé de commande -->
                <div class="bg-white rounded-lg shadow p-6 h-fit sticky top-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Résumé de votre commande</h2>

                    <div class="space-y-4 mb-6">
                        @foreach($cartItems as $item)
                            @if($item->product->stock_quantity > 0)
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <x-product-image :product="$item->product" size="small" class="w-12 h-12" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $item->product->name }}</p>
                                        <p class="text-sm text-gray-500">Qté: {{ $item->quantity }}</p>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ number_format($item->quantity * $item->product->current_price, 0, ',', ' ') }} FCFA
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Sous-total:</span>
                            <span class="font-medium">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Livraison:</span>
                            <span class="font-medium text-blue-600">{{ number_format($shipping_fee, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">TVA:</span>
                            <span class="font-medium">Incluse</span>
                        </div>
                        <div class="border-t pt-2">
                            <div class="flex justify-between">
                                <span class="text-lg font-semibold text-gray-900">Total:</span>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    </div>

                    <!-- Informations de livraison -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-blue-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Informations de livraison</p>
                                <ul class="space-y-1">
                                    <li>• Livraison {{ number_format($shipping_fee, 0, ',', ' ') }} FCFA</li>
                                    <li>• Délai: 2-5 jours ouvrés</li>
                                    <li>• Paiement à la livraison</li>
                                    <li>• Suivi par SMS</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    // Restriction sur le format du numéro de téléphone retirée
    </script>
</x-app-layout>
