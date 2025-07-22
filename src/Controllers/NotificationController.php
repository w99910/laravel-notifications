<?php

namespace Thomasbrillion\Notification\Controllers;

use Illuminate\Http\Request;
use Thomasbrillion\Notification\Services\NotificationService;
use \Illuminate\Support\Facades\Route;

class NotificationController
{
    public static function routes()
    {
        Route::group(['prefix' => config('notification.prefix', 'notifications'), 'middleware' => config('notification.middleware', 'web')], function () {
            Route::get('/user', [self::class, 'getUserId'])->name('notifications.user');
            Route::get('/', [self::class, 'getNotifications'])->name('notifications.get');
            Route::get('/count', [self::class, 'getNotificationCount'])->name('notifications.count');
            Route::get('/unread', [self::class, 'getUnreadNotifications'])->name('notifications.unread');
            Route::get('/read', [self::class, 'getReadNotifications'])->name('notifications.read.get');

            Route::post('/read', [self::class, 'markAsRead'])->name('notifications.read.post');
            Route::post('/read-all', [self::class, 'markAllAsRead'])->name('notifications.markAllRead');
            Route::post('/delete', [self::class, 'deleteNotification'])->name('notifications.delete');
            Route::post('/delete-all', [self::class, 'deleteAllNotifications'])->name('notifications.deleteAll');
        });
    }

    public function getUserId(Request $request)
    {
        $user = $request->user() ?? \Auth::user();
        if (!$user) {
            throw new \InvalidArgumentException('User not authenticated. Please ensure you are logged in.');
        }
        return $user->getKey() ?? $user->id ?? $user->getAuthIdentifier();
    }

    public function getNotifications(Request $request, NotificationService $notificationService)
    {
        return $notificationService->getNotifications($request->all());
    }

    public function getNotificationCount(Request $request, NotificationService $notificationService)
    {
        return $notificationService->getNotificationCount();
    }

    public function getUnreadNotifications(Request $request, NotificationService $notificationService)
    {
        return $notificationService->getUnreadNotifications();
    }

    public function getReadNotifications(Request $request, NotificationService $notificationService)
    {
        return $notificationService->getReadNotifications();
    }

    public function markAsRead(Request $request, NotificationService $notificationService)
    {
        $notificationId = $request->input('id');
        return $notificationService->markAsRead($notificationId);
    }

    public function markAllAsRead(Request $request, NotificationService $notificationService)
    {
        return $notificationService->markAllAsRead();
    }

    public function deleteNotification(Request $request, NotificationService $notificationService)
    {
        $notificationId = $request->input('id');
        return $notificationService->deleteNotification($notificationId);
    }

    public function deleteAllNotifications(Request $request, NotificationService $notificationService)
    {
        return $notificationService->deleteAllNotifications();
    }
}
