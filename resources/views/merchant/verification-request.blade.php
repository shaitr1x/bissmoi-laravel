@extends('layouts.merchant-layout')

@section('slot')
<div class="max-w-2xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6 text-center">Demande de badge de vérification</h1>
    <div class="bg-white border border-blue-200 rounded-lg shadow p-8">
        <form method="POST" action="{{ route('merchant.verification.request') }}" class="space-y-6">
            @csrf
            <div>
                <label for="business_phone" class="block mb-1 font-semibold">Numéro de téléphone professionnel <span class="text-red-500">*</span></label>
                <input type="tel" name="business_phone" id="business_phone" required class="w-full border rounded p-2 focus:ring focus:ring-blue-200" placeholder="ex: +216 99 999 999">
            </div>
            <div>
                <label class="block mb-1 font-semibold">Avez-vous un siège physique ? <span class="text-red-500">*</span></label>
                <div class="flex gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="has_physical_office" value="1" required class="form-radio text-blue-600"> <span class="ml-2">Oui</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="has_physical_office" value="0" required class="form-radio text-blue-600"> <span class="ml-2">Non</span>
                    </label>
                </div>
            </div>
            <div id="office_address_field" style="display:none;">
                <label for="office_address" class="block mb-1 font-semibold">Adresse du siège <span class="text-red-500">*</span></label>
                <input type="text" name="office_address" id="office_address" class="w-full border rounded p-2 focus:ring focus:ring-blue-200" placeholder="Adresse complète du siège">
            </div>
            <div>
                <label for="website_or_social" class="block mb-1 font-semibold">Site web ou réseaux sociaux professionnels</label>
                <input type="url" name="website_or_social" id="website_or_social" class="w-full border rounded p-2 focus:ring focus:ring-blue-200" placeholder="https://votre-site.com ou https://facebook.com/votrepage">
            </div>
            <div>
                <label for="business_description" class="block mb-1 font-semibold">Description de l’activité ou de l’entreprise <span class="text-red-500">*</span></label>
                <textarea name="business_description" id="business_description" rows="3" required class="w-full border rounded p-2 focus:ring focus:ring-blue-200" placeholder="Décrivez brièvement votre activité..."></textarea>
            </div>
            <div>
                <label for="business_experience" class="block mb-1 font-semibold">Années d’expérience ou date de création <span class="text-red-500">*</span></label>
                <input type="text" name="business_experience" id="business_experience" required class="w-full border rounded p-2 focus:ring focus:ring-blue-200" placeholder="ex: 5 ans ou 2018">
            </div>
            <div>
                <label for="message" class="block mb-1 font-semibold">Message complémentaire (optionnel)</label>
                <textarea name="message" id="message" rows="2" class="w-full border rounded p-2 focus:ring focus:ring-blue-200" placeholder="Expliquez votre demande ou ajoutez un commentaire..."></textarea>
            </div>
            <div class="pt-4 text-center">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold shadow hover:bg-blue-700 transition">Demander la vérification</button>
            </div>
        </form>
    </div>
</div>
<script>
    // Affiche/masque le champ adresse selon le choix du siège physique
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.getElementsByName('has_physical_office');
        const addressField = document.getElementById('office_address_field');
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === '1') {
                    addressField.style.display = '';
                    document.getElementById('office_address').setAttribute('required', 'required');
                } else {
                    addressField.style.display = 'none';
                    document.getElementById('office_address').removeAttribute('required');
                }
            });
        });
    });
</script>
@endsection
