<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Models\AdminNotification;

Route::middleware(['auth', 'admin'])->get('/admin/notifications/unread-count', function () {
    $count = AdminNotification::where('is_read', false)->count();
    return Response::json(['count' => $count]);

});

// Suppression groupée des notifications admin
Route::middleware(['auth', 'admin'])->post('/admin/notifications/delete-multiple', [\App\Http\Controllers\Admin\AdminController::class, 'deleteMultipleNotifications'])->name('admin.notifications.deleteMultiple');

Route::middleware(['auth', 'admin'])->get('/admin/notifications/recent', function () {
    $notifications = AdminNotification::orderByDesc('created_at')
        ->take(10)
        ->get(['id', 'title', 'message', 'is_read', 'created_at']);
    return Response::json(['notifications' => $notifications]);
});
