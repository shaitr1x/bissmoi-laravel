<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SeoSetting;

class SeoController extends Controller
{
    public function edit()
    {
        $seo = SeoSetting::first() ?? new SeoSetting();
        return view('admin.seo.edit', compact('seo'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            'custom_head' => 'nullable|string',
            'robots_txt' => 'nullable|string',
            'sitemap_xml' => 'nullable|string',
        ]);
        $seo = SeoSetting::first() ?? new SeoSetting();
        $seo->fill($data)->save();
        return redirect()->route('admin.seo.edit')->with('success', 'Paramètres SEO mis à jour.');
    }
}
