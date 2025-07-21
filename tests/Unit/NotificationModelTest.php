<?php

use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use Thomasbrillion\Notification\Models\Notification;

test('implements NotificationInterface', function () {
    $data = [
        'id' => 1,
        'title' => 'Test Notification',
        'body' => 'This is a test notification body',
        'user_id' => 1,
        'type' => 'info',
        'priority' => 5,
        'topic_id' => 123,
        'persistent' => true,
        'icon' => 'bell',
        'read_at' => null,
        'created_at' => now(),
        'actions' => ['view', 'dismiss'],
        'progress' => 75,
        'error' => null
    ];

    $notification = new Notification($data);

    expect($notification)->toBeInstanceOf(NotificationInterface::class);
});

test('has correct fillable attributes', function () {
    $notification = new Notification();

    $expected = [
        'title',
        'body',
        'user_id',
        'type',
        'priority',
        'topic_id',
        'persistent',
        'icon',
        'read_at',
        'created_at',
        'actions',
        'progress',
        'error'
    ];

    expect($notification->getFillable())->toBe($expected);
});

test('returns correct title', function () {
    $notification = new Notification(['title' => 'Test Notification']);

    expect($notification->getTitle())->toBe('Test Notification');
});

test('returns correct body', function () {
    $notification = new Notification(['body' => 'This is a test notification body']);

    expect($notification->getBody())->toBe('This is a test notification body');
});

test('returns correct user ID', function () {
    $notification = new Notification(['user_id' => 1]);

    expect($notification->getUserId())->toBe(1);
});

test('returns correct type', function () {
    $notification = new Notification(['type' => 'info']);

    expect($notification->getType())->toBe('info');
});

test('returns correct priority', function () {
    $notification = new Notification(['priority' => 5]);

    expect($notification->getPriority())->toBe(5);
});

test('can be created with minimal data', function () {
    $minimal = new Notification([
        'title' => 'Minimal',
        'body' => 'Body',
        'user_id' => 1,
        'type' => 'info',
        'priority' => 1
    ]);

    expect($minimal->title)
        ->toBe('Minimal')
        ->and($minimal->body)
        ->toBe('Body')
        ->and($minimal->user_id)
        ->toBe(1)
        ->and($minimal->type)
        ->toBe('info')
        ->and($minimal->priority)
        ->toBe(1);
});

test('handles null values correctly', function () {
    $notification = new Notification([
        'title' => 'Test',
        'body' => 'Body',
        'user_id' => 1,
        'type' => 'info',
        'priority' => 1,
        'topic_id' => null,
        'icon' => null,
        'read_at' => null,
        'actions' => null,
        'progress' => null,
        'error' => null
    ]);

    expect($notification->topic_id)
        ->toBeNull()
        ->and($notification->icon)
        ->toBeNull()
        ->and($notification->read_at)
        ->toBeNull()
        ->and($notification->actions)
        ->toBeNull()
        ->and($notification->progress)
        ->toBeNull()
        ->and($notification->error)
        ->toBeNull();
});

test('handles boolean persistent attribute', function () {
    $persistent = new Notification([
        'title' => 'Test',
        'body' => 'Body',
        'user_id' => 1,
        'type' => 'info',
        'priority' => 1,
        'persistent' => true
    ]);

    $nonPersistent = new Notification([
        'title' => 'Test',
        'body' => 'Body',
        'user_id' => 1,
        'type' => 'info',
        'priority' => 1,
        'persistent' => false
    ]);

    expect($persistent->persistent)
        ->toBeTrue()
        ->and($nonPersistent->persistent)
        ->toBeFalse();
});

test('handles array actions attribute', function () {
    $actions = ['view', 'dismiss', 'archive'];
    $notification = new Notification([
        'title' => 'Test',
        'body' => 'Body',
        'user_id' => 1,
        'type' => 'info',
        'priority' => 1,
        'actions' => $actions
    ]);

    expect($notification->actions)->toBe($actions);
});

test('handles progress attribute', function () {
    $notification = new Notification([
        'title' => 'Test',
        'body' => 'Body',
        'user_id' => 1,
        'type' => 'info',
        'priority' => 1,
        'progress' => 50
    ]);

    expect($notification->progress)->toBe(50);
});

test('provides all required interface methods', function () {
    $notification = new Notification();

    expect(method_exists($notification, 'getTitle'))
        ->toBeTrue()
        ->and(method_exists($notification, 'getBody'))
        ->toBeTrue()
        ->and(method_exists($notification, 'getUserId'))
        ->toBeTrue()
        ->and(method_exists($notification, 'getType'))
        ->toBeTrue()
        ->and(method_exists($notification, 'getPriority'))
        ->toBeTrue();
});
