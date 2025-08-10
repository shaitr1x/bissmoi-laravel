<x-app-layout>
    <div class="max-w-4xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-8">Blog</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($posts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="block bg-white rounded shadow p-6 hover:bg-gray-50 transition">
                    <h2 class="text-xl font-semibold mb-2">{{ $post->title }}</h2>
                    <div class="text-gray-500 text-sm mb-2">{{ $post->created_at->format('d/m/Y') }}</div>
                    <div class="text-gray-700 mb-4 line-clamp-3">{{ Str::limit(strip_tags($post->content), 120) }}</div>
                    <span class="inline-block px-2 py-1 text-xs rounded-full {{ $post->published ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                        {{ $post->published ? 'Publié' : 'Brouillon' }}
                    </span>
                </a>
            @empty
                <div class="col-span-full text-center text-gray-500 py-12">Aucun article pour le moment.</div>
            @endforelse
        </div>
        <div class="mt-8">{{ $posts->links() }}</div>
    </div>
</x-app-layout>
