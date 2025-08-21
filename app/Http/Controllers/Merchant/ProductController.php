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

            // Envoyer un email aux administrateurs
            $adminEmails = [
                'dokoalanfranck@gmail.com',
                'jordymbele948@gmail.com',
                'danieltambe522@gmail.com',
                'danielmama881@gmail.com',
                'badoanagabriel94@gmail.com',
            ];
            $merchant = auth()->user();
            foreach ($adminEmails as $email) {
                \Mail::raw(
                    "Un commerçant a ajouté un nouveau produit sur BISSMOI.\n\nCommerçant: {$merchant->name}\nEmail: {$merchant->email}\nBoutique: {$merchant->shop_name}\nProduit: {$product->name}",
                    function ($message) use ($email) {
                        $message->to($email)
                            ->subject('Nouveau produit ajouté - BISSMOI');
                    }
                );
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
