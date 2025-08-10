<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\UserNotification;

Route::middleware('auth')->get('/notifications/unread-count', function () {
    $count = UserNotification::where('user_id', Auth::id())->where('is_read', false)->count();
    return Response::json(['count' => $count]);
});

Route::middleware('auth')->get('/notifications/recent', function () {
    $notifications = UserNotification::where('user_id', Auth::id())
        ->orderByDesc('created_at')
        ->take(10)
        ->get(['id', 'title', 'message', 'is_read', 'created_at']);
    return Response::json(['notifications' => $notifications]);
});
