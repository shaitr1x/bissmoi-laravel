@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white p-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6">Devenir commerçant</h1>
<form method="POST" action="{{ route('become-merchant.submit') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="shop_name" class="block font-semibold mb-1">Nom de la boutique</label>
        <input type="text" name="shop_name" id="shop_name" class="w-full border rounded p-2" value="{{ old('shop_name') }}" required>
    </div>
    <div class="mb-4">
        <label for="merchant_description" class="block font-semibold mb-1">Description de votre activité</label>
        <textarea name="merchant_description" id="merchant_description" class="w-full border rounded p-2" required>{{ old('merchant_description') }}</textarea>
    </div>
    <div class="mb-4">
        <label for="merchant_phone" class="block font-semibold mb-1">Téléphone</label>
        <input type="text" name="merchant_phone" id="merchant_phone" class="w-full border rounded p-2" value="{{ old('merchant_phone') }}" required>
    </div>
    <div class="mb-4">
        <label for="merchant_address" class="block font-semibold mb-1">Adresse</label>
        <input type="text" name="merchant_address" id="merchant_address" class="w-full border rounded p-2" value="{{ old('merchant_address') }}" required>
    </div>
    <div class="mb-4">
        <label for="merchant_city" class="block font-semibold mb-1">Ville</label>
        <select name="merchant_city" id="merchant_city" class="w-full border rounded p-2" required>
            <option value="">Sélectionnez votre ville</option>
            <option value="Yaoundé" {{ old('merchant_city') == 'Yaoundé' ? 'selected' : '' }}>Yaoundé</option>
            <option value="Douala" {{ old('merchant_city') == 'Douala' ? 'selected' : '' }}>Douala</option>
            <option value="Bertoua" {{ old('merchant_city') == 'Bertoua' ? 'selected' : '' }}>Bertoua</option>
            <option value="Garoua" {{ old('merchant_city') == 'Garoua' ? 'selected' : '' }}>Garoua</option>
            <option value="Ngaoundéré" {{ old('merchant_city') == 'Ngaoundéré' ? 'selected' : '' }}>Ngaoundéré</option>
        </select>
    </div>
    <div class="mb-4">
        <label for="merchant_website" class="block font-semibold mb-1">Site web (optionnel)</label>
        <input type="url" name="merchant_website" id="merchant_website" class="w-full border rounded p-2" value="{{ old('merchant_website') }}">
    </div>
    <div class="mb-6">
        <label class="block font-semibold mb-1">Politique commerçant</label>
        <div class="border p-3 rounded bg-gray-50 h-32 overflow-y-auto text-xs mb-2" style="max-height: 150px;">
            <strong>Charte d'utilisation BISSMOI :</strong><br>
            - Vous vous engagez à fournir des informations exactes et à jour.<br>
            - Toute activité frauduleuse entraînera la suspension du compte.<br>
            - Vous acceptez de respecter la législation en vigueur et les CGU de la plateforme.<br>
            - Les documents fournis seront vérifiés par l'équipe BISSMOI.<br>
            - ... (ajoutez ici vos règles spécifiques)
        </div>
        <label class="inline-flex items-center">
            <input type="checkbox" name="accept_policy" class="mr-2" required>
            J'ai lu et j'accepte la politique commerçant BISSMOI
        </label>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Envoyer la demande</button>
</form>
</div>
@endsection
