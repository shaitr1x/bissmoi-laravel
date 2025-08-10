<x-admin-layout>
    <div class="max-w-2xl mx-auto bg-white rounded shadow p-8 mt-8">
        <h1 class="text-3xl font-bold mb-4">{{ $blog->title }}</h1>
        <div class="mb-2 text-gray-500 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            {{ $blog->created_at->format('d/m/Y H:i') }}
            <span class="ml-4 px-2 py-1 text-xs rounded-full {{ $blog->published ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                {{ $blog->published ? 'Publié' : 'Brouillon' }}
            </span>
        </div>
        <div class="mb-6 text-gray-700 prose max-w-none">
            {!! nl2br(e($blog->content)) !!}
        </div>
        <a href="{{ route('admin.blog.edit', $blog) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Modifier</a>
        <a href="{{ route('admin.blog.index') }}" class="ml-2 text-gray-600 hover:underline">Retour à la liste</a>
    </div>
</x-admin-layout>
