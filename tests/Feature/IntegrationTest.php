<?php

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Thomasbrillion\Notification\Notifications\NotificationEvent;
use Thomasbrillion\Notification\Services\NotificationService;
use Thomasbrillion\Notification\Tests\Models\User;

test('complete notification lifecycle - create, send, and manage', function () {
    Notification::fake();

    $user = new User(['id' => 1]);

    $service = new NotificationService($user);

    // Create notification
    $data = [
        'title' => 'Lifecycle Test',
        'message' => 'Testing complete notification lifecycle',
        'status' => 'info',
        'priority' => 5,
    ];

    $notification = $service->createNotification($data);

    expect($notification->title)
        ->toBe('Lifecycle Test')
        ->and($notification->message)
        ->toBe('Testing complete notification lifecycle')
        ->and($notification->status)
        ->toBe('info')
        ->and($notification->priority)
        ->toBe(5);

    // Send notification
    $result = $service->sendNotification($notification);

    expect($result)->toBeTrue();

    Notification::assertSentTo(
        [$user], NotificationEvent::class
    );
});

test('handles multiple notifications for same user', function () {
    Notification::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $notifications = [];

    for ($i = 1; $i <= 3; $i++) {
        $data = [
            'title' => "Notification $i",
            'message' => "Body for notification $i",
            'status' => 'info',
            'priority' => $i,
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

    Notification::assertSentTo(
        [$user], NotificationEvent::class
    );
});

test('handles different notification types', function () {
    Notification::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $types = ['info', 'warning', 'error', 'success'];

    foreach ($types as $type) {
        $data = [
            'title' => ucfirst($type) . ' Notification',
            'message' => "This is a $type notification",
            'status' => $type,
            'priority' => 5,
        ];

        $notification = $service->createNotification($data);

        expect($notification->status)->toBe($type);

        $service->sendNotification($notification);
    }

    Notification::assertSentTo(
        [$user], NotificationEvent::class
    );
});

test('handles notifications with all optional fields', function () {
    Notification::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $actions = [['label' => 'View', 'url' => 'http://example.com/view'], ['label' => 'Dismiss', 'url' => 'http://example.com/dismiss'], ['label' => 'Archive', 'url' => 'http://example.com/archive']];
    $data = [
        'title' => 'Complete Notification',
        'message' => 'Notification with all fields',
        'status' => 'success',
        'priority' => 8,
        'category' => 'something',
        'avatar' => 'check-circle',
        'actions' => $actions,
        'progress' => 100,
        'attachment' => null
    ];

    $notification = $service->createNotification($data);

    expect($notification->title)
        ->toBe('Complete Notification')
        ->and($notification->message)
        ->toBe('Notification with all fields')
        ->and($notification->status)
        ->toBe('success')
        ->and($notification->priority)
        ->toBe(8)
        ->and($notification->category)
        ->toBe('something')
        ->and($notification->avatar)
        ->toBe('check-circle')
        ->and($notification->actions)
        ->toBe($actions)
        ->and($notification->progress)
        ->toBe(100)
        ->and($notification->error)
        ->toBeNull();

    $service->sendNotification($notification);

    Notification::assertSentTo(
        [$user], NotificationEvent::class
    );
});

test('handles validation errors gracefully', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $invalidData = [
        'title' => '',  // Empty title
        'message' => 'Valid body',
        'status' => 'invalid',  // Invalid type
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
    Notification::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $notifications = [];

    for ($i = 1; $i <= 10; $i++) {
        $data = [
            'title' => "Rapid $i",
            'message' => "Body $i",
            'status' => 'info',
            'priority' => ($i % 10) + 1,
        ];

        $notification = $service->createNotification($data);
        $notifications[] = $notification;
    }

    expect(count($notifications))->toBe(10);

    // Send all at once
    foreach ($notifications as $notification) {
        $service->sendNotification($notification);
    }

    Notification::assertSentTo(
        [$user], NotificationEvent::class
    );
});

test('handles edge case values', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $edgeCases = [
        [
            'title' => str_repeat('a', 255),  // Max length title
            'message' => str_repeat('b', 1000),  // Long body
            'status' => 'error',
            'priority' => 1,  // Min priority
        ],
        [
            'title' => 'Min Priority',
            'message' => 'Testing minimum priority',
            'status' => 'success',
            'priority' => 10,  // Max priority
            'progress' => 0  // Min progress
        ],
        [
            'title' => 'Max Progress',
            'message' => 'Testing maximum progress',
            'status' => 'warning',
            'priority' => 5,
            'progress' => 100  // Max progress
        ]
    ];

    foreach ($edgeCases as $data) {
        $notification = $service->createNotification($data);

        expect($notification->title)
            ->toBe($data['title'])
            ->and($notification->message)
            ->toBe($data['message'])
            ->and($notification->status)
            ->toBe($data['status'])
            ->and($notification->priority)
            ->toBe($data['priority']);

        if (isset($data['progress'])) {
            expect($notification->progress)->toBe($data['progress']);
        }
    }
});
