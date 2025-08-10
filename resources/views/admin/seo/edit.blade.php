<x-admin-layout>
<div class="bg-white p-8 rounded shadow max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Paramètres de Référencement (SEO)</h1>
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.seo.update') }}">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">Meta Title</label>
            <input type="text" name="meta_title" class="w-full border rounded p-2" value="{{ old('meta_title', $seo->meta_title) }}">
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Meta Description</label>
            <textarea name="meta_description" class="w-full border rounded p-2" rows="2">{{ old('meta_description', $seo->meta_description) }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Meta Keywords</label>
            <textarea name="meta_keywords" class="w-full border rounded p-2" rows="2">{{ old('meta_keywords', $seo->meta_keywords) }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Balises personnalisées &lt;head&gt; (ex: Open Graph, Twitter...)</label>
            <textarea name="custom_head" class="w-full border rounded p-2" rows="3">{{ old('custom_head', $seo->custom_head) }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">robots.txt</label>
            <textarea name="robots_txt" class="w-full border rounded p-2" rows="2">{{ old('robots_txt', $seo->robots_txt) }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">sitemap.xml</label>
            <textarea name="sitemap_xml" class="w-full border rounded p-2" rows="2">{{ old('sitemap_xml', $seo->sitemap_xml) }}</textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Enregistrer</button>
    </form>
</div>
</x-admin-layout>
