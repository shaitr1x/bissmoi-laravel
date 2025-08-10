<x-admin-layout>
    <h1 class="text-2xl font-bold mb-6">Nouvel article</h1>
    <form method="POST" action="{{ route('admin.blog.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">Titre</label>
            <input type="text" name="title" class="w-full border rounded p-2" value="{{ old('title') }}" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Slug (URL)</label>
            <input type="text" name="slug" class="w-full border rounded p-2" value="{{ old('slug') }}" required>
            <small class="text-gray-500">ex: mon-article-exemple</small>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Contenu</label>
            <textarea name="content" class="w-full border rounded p-2" rows="8" required>{{ old('content') }}</textarea>
        </div>
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="published" value="1" class="form-checkbox" {{ old('published') ? 'checked' : '' }}>
                <span class="ml-2">Publier</span>
            </label>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Enregistrer</button>
        <a href="{{ route('admin.blog.index') }}" class="ml-4 text-gray-600 hover:underline">Annuler</a>
    </form>
</x-admin-layout>
