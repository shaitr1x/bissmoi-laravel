
@section('seo')
    <title>{{ $post->title }} | Bissmoi</title>
    <meta name="description" content="{{ Str::limit(strip_tags($post->content), 160) }}">
@endsection

<x-app-layout>
    <div class="max-w-2xl mx-auto py-10">
        <a href="{{ route('blog.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">← Retour au blog</a>
        <h1 class="text-3xl font-bold mb-2">{{ $post->title }}</h1>
        <div class="text-gray-500 text-sm mb-6 flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            {{ $post->created_at->format('d/m/Y H:i') }}
        </div>
        <div class="prose max-w-none text-gray-800 mb-8">
            {!! nl2br(e($post->content)) !!}
        </div>
    </div>
</x-app-layout>
