<?php

use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use Thomasbrillion\Notification\Models\Notification;

test('implements NotificationInterface', function () {
    $data = [
        'id' => 1,
        'title' => 'Test Notification',
        'message' => 'This is a test notification message',
        'user_id' => 1,
        'status' => 'info',
        'priority' => 5,
        'category' => 123,
        'avatar' => 'bell',
        'read_at' => null,
        'created_at' => now(),
        'actions' => [['label' => 'view', 'url' => 'test.com'], ['label' => 'dismiss', 'url' => 'test2.com']],
        'progress' => 75,
        'attachment' => 'path/to/file.pdf'
    ];

    $notification = new Notification($data);

    expect($notification)->toBeInstanceOf(NotificationInterface::class);
});

test('has correct fillable attributes', function () {
    $notification = new Notification();

    $expected = [
        'title',
        'message',
        'user_id',
        'status',
        'priority',
        'category',
        'avatar',
        'read_at',
        'created_at',
        'actions',
        'progress',
        'attachment',
    ];

    expect($notification->getFillable())->toBe($expected);
});

test('returns correct title', function () {
    $notification = new Notification(['title' => 'Test Notification']);

    expect($notification->getTitle())->toBe('Test Notification');
});

test('returns correct message', function () {
    $notification = new Notification(['message' => 'This is a test notification message']);

    expect($notification->getMessage())->toBe('This is a test notification message');
});

test('returns correct user ID', function () {
    $notification = new Notification(['user_id' => 1]);

    expect($notification->getUserId())->toBe(1);
});

test('returns correct status', function () {
    $notification = new Notification(['status' => 'info']);

    expect($notification->getStatus())->toBe('info');
});

test('returns correct priority', function () {
    $notification = new Notification(['priority' => 5]);

    expect($notification->getPriority())->toBe(5);
});

test('can be created with minimal data', function () {
    $minimal = new Notification([
        'title' => 'Minimal',
        'message' => 'Message',
        'user_id' => 1,
        'status' => 'info',
        'priority' => 1
    ]);

    expect($minimal->title)
        ->toBe('Minimal')
        ->and($minimal->message)
        ->toBe('Message')
        ->and($minimal->user_id)
        ->toBe(1)
        ->and($minimal->status)
        ->toBe('info')
        ->and($minimal->priority)
        ->toBe(1);
});

test('handles null values correctly', function () {
    $notification = new Notification([
        'title' => 'Test',
        'message' => 'Message',
        'user_id' => 1,
        'status' => 'info',
        'priority' => 1,
        'category' => null,
        'avatar' => null,
        'read_at' => null,
        'actions' => null,
        'progress' => null,
        'attachment' => null
    ]);

    expect($notification->category)
        ->toBeNull()
        ->and($notification->avatar)
        ->toBeNull()
        ->and($notification->read_at)
        ->toBeNull()
        ->and($notification->actions)
        ->toBeNull()
        ->and($notification->progress)
        ->toBeNull()
        ->and($notification->attachment)
        ->toBeNull();
});

test('handles array actions attribute', function () {
    $actions = [
        ['label' => 'view', 'url' => 'https://example.com/view'],
        ['label' => 'dismiss', 'url' => 'https://example.com/dismiss'],
        ['label' => 'archive', 'url' => 'https://example.com/archive']
    ];
    $notification = new Notification([
        'title' => 'Test',
        'message' => 'Message',
        'user_id' => 1,
        'status' => 'info',
        'priority' => 1,
        'actions' => $actions
    ]);

    expect($notification->actions)->toBe($actions);
});

test('handles progress attribute', function () {
    $notification = new Notification([
        'title' => 'Test',
        'message' => 'Message',
        'user_id' => 1,
        'status' => 'info',
        'priority' => 1,
        'progress' => 50
    ]);

    expect($notification->progress)->toBe(50);
});

test('provides all required interface methods', function () {
    $notification = new Notification();

    expect(method_exists($notification, 'getTitle'))
        ->toBeTrue()
        ->and(method_exists($notification, 'getMessage'))
        ->toBeTrue()
        ->and(method_exists($notification, 'getUserId'))
        ->toBeTrue()
        ->and(method_exists($notification, 'getStatus'))
        ->toBeTrue()
        ->and(method_exists($notification, 'getPriority'))
        ->toBeTrue()
        ->and(method_exists($notification, 'getCategory'))
        ->toBeTrue()
        ->and(method_exists($notification, 'getAvatar'))
        ->toBeTrue()
        ->and(method_exists($notification, 'getActions'))
        ->toBeTrue()
        ->and(method_exists($notification, 'getProgress'))
        ->toBeTrue()
        ->and(method_exists($notification, 'getAttachment'))
        ->toBeTrue();
});

test('handles attachment attribute', function () {
    $notification = new Notification([
        'title' => 'Test',
        'message' => 'Message',
        'user_id' => 1,
        'status' => 'info',
        'priority' => 1,
        'attachment' => '/path/to/file.pdf'
    ]);

    expect($notification->attachment)->toBe('/path/to/file.pdf');
});

test('handles category and avatar attributes', function () {
    $notification = new Notification([
        'title' => 'Test',
        'message' => 'Message',
        'user_id' => 1,
        'status' => 'info',
        'priority' => 1,
        'category' => 42,
        'avatar' => 'user.png'
    ]);

    expect($notification->category)
        ->toBe(42)
        ->and($notification->avatar)
        ->toBe('user.png');
});
