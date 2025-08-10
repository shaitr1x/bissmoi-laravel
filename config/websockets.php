<?php

return [
    'apps' => [
        [
            'id' => env('PUSHER_APP_ID', 'local-app'),
            'name' => env('APP_NAME', 'Bissmoi'),
            'key' => env('PUSHER_APP_KEY', 'local-key'),
            'secret' => env('PUSHER_APP_SECRET', 'local-secret'),
            'path' => env('PUSHER_APP_PATH'),
            'capacity' => null,
            'enable_client_messages' => false,
            'enable_statistics' => true,
        ],
    ],
    'dashboard' => [
        'port' => env('WEBSOCKETS_PORT', 6001),
    ],
    'ssl' => [
        'local_cert' => null,
        'local_pk' => null,
        'passphrase' => null,
        'verify_peer' => false,
    ],
    'max_request_size_in_kb' => 250,
    'path' => 'ws',
    'middleware' => [
        'web',
    ],
    'statistics' => [
        'model' => \BeyondCode\LaravelWebSockets\Statistics\Models\WebSocketsStatisticsEntry::class,
        'logger' => \BeyondCode\LaravelWebSockets\Statistics\Logger\WebsocketsLogger::class,
        'interval_in_seconds' => 60,
        'delete_statistics_older_than_days' => 60,
    ],
];
