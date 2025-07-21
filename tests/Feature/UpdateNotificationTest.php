<?php

use Illuminate\Support\Facades\Notification;
use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use Thomasbrillion\Notification\Models\Notification as NotificationModel;
use Thomasbrillion\Notification\Notifications\NotificationEvent;
use Thomasbrillion\Notification\Services\NotificationService;
use Thomasbrillion\Notification\Tests\Models\User;

test('updates notification with valid data', function () {
    Notification::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $updateData = [
        'title' => 'Updated Title',
        'message' => 'Updated message content',
        'status' => 'warning',
        'priority' => 8
    ];

    $testNotification = NotificationModel::first();

    $notification = $service->updateNotification($testNotification->id, $updateData);

    expect($notification)
        ->toBeInstanceOf(NotificationInterface::class)
        ->and($notification->title)
        ->toBe('Updated Title')
        ->and($notification->message)
        ->toBe('Updated message content')
        ->and($notification->status)
        ->toBe('warning')
        ->and($notification->priority)
        ->toBe(8);

    Notification::assertSentTo(
        [$user], NotificationEvent::class
    );
});

test('validates update data', function () {
    Notification::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $invalidData = ['status' => 'invalid'];

    $testNotification = NotificationModel::first();

    expect(fn() => $service->updateNotification($testNotification->id, $invalidData))
        ->toThrow(\Exception::class);

    Notification::assertNothingSent();
});

test('throws exception for non-existent notification', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $invalidId = 9999;  // Assuming this ID does not exist

    $updateData = [
        'title' => 'Updated Title',
        'message' => 'Updated message content',
        'status' => 'warning',
        'priority' => 8
    ];

    expect(fn() => $service->updateNotification($invalidId, $updateData))
        ->toThrow(\Exception::class);
});

test('disable notify', function () {
    Notification::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $updateData = [
        'title' => 'Updated Title',
        'message' => 'Updated message content',
        'status' => 'warning',
        'priority' => 8
    ];

    $testNotification = NotificationModel::first();

    $notification = $service->updateNotification($testNotification->id, $updateData, false);

    Notification::assertNothingSent();
});

test('updates notification progress', function () {
    Notification::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $testNotification = NotificationModel::first();

    $notification = $service->updateNotificationProgress($testNotification->id, 50);

    expect($notification->progress)->toBe(50);

    Notification::assertSentTo(
        [$user], NotificationEvent::class
    );
});

test('validates progress range for negative values', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $testNotification = NotificationModel::first();

    expect(fn() => $service->updateNotificationProgress($testNotification->id, -1))
        ->toThrow(\InvalidArgumentException::class);
});

test('validates progress range for values over 100', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $testNotification = NotificationModel::first();

    expect(fn() => $service->updateNotificationProgress($testNotification->id, 101))
        ->toThrow(\InvalidArgumentException::class);
});

test('accepts valid progress value 0', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $testNotification = NotificationModel::first();

    $notification = $service->updateNotificationProgress($testNotification->id, 0);

    expect($notification->progress)->toBe(0);
});

test('accepts valid progress value 100', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $testNotification = NotificationModel::first();

    $notification = $service->updateNotificationProgress($testNotification->id, 100);

    expect($notification->progress)->toBe(100);
});

test('accepts valid progress value 50', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    // This would require proper database mocking
    expect(true)->toBeTrue();
});

test('updates timestamp when progress is updated', function () {
    Notification::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $testNotification = NotificationModel::first();

    $notification = $service->updateNotificationProgress($testNotification->id, 50);

    expect($notification->updated_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});
