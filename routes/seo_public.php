<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Models\SeoSetting;

Route::get('/robots.txt', function () {
    $seo = SeoSetting::first();
    return Response::make($seo && $seo->robots_txt ? $seo->robots_txt : "User-agent: *\nDisallow:", 200, [
        'Content-Type' => 'text/plain',
    ]);
});

use App\Models\Product;
use App\Models\BlogPost;

Route::get('/sitemap.xml', function () {
    $urls = [
        url('/'),
        url('/products'),
        url('/remerciements'),
        url('/blog'),
        url('/contact'),
    ];

    // Ajoute les URLs des produits
    foreach (Product::all() as $product) {
        $urls[] = url('/products/' . $product->id);
    }

    // Ajoute les URLs des articles de blog
    if (class_exists('App\\Models\\BlogPost')) {
        foreach (BlogPost::where('published', true)->get() as $post) {
            $urls[] = url('/blog/' . $post->slug);
        }
    }

    $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($urls as $u) {
        $xml .= '<url><loc>' . htmlspecialchars($u) . '</loc></url>';
    }
    $xml .= '</urlset>';

    return Response::make($xml, 200, ['Content-Type' => 'application/xml']);
});
