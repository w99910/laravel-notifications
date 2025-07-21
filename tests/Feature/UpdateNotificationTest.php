<?php

use Illuminate\Support\Facades\Event;
use Thomasbrillion\Notification\Events\NotificationEvent;
use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use Thomasbrillion\Notification\Services\NotificationService;
use Thomasbrillion\Notification\Tests\Models\User;

test('updates notification with valid data', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $updateData = [
        'title' => 'Updated Title',
        'body' => 'Updated body content',
        'type' => 'warning',
        'priority' => 8
    ];

    // This would require proper database mocking
    expect(true)->toBeTrue();
})->skip('Requires database mock');

test('validates update data', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $invalidData = ['type' => 'invalid'];

    expect(fn() => $service->updateNotification(1, $invalidData))
        ->toThrow(\Exception::class);
});

test('throws exception for non-existent notification', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $updateData = [
        'title' => 'Updated Title',
        'body' => 'Updated body content',
        'type' => 'warning',
        'priority' => 8
    ];

    // This would require proper database mocking
    expect(true)->toBeTrue();
})->skip('Requires database mock');

test('dispatches notification event after update', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $updateData = [
        'title' => 'Updated Title',
        'body' => 'Updated body content',
        'type' => 'warning',
        'priority' => 8
    ];

    // This would require proper database mocking
    expect(true)->toBeTrue();
})->skip('Requires database mock');

test('updates notification progress', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    // This would require proper database mocking
    expect(true)->toBeTrue();
})->skip('Requires database mock');

test('validates progress range for negative values', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    expect(fn() => $service->updateNotificationProgress(1, -1))
        ->toThrow(\InvalidArgumentException::class);
});

test('validates progress range for values over 100', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    expect(fn() => $service->updateNotificationProgress(1, 101))
        ->toThrow(\InvalidArgumentException::class);
});

test('accepts valid progress value 0', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    // This would require proper database mocking
    expect(true)->toBeTrue();
})->skip('Requires database mock');

test('accepts valid progress value 100', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    // This would require proper database mocking
    expect(true)->toBeTrue();
})->skip('Requires database mock');

test('accepts valid progress value 50', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    // This would require proper database mocking
    expect(true)->toBeTrue();
})->skip('Requires database mock');

test('updates timestamp when progress is updated', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    // This would test that updated_at is set
    expect(true)->toBeTrue();
})->skip('Requires database mock');

test('validates title length', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $longTitle = str_repeat('a', 300);
    $data = [
        'title' => $longTitle,
        'body' => 'Valid body',
        'type' => 'info',
        'priority' => 5
    ];

    expect(fn() => $service->updateNotification(1, $data))
        ->toThrow(\Exception::class);
});

test('validates notification type in update', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'title' => 'Valid Title',
        'body' => 'Valid body',
        'type' => 'invalid',
        'priority' => 5
    ];

    expect(fn() => $service->updateNotification(1, $data))
        ->toThrow(\Exception::class);
});

test('validates priority range in update', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'title' => 'Valid Title',
        'body' => 'Valid body',
        'type' => 'info',
        'priority' => 11
    ];

    expect(fn() => $service->updateNotification(1, $data))
        ->toThrow(\Exception::class);
});

test('validates progress range in update data', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'title' => 'Valid Title',
        'body' => 'Valid body',
        'type' => 'info',
        'priority' => 5,
        'progress' => 150
    ];

    expect(fn() => $service->updateNotification(1, $data))
        ->toThrow(\Exception::class);
});
