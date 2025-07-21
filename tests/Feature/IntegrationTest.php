<?php

use Illuminate\Support\Facades\Event;
use Thomasbrillion\Notification\Events\NotificationEvent;
use Thomasbrillion\Notification\Services\NotificationService;
use Thomasbrillion\Notification\Tests\Models\User;

test('complete notification lifecycle - create, send, and manage', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    // Create notification
    $data = [
        'title' => 'Lifecycle Test',
        'body' => 'Testing complete notification lifecycle',
        'type' => 'info',
        'priority' => 5,
        'persistent' => true
    ];

    $notification = $service->createNotification($data);

    expect($notification->title)
        ->toBe('Lifecycle Test')
        ->and($notification->body)
        ->toBe('Testing complete notification lifecycle')
        ->and($notification->type)
        ->toBe('info')
        ->and($notification->priority)
        ->toBe(5)
        ->and($notification->persistent)
        ->toBeTrue();

    // Send notification
    $result = $service->sendNotification($notification);

    expect($result)->toBeTrue();
    Event::assertDispatched(NotificationEvent::class);
});

test('handles multiple notifications for same user', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $notifications = [];

    for ($i = 1; $i <= 3; $i++) {
        $data = [
            'title' => "Notification $i",
            'body' => "Body for notification $i",
            'type' => 'info',
            'priority' => $i,
            'persistent' => false
        ];

        $notification = $service->createNotification($data);
        $notifications[] = $notification;

        expect($notification->title)
            ->toBe("Notification $i")
            ->and($notification->priority)
            ->toBe($i);
    }

    // Send all notifications
    foreach ($notifications as $notification) {
        $service->sendNotification($notification);
    }

    Event::assertDispatchedTimes(NotificationEvent::class, 3);
});

test('handles different notification types', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $types = ['info', 'warning', 'error', 'success'];

    foreach ($types as $type) {
        $data = [
            'title' => ucfirst($type) . ' Notification',
            'body' => "This is a $type notification",
            'type' => $type,
            'priority' => 5,
            'persistent' => false
        ];

        $notification = $service->createNotification($data);

        expect($notification->type)->toBe($type);

        $service->sendNotification($notification);
    }

    Event::assertDispatchedTimes(NotificationEvent::class, 4);
});

test('handles notifications with all optional fields', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'title' => 'Complete Notification',
        'body' => 'Notification with all fields',
        'type' => 'success',
        'priority' => 8,
        'persistent' => true,
        'topic_id' => 456,
        'icon' => 'check-circle',
        'actions' => ['view', 'dismiss', 'archive'],
        'progress' => 100,
        'error' => null
    ];

    $notification = $service->createNotification($data);

    expect($notification->title)
        ->toBe('Complete Notification')
        ->and($notification->body)
        ->toBe('Notification with all fields')
        ->and($notification->type)
        ->toBe('success')
        ->and($notification->priority)
        ->toBe(8)
        ->and($notification->persistent)
        ->toBeTrue()
        ->and($notification->topic_id)
        ->toBe(456)
        ->and($notification->icon)
        ->toBe('check-circle')
        ->and($notification->actions)
        ->toBe(['view', 'dismiss', 'archive'])
        ->and($notification->progress)
        ->toBe(100)
        ->and($notification->error)
        ->toBeNull();

    $service->sendNotification($notification);

    Event::assertDispatched(NotificationEvent::class);
});

test('handles validation errors gracefully', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $invalidData = [
        'title' => '',  // Empty title
        'body' => 'Valid body',
        'type' => 'invalid',  // Invalid type
        'priority' => 15  // Out of range
    ];

    expect(fn() => $service->createNotification($invalidData))
        ->toThrow(\Exception::class);
});

test('handles missing required fields', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $incompleteData = [
        'title' => 'Valid Title'
        // Missing body and type
    ];

    expect(fn() => $service->createNotification($incompleteData))
        ->toThrow(\Exception::class);
});

test('handles rapid notification creation', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $notifications = [];

    for ($i = 1; $i <= 10; $i++) {
        $data = [
            'title' => "Rapid $i",
            'body' => "Body $i",
            'type' => 'info',
            'priority' => ($i % 10) + 1,
            'persistent' => $i % 2 === 0
        ];

        $notification = $service->createNotification($data);
        $notifications[] = $notification;
    }

    expect(count($notifications))->toBe(10);

    // Send all at once
    foreach ($notifications as $notification) {
        $service->sendNotification($notification);
    }

    Event::assertDispatchedTimes(NotificationEvent::class, 10);
});

test('handles edge case values', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $edgeCases = [
        [
            'title' => str_repeat('a', 255),  // Max length title
            'body' => str_repeat('b', 1000),  // Long body
            'type' => 'error',
            'priority' => 1,  // Min priority
            'persistent' => false
        ],
        [
            'title' => 'Min Priority',
            'body' => 'Testing minimum priority',
            'type' => 'success',
            'priority' => 10,  // Max priority
            'persistent' => true,
            'progress' => 0  // Min progress
        ],
        [
            'title' => 'Max Progress',
            'body' => 'Testing maximum progress',
            'type' => 'warning',
            'priority' => 5,
            'persistent' => false,
            'progress' => 100  // Max progress
        ]
    ];

    foreach ($edgeCases as $data) {
        $notification = $service->createNotification($data);

        expect($notification->title)
            ->toBe($data['title'])
            ->and($notification->body)
            ->toBe($data['body'])
            ->and($notification->type)
            ->toBe($data['type'])
            ->and($notification->priority)
            ->toBe($data['priority'])
            ->and($notification->persistent)
            ->toBe($data['persistent']);

        if (isset($data['progress'])) {
            expect($notification->progress)->toBe($data['progress']);
        }
    }
});
