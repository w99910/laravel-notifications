<?php

use Thomasbrillion\Notification\Models\Notification as NotificationModel;
use Thomasbrillion\Notification\Services\NotificationService;
use Thomasbrillion\Notification\Tests\Models\User;

test('get notifications with order by descending', function () {
    $user = new User(['id' => 13]);
    $service = new NotificationService($user);

    $service->createNotification([
        'title' => 'First',
        'message' => 'First body',
        'status' => 'info',
        'user_id' => $user->id,
    ]);
    $service->createNotification([
        'title' => 'Second',
        'message' => 'Second body',
        'status' => 'info',
        'user_id' => $user->id,
    ]);

    $notifications = $service->getNotifications(['order_by' => 'id', 'order_direction' => 'desc']);
    expect($notifications[0]->title)->toBe('Second');
    expect($notifications[1]->title)->toBe('First');
});

test('get notifications with offset and limit', function () {
    $user = new User(['id' => 14]);
    $service = new NotificationService($user);

    foreach (range(1, 5) as $i) {
        $service->createNotification([
            'title' => "Notification $i",
            'message' => "Body $i",
            'status' => 'info',
            'user_id' => $user->id,
        ]);
    }

    $notifications = $service->getNotifications(['offset' => 2, 'limit' => 2]);

    expect($notifications)->toHaveCount(2);
    expect($notifications[0]->title)->toBe('Notification 3');
    expect($notifications[1]->title)->toBe('Notification 4');
});

test('get notifications with start_date and end_date', function () {
    $user = new User(['id' => 15]);
    $service = new NotificationService($user);

    $service->createNotification([
        'title' => 'Old Notification',
        'message' => 'Old',
        'status' => 'info',
        'user_id' => $user->id,
        'created_at' => now()->subDays(10),
    ]);
    $service->createNotification([
        'title' => 'Recent Notification',
        'message' => 'Recent',
        'status' => 'info',
        'user_id' => $user->id,
        'created_at' => now()->subDays(2),
    ]);

    $startDate = now()->subDays(5);
    $endDate = now();

    $notifications = $service->getNotifications([
        'start_date' => $startDate,
        'end_date' => $endDate,
    ]);
    expect($notifications)->toHaveCount(1);
    expect($notifications[0]->title)->toBe('Recent Notification');
});

test('get notifications with limit only', function () {
    $user = new User(['id' => 16]);
    $service = new NotificationService($user);

    foreach (range(1, 4) as $i) {
        $service->createNotification([
            'title' => "Notification $i",
            'message' => "Body $i",
            'status' => 'info',
            'user_id' => $user->id,
        ]);
    }

    $notifications = $service->getNotifications(['limit' => 2]);
    expect($notifications)->toHaveCount(2);
    expect($notifications[0]->title)->toBe('Notification 1');
    expect($notifications[1]->title)->toBe('Notification 2');
});

test('get notifications with priority filter', function () {
    $user = new User(['id' => 18]);
    $service = new NotificationService($user);

    $service->createNotification([
        'title' => 'Low Priority',
        'message' => 'Low',
        'status' => 'info',
        'user_id' => $user->id,
        'priority' => 1,
    ]);
    $service->createNotification([
        'title' => 'High Priority',
        'message' => 'High',
        'status' => 'info',
        'user_id' => $user->id,
        'priority' => 10,
    ]);

    $notifications = $service->getNotifications(['priority' => 10]);
    expect($notifications)->toHaveCount(1);
    expect($notifications[0]->title)->toBe('High Priority');
});

test('getReadNotifications returns only read notifications', function () {
    $user = new User(['id' => 24]);
    $service = new NotificationService($user);

    $notification = $service->createNotification([
        'title' => 'Read 1',
        'message' => 'Body',
        'status' => 'info',
        'user_id' => $user->id,
    ]);
    $service->createNotification([
        'title' => 'Unread 1',
        'message' => 'Body',
        'status' => 'info',
        'user_id' => $user->id,
    ]);

    // Mark the first notification as read
    $service->markAsRead($notification->id);

    $read = $service->getReadNotifications();

    expect($read)->toHaveCount(1);
    expect($read[0]->title)->toBe('Read 1');
});

test('getUnreadNotifications returns only unread notifications', function () {
    $user = new User(['id' => 25]);
    $service = new NotificationService($user);

    $service->createNotification([
        'title' => 'Unread 1',
        'message' => 'Body',
        'status' => 'info',
        'user_id' => $user->id,
    ]);
    $notification = $service->createNotification([
        'title' => 'Read 1',
        'message' => 'Body',
        'status' => 'info',
        'user_id' => $user->id,
    ]);
    // Mark the second notification as read
    $service->markAsRead($notification->id);

    $unread = $service->getUnreadNotifications();
    expect($unread)->toHaveCount(1);
    expect($unread[0]->title)->toBe('Unread 1');
});

test('readNotificationsCount returns correct count', function () {
    $user = new User(['id' => 26]);
    $service = new NotificationService($user);

    $notificationIds = [];
    foreach (range(1, 3) as $i) {
        $notification = $service->createNotification([
            'title' => "Notification $i",
            'message' => "Body $i",
            'status' => 'info',
            'user_id' => $user->id,
        ]);
        $notificationIds[] = $notification->id;
    }
    // Mark two notifications as read
    $service->markAsRead($notificationIds[0]);
    $service->markAsRead($notificationIds[1]);

    $count = $service->getReadNotificationsCount();
    expect($count)->toBe(2);
});

test('unreadNotificationsCount returns correct count', function () {
    $user = new User(['id' => 27]);
    $service = new NotificationService($user);

    $notificationIds = [];
    foreach (range(1, 4) as $i) {
        $notification = $service->createNotification([
            'title' => "Notification $i",
            'message' => "Body $i",
            'status' => 'info',
            'user_id' => $user->id,
        ]);
        $notificationIds[] = $notification->id;
    }
    // Mark one notification as read
    $service->markAsRead($notificationIds[0]);

    $count = $service->getUnreadNotificationsCount();
    expect($count)->toBe(3);
});
