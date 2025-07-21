<?php

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Thomasbrillion\Notification\Events\NotificationEvent;
use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use Thomasbrillion\Notification\Models\Notification;
use Thomasbrillion\Notification\Services\NotificationService;
use Thomasbrillion\Notification\Tests\Models\User;

test('sends notification and dispatches event', function () {
    Event::fake();
    Log::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $validData = [
        'title' => 'Test Notification',
        'body' => 'This is a test notification',
        'type' => 'info',
        'priority' => 5,
        'persistent' => false
    ];

    $notification = $service->createNotification($validData);

    $result = $service->sendNotification($notification);

    expect($result)->toBeTrue();
    Event::assertDispatched(NotificationEvent::class);
});

test('logs notification when sent', function () {
    Event::fake();
    Log::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $validData = [
        'title' => 'Test Notification',
        'body' => 'This is a test notification',
        'type' => 'info',
        'priority' => 5,
        'persistent' => false
    ];

    $notification = $service->createNotification($validData);

    $service->sendNotification($notification);

    Log::assertLogged('info', function ($message, $context) {
        return $message === 'Notification sent' &&
            $context['user_id'] === 1 &&
            $context['title'] === 'Test Notification';
    });
});

test('dispatches notification event with correct data', function () {
    Event::fake();
    Log::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $validData = [
        'title' => 'Test Notification',
        'body' => 'This is a test notification',
        'type' => 'info',
        'priority' => 5,
        'persistent' => false
    ];

    $notification = $service->createNotification($validData);

    $service->sendNotification($notification);

    Event::assertDispatched(NotificationEvent::class, function ($event) use ($notification) {
        return $event->notification->title === $notification->title &&
            $event->notification->body === $notification->body &&
            $event->notification->type === $notification->type;
    });
});

test('can send notification by ID', function () {
    // This would require proper database mocking
    expect(true)->toBeTrue();
})->skip('Requires database mock');

test('handles multiple notifications', function () {
    Event::fake();
    Log::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $validData = [
        'title' => 'Test Notification',
        'body' => 'This is a test notification',
        'type' => 'info',
        'priority' => 5,
        'persistent' => false
    ];

    $notification1 = $service->createNotification($validData);
    $notification2 = $service->createNotification(array_merge($validData, ['title' => 'Second']));

    $service->sendNotification($notification1);
    $service->sendNotification($notification2);

    Event::assertDispatchedTimes(NotificationEvent::class, 2);
});
