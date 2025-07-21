<?php

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Thomasbrillion\Notification\Events\NotificationEvent;
use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use Thomasbrillion\Notification\Models\Notification;
use Thomasbrillion\Notification\Tests\Models\User;

test('can be instantiated with notification', function () {
    $user = new User(['id' => 1]);
    $notification = new Notification([
        'id' => 1,
        'title' => 'Test Notification',
        'body' => 'Test body',
        'type' => 'info',
        'priority' => 5,
        'user_id' => 1,
        'persistent' => false
    ]);

    $event = new NotificationEvent($notification);

    expect($event)
        ->toBeInstanceOf(NotificationEvent::class)
        ->and($event->notification)
        ->toBe($notification);
});

test('can be instantiated with custom ws name', function () {
    $user = new User(['id' => 1]);
    $notification = new Notification([
        'id' => 1,
        'title' => 'Test Notification',
        'body' => 'Test body',
        'type' => 'info',
        'priority' => 5,
        'user_id' => 1,
        'persistent' => false
    ]);

    $event = new NotificationEvent($notification, 'custom.event');

    expect($event)
        ->toBeInstanceOf(NotificationEvent::class)
        ->and($event->notification)
        ->toBe($notification);
});

test('implements ShouldBroadcast interface', function () {
    $notification = new Notification([
        'id' => 1,
        'title' => 'Test Notification',
        'body' => 'Test body',
        'type' => 'info',
        'priority' => 5,
        'user_id' => 1,
        'persistent' => false
    ]);

    $event = new NotificationEvent($notification);

    expect($event)->toBeInstanceOf(\Illuminate\Contracts\Broadcasting\ShouldBroadcast::class);
});

test('generates WebSocket channel for user', function () {
    $notification = new Notification([
        'id' => 1,
        'title' => 'Test Notification',
        'body' => 'Test body',
        'type' => 'info',
        'priority' => 5,
        'user_id' => 1,
        'persistent' => false
    ]);

    $event = new NotificationEvent($notification);

    expect($event->broadcastOn())->toBeInstanceOf(Channel::class);
});

test('uses correct channel name pattern', function () {
    $notification = new Notification([
        'id' => 1,
        'title' => 'Test Notification',
        'body' => 'Test body',
        'type' => 'info',
        'priority' => 5,
        'user_id' => 1,
        'persistent' => false
    ]);

    $event = new NotificationEvent($notification);
    $channels = $event->broadcastOn();

    if (is_array($channels)) {
        $channel = $channels[0];
    } else {
        $channel = $channels;
    }

    expect($channel->name)->toStartWith('users.');
});

test('serializes notification data correctly', function () {
    $notification = new Notification([
        'id' => 1,
        'title' => 'Test Notification',
        'body' => 'Test body',
        'type' => 'info',
        'priority' => 5,
        'user_id' => 1,
        'persistent' => false
    ]);

    $event = new NotificationEvent($notification);

    expect($event->notification)
        ->toBeInstanceOf(NotificationInterface::class)
        ->and($event->notification->title)
        ->toBe('Test Notification')
        ->and($event->notification->body)
        ->toBe('Test body')
        ->and($event->notification->type)
        ->toBe('info');
});

test('handles different notification types', function () {
    $types = ['info', 'warning', 'error', 'success'];

    foreach ($types as $type) {
        $notification = new Notification([
            'id' => 1,
            'title' => 'Test',
            'body' => 'Test',
            'type' => $type,
            'priority' => 5,
            'user_id' => 1,
            'persistent' => false
        ]);

        $event = new NotificationEvent($notification);

        expect($event->notification->type)->toBe($type);
    }
});
