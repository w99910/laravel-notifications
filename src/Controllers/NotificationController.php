<?php

namespace Thomasbrillion\Notification\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Route;

class NotificationController
{
    public static function routes()
    {
        Route::group(['prefix' => config('notification.prefix', 'notifications'), 'middleware' => config('notification.middleware', 'auth')], function () {
            Route::get('/', [self::class, 'getNotifications'])->name('notifications.get');
            Route::get('/count', [self::class, 'getNotificationCount'])->name('notifications.count');
            Route::get('/unread', [self::class, 'getUnreadNotifications'])->name('notifications.unread');
            Route::get('/read', [self::class, 'getReadNotifications'])->name('notifications.read');

            Route::post('/read', [self::class, 'markAsRead'])->name('notifications.read');
            Route::post('/read-all', [self::class, 'markAllAsRead'])->name('notifications.markAllRead');
            Route::post('/delete', [self::class, 'deleteNotification'])->name('notifications.delete');
            Route::post('/delete-all', [self::class, 'deleteAllNotifications'])->name('notifications.deleteAll');
        });
    }

    protected NotificationService $notificationService;

    public function __construct(Request $request)
    {
        $this->notificationService = new \Thomasbrillion\Notification\Services\NotificationService($request->user());
    }

    public function getNotifications(Request $request)
    {
        return $this->notificationService->getNotifications($request->all());
    }

    public function getNotificationCount()
    {
        return $this->notificationService->getNotificationCount();
    }

    public function getUnreadNotifications()
    {
        return $this->notificationService->getUnreadNotifications();
    }

    public function getReadNotifications()
    {
        return $this->notificationService->getReadNotifications();
    }

    public function markAsRead(Request $request)
    {
        $notificationId = $request->input('id');
        return $this->notificationService->markAsRead($notificationId);
    }

    public function markAllAsRead()
    {
        return $this->notificationService->markAllAsRead();
    }

    public function deleteNotification(Request $request)
    {
        $notificationId = $request->input('id');
        return $this->notificationService->deleteNotification($notificationId);
    }

    public function deleteAllNotifications()
    {
        return $this->notificationService->deleteAllNotifications();
    }
}
