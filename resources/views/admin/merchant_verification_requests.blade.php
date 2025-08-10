@extends('layouts.admin')

@section('slot')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Demandes de vérification des marchands</h1>

    @if(session('success'))
        <div class="mb-4 p-2 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="grid gap-4">
        @forelse($requests as $request)
            <div x-data="{ open: false }" class="bg-white shadow rounded p-4 cursor-pointer hover:bg-blue-50 transition" @click="open = !open">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="font-semibold text-lg">{{ $request->user->name ?? 'Utilisateur supprimé' }}</span>
                        <span class="ml-2 px-2 py-1 rounded text-xs font-bold
                            @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($request->status === 'approved') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            @if($request->status === 'pending') En attente
                            @elseif($request->status === 'approved') Approuvée
                            @else Rejetée @endif
                        </span>
                    </div>
                    <svg :class="{'rotate-180': open}" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </div>
                <div x-show="open" x-transition class="mt-4 border-t pt-4">
                    <p class="mb-2"><span class="font-medium">Message :</span> {{ $request->message ?: 'Aucun message.' }}</p>
                    <p class="mb-2"><span class="font-medium">Date de la demande :</span> {{ $request->created_at->format('d/m/Y H:i') }}</p>

                    <div class="mb-2"><span class="font-medium">Téléphone professionnel :</span> {{ $request->business_phone ?: 'Non renseigné' }}</div>
                    <div class="mb-2"><span class="font-medium">Siège physique :</span> {{ $request->has_physical_office ? 'Oui' : 'Non' }}</div>
                    @if($request->has_physical_office && $request->office_address)
                        <div class="mb-2"><span class="font-medium">Adresse du siège :</span> {{ $request->office_address }}</div>
                    @endif
                    <div class="mb-2"><span class="font-medium">Site web / Réseaux sociaux :</span> 
                        @if($request->website_or_social)
                            <a href="{{ $request->website_or_social }}" target="_blank" class="text-blue-600 underline">{{ $request->website_or_social }}</a>
                        @else
                            Non renseigné
                        @endif
                    </div>
                    <div class="mb-2"><span class="font-medium">Description de l’activité :</span> {{ $request->business_description ?: 'Non renseigné' }}</div>
                    <div class="mb-2"><span class="font-medium">Années d’expérience / Date de création :</span> {{ $request->business_experience ?: 'Non renseigné' }}</div>
                    <div class="flex gap-2 mt-2">
                        @if($request->status === 'pending')
                            <form action="{{ route('admin.merchant_verification_requests.approve', $request->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Approuver</button>
                            </form>
                            <form action="{{ route('admin.merchant_verification_requests.reject', $request->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Rejeter</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">Aucune demande en attente.</div>
        @endforelse
    </div>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</div>
@endsection
