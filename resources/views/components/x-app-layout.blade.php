<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Bissmoi') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    @auth
    <div class="min-h-screen">
        @include('layouts.navigation')
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
