<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Articles du blog</h1>
        <a href="{{ route('admin.blog.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Nouvel article</a>
    </div>
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($posts as $post)
        <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between h-full">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-lg font-semibold truncate" title="{{ $post->title }}">{{ $post->title }}</h2>
                    <span class="px-2 py-1 text-xs rounded-full {{ $post->published ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                        {{ $post->published ? 'Publié' : 'Brouillon' }}
                    </span>
                </div>
                <div class="text-sm text-gray-500 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $post->created_at->format('d/m/Y') }}
                </div>
                <div class="text-gray-700 mb-4 line-clamp-3">{{ Str::limit(strip_tags($post->content), 120) }}</div>
            </div>
            <div class="flex justify-end gap-2 mt-2">
                <a href="{{ route('admin.blog.show', $post) }}" class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-sm font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Voir
                </a>
                <a href="{{ route('admin.blog.edit', $post) }}" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-sm font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21h6a2 2 0 002-2v-6a2 2 0 00-2-2H3a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                    Modifier
                </a>
                <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" onsubmit="return confirm('Supprimer cet article ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-sm font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center text-gray-500 py-12">Aucun article pour le moment.</div>
        @endforelse
    </div>
    <div class="mt-8">{{ $posts->links() }}</div>
</x-admin-layout>
