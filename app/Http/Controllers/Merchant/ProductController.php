<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
    // ...existing code...
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
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->images = [$path];
        } else {
            $product->images = [];
        }
        $product->save();

        // Envoyer un email aux administrateurs avec liens de confirmation/rejet
        $adminEmails = [
            'yannicksongmy@gmail.com',
            'dokoalanfranck@gmail.com',
            'jordymbele948@gmail.com',
            'danieltambe522@gmail.com',
            'danielmama881@gmail.com',
            'badoanagabriel94@gmail.com',
        ];
        $merchant = auth()->user();
        foreach ($adminEmails as $email) {
            \Log::info('Envoi mail admin à ' . $email);
            \Mail::to($email)->send(new \App\Mail\ProductAdminConfirmation($product, $merchant));
        }

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            'image' => 'nullable|image|max:2048',
        ]);

        $product->fill($validated);

        // Suppression de l'ancienne image si une nouvelle est uploadée
        if ($request->hasFile('image')) {
            $oldImages = $product->images;
            if (is_array($oldImages)) {
                foreach ($oldImages as $img) {
                    $imgPath = public_path($img);
                    if (file_exists($imgPath)) {
                        @unlink($imgPath);
                    }
                }
            }
            $path = $request->file('image')->store('products', 'public');
            $product->images = [$path];
        }
        $product->save();
        
        // NOUVEAU : Notification admin lors de la mise à jour de produit
        $adminEmails = [
            'jordymbele948@gmail.com',
            'danieltambe522@gmail.com',
            'danielmama881@gmail.com',
            'badoanagabriel94@gmail.com',
            'dokoalanfranck@gmail.com',
            'yannicksongmy@gmail.com',
        ];
        $merchant = auth()->user();
        foreach ($adminEmails as $email) {
            try {
                \Mail::raw(
                    "Mise à jour de produit sur BISSMOI.\n\nCommerçant: {$merchant->name}\nEmail: {$merchant->email}\nBoutique: {$merchant->shop_name}\nProduit: {$product->name}\nNouveau prix: {$product->price} FCFA\n\nLe produit a été mis à jour et nécessite peut-être une nouvelle validation.",
                    function ($message) use ($email) {
                        $message->to($email)->subject('Mise à jour produit - BISSMOI');
                    }
                );
                \Log::info('Email mise à jour produit envoyé à ' . $email);
            } catch (\Exception $e) {
                \Log::error('Erreur envoi email mise à jour produit à ' . $email . ': ' . $e->getMessage());
            }
        }
        
        return redirect()->route('merchant.products.index')->with('success', 'Produit modifié avec succès !');
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
        $images = $product->images;
        if (is_array($images)) {
            foreach ($images as $img) {
                $imgPath = public_path($img);
                if (file_exists($imgPath)) {
                    @unlink($imgPath);
                }
            }
        }
        $product->delete();
        return redirect()->route('merchant.products.index')->with('success', 'Produit supprimé avec succès et images nettoyées !');
    }
}
