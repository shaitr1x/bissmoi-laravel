<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Pour chaque commande, si merchant_id est null, on prend le user_id du premier produit du panier
        DB::table('orders')->whereNull('merchant_id')->orderBy('id')->chunk(100, function ($orders) {
            foreach ($orders as $order) {
                $orderItem = DB::table('order_items')->where('order_id', $order->id)->first();
                if ($orderItem) {
                    $product = DB::table('products')->where('id', $orderItem->product_id)->first();
                    if ($product) {
                        DB::table('orders')->where('id', $order->id)->update([
                            'merchant_id' => $product->user_id
                        ]);
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Optionnel : remettre merchant_id à null
        DB::table('orders')->update(['merchant_id' => null]);
    }
};
