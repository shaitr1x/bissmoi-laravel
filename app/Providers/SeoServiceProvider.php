<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SeoSetting;

class SeoServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('components.app-layout', function ($view) {
            $seo = SeoSetting::first();
            $view->with('seo', $seo);
        });
    }
}
