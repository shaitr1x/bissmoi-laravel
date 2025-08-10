<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seo && $seo->meta_title ? $seo->meta_title : config('app.name', 'Bissmoi') }}</title>
    @if($seo && $seo->meta_description)
        <meta name="description" content="{{ $seo->meta_description }}">
    @endif
    @if($seo && $seo->meta_keywords)
        <meta name="keywords" content="{{ $seo->meta_keywords }}">
    @endif
    {!! $seo && $seo->custom_head ? $seo->custom_head : '' !!}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    @auth
        <script>window.Laravel = {userId: {{ Auth::id() }}, isAdmin: {{ Auth::user() && Auth::user()->isAdmin() ? 'true' : 'false' }}};</script>
    @endauth
    <div class="min-h-screen">
        @include('layouts.navigation')
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
