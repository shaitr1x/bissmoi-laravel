<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;

class TestMerchantOrderNotification extends Command
{
    protected $signature = 'test:merchant-order-notification';
    protected $description = 'Tester la notification de nouvelle commande pour commerçant';

    public function handle()
    {
        $this->info('=== Test de notification de commande pour commerçant ===');

        // Trouver un client et un commerçant
        $customer = User::where('role', '!=', 'admin')->where('role', '!=', 'merchant')->first();
        $merchant = User::where('role', 'merchant')->first();
        
        if (!$customer) {
            $this->error('❌ Aucun client trouvé dans la base de données.');
            return Command::FAILURE;
        }
        
        if (!$merchant) {
            $this->error('❌ Aucun commerçant trouvé dans la base de données.');
            return Command::FAILURE;
        }

        // Trouver un produit du commerçant
        $product = Product::where('user_id', $merchant->id)->where('status', 'active')->first();
        
        if (!$product) {
            // Essayer avec n'importe quel produit actif
            $product = Product::where('status', 'active')->first();
            if ($product) {
                $merchant = User::find($product->user_id);
                $this->info("⚠️ Utilisation d'un produit d'un autre commerçant pour le test");
            }
        }
        
        if (!$product) {
            $this->error('❌ Aucun produit actif trouvé dans la base de données.');
            return Command::FAILURE;
        }

        $this->info("✅ Client: {$customer->name} ({$customer->email})");
        $this->info("✅ Commerçant: {$merchant->name} ({$merchant->email})");
        $this->info("✅ Produit: {$product->name} - {$product->current_price}€");

        DB::beginTransaction();

        try {
            // Simuler une commande
            $order = Order::create([
                'user_id' => $customer->id,
                'merchant_id' => $merchant->id,
                'order_number' => 'BSM-TEST-' . strtoupper(uniqid()),
                'total_amount' => $product->current_price,
                'status' => 'pending',
                'notes' => 'Commande de test automatique',
                'delivery_address' => 'Adresse de test',
                'phone' => '0123456789'
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->current_price,
                'total' => $product->current_price
            ]);

            // Tester la notification (comme dans OrderController::store)
            NotificationService::sendToUser(
                $merchant->id,
                'Nouvelle commande reçue',
                "Vous avez reçu une nouvelle commande #{$order->order_number} d'un montant de " . number_format($product->current_price, 2) . " €. Consultez vos commandes pour plus de détails.",
                'success',
                'shopping-bag',
                url('/merchant/orders'),
                'Voir mes commandes',
                true // Envoyer email
            );

            $this->info('✅ Notification envoyée avec succès !');
            $this->info("📧 Email envoyé à: {$merchant->email}");
            $this->info("🔗 Commande de test créée: {$order->order_number}");
            
            DB::rollback(); // Ne pas sauvegarder la commande de test
            $this->info('🗑️ Commande de test supprimée (rollback)');
            
            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollback();
            $this->error('❌ Erreur: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
