@extends('layouts.admin')
@section('content')
<div class="bg-gradient-to-br from-blue-50 to-purple-100 min-h-screen py-12">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-center text-purple-700 mb-6">Espace Admin – Remerciements</h1>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded text-center font-medium">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('remerciements.store') }}" class="flex flex-col sm:flex-row gap-3 mb-8 justify-center items-center">
            @csrf
            <input type="text" name="nom" placeholder="Nom à remercier" required class="border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-purple-300 w-full sm:w-auto">
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold px-6 py-2 rounded shadow">Ajouter</button>
        </form>

        <h2 class="text-xl font-semibold text-blue-700 mb-4 text-center">Liste des remerciements</h2>
        <div class="flex flex-wrap gap-4 justify-center mb-10">
            @forelse($remerciements as $r)
                <div class="flex items-center bg-blue-100 text-blue-900 px-5 py-2 rounded-full shadow text-lg font-medium">
                    <span>{{ $r->nom }}</span>
                    <form method="POST" action="{{ route('remerciements.destroy', $r->id) }}" class="ml-3">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold" title="Supprimer">&times;</button>
                    </form>
                </div>
            @empty
                <span class="text-gray-400">Aucun remerciement pour le moment.</span>
            @endforelse
        </div>

        <h2 class="text-xl font-semibold text-purple-700 mb-4 text-center">Fondateurs</h2>
        <form method="POST" action="{{ route('remerciements.fondateurs') }}" class="mb-6">
            @csrf
            <textarea name="fondateurs" rows="3" class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-purple-300 mb-2" placeholder="Un fondateur par ligne">{{ isset($fondateurs) ? implode("\n", $fondateurs) : '' }}</textarea>
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold px-6 py-2 rounded shadow">Mettre à jour</button>
        </form>
        <div class="flex flex-wrap gap-4 justify-center">
            @if(isset($fondateurs) && count($fondateurs))
                @foreach($fondateurs as $f)
                    <div class="bg-purple-100 text-purple-900 px-5 py-2 rounded-full shadow text-lg font-medium">
                        {{ $f }}
                    </div>
                @endforeach
            @else
                <span class="text-gray-400">Aucun fondateur renseigné.</span>
            @endif
        </div>
    </div>
</div>
@endsection
