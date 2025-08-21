<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [
            url('/'),
            url('/boutique'),
            url('/categories'),
            url('/contact'),
        ];

        // Ajouter les produits
        foreach (Product::all() as $product) {
            $urls[] = url('/produit/' . $product->slug);
        }
        // Ajouter les catégories
        foreach (Category::all() as $category) {
            $urls[] = url('/categorie/' . $category->slug);
        }

        $xml = view('sitemap.xml', compact('urls'))->render();
        return Response::make($xml, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
