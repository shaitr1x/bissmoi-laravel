
@extends('layouts.app')
@section('content')
<div class="bg-gradient-to-br from-blue-50 to-purple-100 min-h-screen py-12">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-4xl font-bold text-center text-purple-700 mb-4">Remerciements</h1>
        <p class="text-center text-gray-600 italic mb-8">Merci à toutes les personnes et partenaires qui ont contribué à l'aventure Bissmoi.</p>

        <div class="mb-10">
            <h2 class="text-2xl font-semibold text-blue-700 mb-4 text-center">Contributeurs</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-8">
                @forelse($remerciements as $r)
                    <div class="flex flex-col items-center bg-white rounded-xl shadow-lg p-6 transition-transform duration-200 hover:-translate-y-1 hover:shadow-2xl animate-fade-in">
                        <div class="w-16 h-16 mb-3 rounded-full bg-gradient-to-br from-blue-100 to-purple-200 flex items-center justify-center text-blue-900 text-3xl font-bold shadow-lg border border-blue-300">
                            {{ mb_strtoupper(mb_substr($r->nom, 0, 1)) }}
                        </div>
                        <div class="text-center text-blue-900 font-semibold text-lg truncate max-w-[120px]">{{ $r->nom }}</div>
                    </div>
                @empty
                    <span class="text-gray-400 col-span-full">Aucun remerciement pour le moment.</span>
                @endforelse
            </div>
            <style>
                @keyframes fade-in { from { opacity: 0; transform: translateY(20px);} to { opacity: 1; transform: none; } }
                .animate-fade-in { animation: fade-in 0.7s cubic-bezier(.4,0,.2,1) both; }
            </style>
        </div>

        <div>
            <h2 class="text-2xl font-semibold text-purple-700 mb-4 text-center">Fondateurs</h2>
            <div class="flex flex-wrap justify-center gap-10">
                @forelse($fondateurs as $f)
                    <div class="flex flex-col items-center bg-white rounded-2xl shadow-2xl p-8 border border-yellow-300 animate-fade-in max-w-xs w-full relative group">
                        <div class="relative mb-4">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-yellow-200 via-yellow-400 to-yellow-600 flex items-center justify-center text-yellow-900 text-5xl font-extrabold shadow-xl border-4 border-yellow-400 ring-4 ring-yellow-100 group-hover:ring-yellow-300 transition-all duration-300">
                                {{ mb_strtoupper(mb_substr($f, 0, 1)) }}
                            </div>
                            <div class="absolute inset-0 rounded-full pointer-events-none" style="box-shadow:0 0 32px 8px #ffe06655;"></div>
                        </div>
                        <div class="text-center text-yellow-800 font-extrabold text-xl uppercase tracking-widest mt-2 mb-1">{{ $f }}</div>
                        <!-- <div class="text-center text-yellow-700 italic text-sm">Fondateur / Rôle</div> -->
                    </div>
                @empty
                    <span class="text-gray-400 col-span-full">Aucun fondateur renseigné.</span>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
