
<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails de la catégorie') }}
            </h2>
            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4">
        <div class="p-6">
            <h3 class="text-lg font-bold mb-2">{{ $category->name ?? 'Nom non défini' }}</h3>
            <p class="mb-2"><span class="font-semibold">ID:</span> {{ $category->id ?? '-' }}</p>
            <p class="mb-2"><span class="font-semibold">Description:</span> {{ $category->description ?? 'Aucune description' }}</p>
            <p class="mb-2"><span class="font-semibold">Créée le:</span> {{ $category->created_at ?? '-' }}</p>
            <p class="mb-2"><span class="font-semibold">Modifiée le:</span> {{ $category->updated_at ?? '-' }}</p>
        </div>
    </div>
</x-admin-layout>
