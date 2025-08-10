<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);


        $product = new \App\Models\Product($validated);
        $product->merchant_id = auth()->id();
        $images = [];
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid('product_') . '.' . $file->getClientOriginalExtension();
            $img = Image::make($file)->resize(800, null, function ($constraint) { $constraint->aspectRatio(); $constraint->upsize(); });
            $img->encode($file->getClientOriginalExtension(), 80); // compression à 80%
            Storage::disk('public')->put('products/' . $filename, (string) $img);
            $images[] = 'products/' . $filename;
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = uniqid('product_') . '.' . $file->getClientOriginalExtension();
                $img = Image::make($file)->resize(800, null, function ($constraint) { $constraint->aspectRatio(); $constraint->upsize(); });
                $img->encode($file->getClientOriginalExtension(), 80);
                Storage::disk('public')->put('products/' . $filename, (string) $img);
                $images[] = 'products/' . $filename;
            }
        }
        $product->images = $images;
        $product->save();

        return redirect()->route('merchant.products.index')->with('success', 'Produit créé avec succès !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = \App\Models\Product::with('category')->findOrFail($id);
        
        // Vérifier que l'utilisateur peut voir ce produit
        if ($product->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }
        
        return view('merchant.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        
        // Vérifier que l'utilisateur peut éditer ce produit
        if ($product->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }
        
        $categories = \App\Models\Category::all();
        
        return view('merchant.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = \App\Models\Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $product->fill($validated);

        // Gestion des images existantes
        $images = $product->images ?? [];
        if (!is_array($images)) {
            $images = json_decode($images, true) ?: [];
        }

        // Remplacement de l'image principale si uploadée
        if ($request->hasFile('image')) {
            // Supprime l'ancienne image principale si elle existe
            if (isset($images[0]) && Storage::disk('public')->exists($images[0])) {
                Storage::disk('public')->delete($images[0]);
                unset($images[0]);
            }
            $file = $request->file('image');
            $filename = uniqid('product_') . '.' . $file->getClientOriginalExtension();
            $img = Image::make($file)->resize(800, null, function ($constraint) { $constraint->aspectRatio(); $constraint->upsize(); });
            $img->encode($file->getClientOriginalExtension(), 80);
            Storage::disk('public')->put('products/' . $filename, (string) $img);
            array_unshift($images, 'products/' . $filename);
        }

        // Ajout d'autres images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = uniqid('product_') . '.' . $file->getClientOriginalExtension();
                $img = Image::make($file)->resize(800, null, function ($constraint) { $constraint->aspectRatio(); $constraint->upsize(); });
                $img->encode($file->getClientOriginalExtension(), 80);
                Storage::disk('public')->put('products/' . $filename, (string) $img);
                $images[] = 'products/' . $filename;
            }
        }

        // On aplatit et filtre pour ne garder que les strings
        $images = array_values(array_filter($images, 'is_string'));
        $product->images = $images;

        $product->save();

        return redirect()->route('merchant.products.edit', $product->id)
            ->with('success', 'Produit modifié avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    $product = \App\Models\Product::findOrFail($id);
    // Les images seront supprimées par l'observer
    $product->delete();
    return redirect()->route('merchant.products.index')->with('success', 'Produit supprimé avec succès !');
    }
}
