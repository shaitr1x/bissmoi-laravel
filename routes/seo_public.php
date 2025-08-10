<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Models\SeoSetting;

Route::get('/robots.txt', function () {
    $seo = SeoSetting::first();
    return Response::make($seo && $seo->robots_txt ? $seo->robots_txt : "User-agent: *\nDisallow:", 200, [
        'Content-Type' => 'text/plain',
    ]);
});

Route::get('/sitemap.xml', function () {
    $seo = SeoSetting::first();
    return Response::make($seo && $seo->sitemap_xml ? $seo->sitemap_xml : '', 200, [
        'Content-Type' => 'application/xml',
    ]);
});
