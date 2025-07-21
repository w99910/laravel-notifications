<?php

use Illuminate\Support\Facades\Event;
use Thomasbrillion\Notification\Services\NotificationService;
use Thomasbrillion\Notification\Tests\Models\User;

test('deletes notification', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    // Create a notification to delete
    $notification = $service->createNotification([
        'title' => 'Test Notification',
        'body' => 'This is a test notification',
        'type' => 'info',
        'priority' => 5,
        'persistent' => false
    ]);

    // This would require proper database mocking
    expect($service->deleteNotification($notification->id))->toBeTrue();
});

test('deletes all notifications for current user', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $notification = $service->createNotification([
        'title' => 'Test Notification',
        'body' => 'This is a test notification',
        'type' => 'info',
        'priority' => 5,
        'persistent' => false
    ]);

    $notification = $service->createNotification([
        'title' => 'Test Notification',
        'body' => 'This is a test notification',
        'type' => 'info',
        'priority' => 5,
        'persistent' => false
    ]);

    expect($service->getNotificationCount())->not->toBe(0);
    // This would test that the query includes user_id filter
    expect($service->deleteAllNotifications())->toBeTrue();
});
