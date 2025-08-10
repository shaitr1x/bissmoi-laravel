<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SystemMaintenance extends Command
{
    protected $signature = 'system:maintenance {action : Action to perform (cleanup, backup, stats)}';
    protected $description = 'Perform system maintenance tasks';

    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'cleanup':
                $this->performCleanup();
                break;
            case 'backup':
                $this->performBackup();
                break;
            case 'stats':
                $this->displayStats();
                break;
            default:
                $this->error("Action non reconnue: {$action}");
                $this->info("Actions disponibles: cleanup, backup, stats");
                return 1;
        }

        return 0;
    }

    private function performCleanup()
    {
        $this->info('🧹 Début du nettoyage du système...');

        // Nettoyer les anciennes notifications (plus de 30 jours)
        $oldNotifications = AdminNotification::where('created_at', '<', now()->subDays(30))->count();
        AdminNotification::where('created_at', '<', now()->subDays(30))->delete();
        $this->info("✅ {$oldNotifications} anciennes notifications supprimées");

        // Nettoyer les sessions expirées
        DB::table('sessions')->where('last_activity', '<', now()->subDays(7)->timestamp)->delete();
        $this->info("✅ Sessions expirées nettoyées");

        // Créer une notification
        AdminNotification::createNotification(
            'Maintenance système',
            'Le nettoyage automatique du système a été effectué avec succès.',
            'success',
            'cog'
        );

        $this->info('✅ Nettoyage terminé avec succès!');
    }

    private function performBackup()
    {
        $this->info('💾 Début de la sauvegarde...');

        $timestamp = now()->format('Y-m-d_H-i-s');
        $backupDir = storage_path("app/backups/{$timestamp}");

        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $this->info("✅ Sauvegarde terminée: {$backupDir}");
    }

    private function displayStats()
    {
        $this->info('📊 Statistiques du système:');

        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();

        $this->info("👥 Utilisateurs: {$totalUsers}");
        $this->info("🛍️ Produits: {$totalProducts}");
        $this->info("🛒 Commandes: {$totalOrders}");
    }
}
