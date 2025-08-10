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
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function maintenance(Request $request)
    {
        if ($request->action === 'enable') {
            Artisan::call('down', ['--message' => 'Maintenance en cours...']);
            AdminNotification::createNotification(
                'Mode maintenance activé',
                'Le site est maintenant en mode maintenance.',
                'warning',
                'exclamation-triangle'
            );
            return back()->with('success', 'Mode maintenance activé!');
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
