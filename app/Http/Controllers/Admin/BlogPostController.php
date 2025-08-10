<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function show(BlogPost $blog)
    {
        return view('admin.blog.show', compact('blog'));
    }
    public function index()
    {
        $posts = BlogPost::orderByDesc('created_at')->paginate(10);
        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'published' => 'boolean',
        ]);
        $data['slug'] = Str::slug($data['title']);
        $data['published_at'] = $data['published'] ? now() : null;
        BlogPost::create($data);
        return redirect()->route('admin.blog.index')->with('success', 'Article créé.');
    }

    public function edit(BlogPost $blog)
    {
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, BlogPost $blog)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'published' => 'boolean',
        ]);
        $data['slug'] = Str::slug($data['title']);
        $data['published_at'] = $data['published'] ? now() : null;
        $blog->update($data);
        return redirect()->route('admin.blog.index')->with('success', 'Article mis à jour.');
    }

    public function destroy(BlogPost $blog)
    {
        $blog->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Article supprimé.');
    }
}
