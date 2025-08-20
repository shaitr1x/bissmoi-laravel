<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $settings = [
            'site_name' => config('app.name'),
            'site_description' => 'Marketplace en ligne pour commerçants et clients',
            'maintenance_mode' => app()->isDownForMaintenance(),
            'cache_status' => Cache::has('system_status'),
            'storage_usage' => $this->getStorageUsage(),
            'recent_backups' => $this->getRecentBackups(),
            'role_signup_enabled' => config('app.role_signup_enabled'),
        ];
        return view('admin.settings.index', compact('settings'));
    }
    // Ajout de la méthode pour gérer le paramètre rôle à l'inscription
    public function updateRoleSignup(Request $request)
    {
        $enabled = $request->has('role_signup_enabled') ? 'true' : 'false';
        // Met à jour le fichier .env
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            if (preg_match('/^ROLE_SIGNUP_ENABLED=.*/m', $envContent)) {
                $envContent = preg_replace('/^ROLE_SIGNUP_ENABLED=.*/m', 'ROLE_SIGNUP_ENABLED=' . $enabled, $envContent);
            } else {
                $envContent .= "\nROLE_SIGNUP_ENABLED=" . $enabled;
            }
            file_put_contents($envPath, $envContent);
        }
        // Vide le cache config pour prendre en compte la modif
        Artisan::call('config:clear');
        return back()->with('success', 'Paramètre mis à jour !');
    }

    public function maintenance(Request $request)
    {
        if ($request->action === 'enable') {
            $cookieMsg = "Avant d'activer la maintenance, ouvrez la console JS de votre navigateur et copiez : document.cookie = 'laravel_down=true; path=/'";
            Artisan::call('down', [
                '--allow' => $ip
            ]);
            AdminNotification::createNotification(
                'Mode maintenance activé',
                'Le site est maintenant en mode maintenance.',
                'warning',
                'exclamation-triangle'
            );
            return back()->with('success', 'Mode maintenance activé!')->with('cookieMsg', $cookieMsg);
        } else {
            Artisan::call('up');
            AdminNotification::createNotification(
                'Mode maintenance désactivé',
                'Le site est de nouveau accessible au public.',
                'success',
                'check-circle'
            );
            return back()->with('success', 'Mode maintenance désactivé!');
        }
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        AdminNotification::createNotification(
            'Cache nettoyé',
            'Tous les caches du système ont été vidés.',
            'success',
            'trash'
        );

        return back()->with('success', 'Cache nettoyé avec succès!');
    }

    public function optimizeSystem()
    {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        AdminNotification::createNotification(
            'Système optimisé',
            'Le système a été optimisé pour de meilleures performances.',
            'success',
            'lightning-bolt'
        );

        return back()->with('success', 'Système optimisé avec succès!');
    }

    public function systemCleanup(Request $request)
    {
        // Suppression des notifications anciennes (exemple: >30j)
        \DB::table('admin_notifications')->where('created_at', '<', now()->subDays(30))->delete();
        // Suppression des sessions expirées
        \DB::table('sessions')->where('last_activity', '<', now()->subDays(7)->timestamp)->delete();
        // Nettoyage des logs (optionnel)
        foreach (glob(storage_path('logs/*.log')) as $logFile) {
            if (filemtime($logFile) < strtotime('-30 days')) {
                @unlink($logFile);
            }
        }
        AdminNotification::createNotification('Nettoyage système', 'Nettoyage automatique effectué.', 'success', 'broom');
        return back()->with('success', 'Nettoyage automatique effectué !');
    }

    public function createBackup(Request $request)
    {
        // Utilise Laravel backup si dispo, sinon simule
        try {
            \Artisan::call('backup:run');
            AdminNotification::createNotification('Sauvegarde', 'Sauvegarde complète créée.', 'success', 'cloud-upload');
            return back()->with('success', 'Sauvegarde complète créée !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la sauvegarde : ' . $e->getMessage());
        }
    }

    public function systemStats(Request $request)
    {
        $stats = [
            'users' => \App\Models\User::count(),
            'products' => \App\Models\Product::count(),
            'orders' => \App\Models\Order::count(),
            'notifications' => \App\Models\AdminNotification::count(),
            'free_space' => round(disk_free_space(base_path()) / 1024 / 1024 / 1024, 1) . ' GB',
        ];
        return response()->json($stats);
    }

    private function getStorageUsage()
    {
        return [
            'total' => '5.2 MB',
            'app' => '2.1 MB',
            'logs' => '1.5 MB',
        ];
    }

    private function getRecentBackups()
    {
        return [];
    }
}
