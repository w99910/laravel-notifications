<?php

use Illuminate\Support\Facades\Notification;
use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use Thomasbrillion\Notification\Models\Notification as NotificationModel;
use Thomasbrillion\Notification\Notifications\NotificationEvent;
use Thomasbrillion\Notification\Services\NotificationService;
use Thomasbrillion\Notification\Tests\Models\User;

test('sends notification and dispatches event', function () {
    Notification::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $validData = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'info',
        'priority' => 5
    ];

    $notification = $service->createNotification($validData);

    $result = $service->sendNotification($notification);

    expect($result)->toBeTrue();

    Notification::assertSentTo(
        [$user], NotificationEvent::class
    );
});

test('logs notification when sent', function () {
    Notification::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $validData = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'info',
        'priority' => 5
    ];

    $notification = $service->createNotification($validData);

    $service->sendNotification($notification);

    Notification::assertSentTo(
        [$user], NotificationEvent::class
    );
});

test('dispatches notification event with correct data', function () {
    Notification::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $validData = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'info',
        'priority' => 5
    ];

    $notification = $service->createNotification($validData);

    $service->sendNotification($notification);

    Notification::assertSentTo(
        [$user], NotificationEvent::class
    );
});

test('handles multiple notifications', function () {
    Notification::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $validData = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'info',
        'priority' => 5
    ];

    $notification1 = $service->createNotification($validData);
    $notification2 = $service->createNotification(array_merge($validData, ['title' => 'Second']));

    $service->sendNotification($notification1);
    $service->sendNotification($notification2);

    Notification::assertSentTo(
        [$user], NotificationEvent::class
    );
});
