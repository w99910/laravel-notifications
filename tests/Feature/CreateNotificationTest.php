<?php

use Illuminate\Support\Facades\Event;
use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use Thomasbrillion\Notification\Models\Notification;
use Thomasbrillion\Notification\Notifications\NotificationEvent;
use Thomasbrillion\Notification\Services\NotificationService;
use Thomasbrillion\Notification\Tests\Models\User;

test('creates notification with valid data', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $validData = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'info',
        'priority' => 5
    ];

    $result = $service->createNotification($validData);

    expect($result)
        ->toBeInstanceOf(NotificationInterface::class)
        ->and($result->title)
        ->toBe('Test Notification')
        ->and($result->message)
        ->toBe('This is a test notification')
        ->and($result->status)
        ->toBe('info')
        ->and($result->priority)
        ->toBe(5);
});

test('expect to throw when creating notification with invalid data', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $invalidData = [
        'title' => 'Test Notification',
        // 'message' is missing
        'status' => 'info',
        'priority' => 5,
        'actions' => ['action1', 'action2']  // Invalid actions format
    ];

    expect(fn() => $service->createNotification($invalidData))
        ->toThrow(\Exception::class);
});

test('creates notification with all optional fields', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);
    $actions = [[
        'label' => 'action1',
        'url' => 'http://example.com/action1'
    ], [
        'label' => 'action2',
        'url' => 'http://example.com/action2'
    ]];
    $data = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'info',
        'priority' => 5,
        'category' => 'something',
        'avatar' => 'bell',
        'actions' => $actions,
        'progress' => 50,
        'attachment' => 'path/to/attachment.pdf'
    ];

    $result = $service->createNotification($data);

    expect($result)
        ->toBeInstanceOf(NotificationInterface::class)
        ->and($result->category)
        ->toBe('something')
        ->and($result->avatar)
        ->toBe('bell')
        ->and($result->actions)
        ->toBe($actions)
        ->and($result->progress)
        ->toBe(50)
        ->and($result->attachment)
        ->toBe('path/to/attachment.pdf');
});

test('sets default priority when not provided', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'info'
    ];

    $result = $service->createNotification($data);

    expect($result->priority)->toBe(5);  // Assuming default is 5
});

test('sets user_id from service user', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'info',
        'priority' => 5
    ];

    $result = $service->createNotification($data);

    expect($result->user_id)->toBe(1);
});

test('validation fails when title is missing', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'message' => 'This is a test notification',
        'status' => 'info',
        'priority' => 5
    ];

    expect(fn() => $service->createNotification($data))
        ->toThrow(\Exception::class);
});

test('validation fails when message is missing', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'title' => 'Test Notification',
        'status' => 'info',
        'priority' => 5
    ];

    expect(fn() => $service->createNotification($data))
        ->toThrow(\Exception::class);
});

test('validation fails when type is invalid', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'invalid',
        'priority' => 5
    ];

    expect(fn() => $service->createNotification($data))
        ->toThrow(\Exception::class);
});

test('validation fails when priority is out of range', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'info',
        'priority' => 11
    ];

    expect(fn() => $service->createNotification($data))
        ->toThrow(\Exception::class);
});

test('validation fails when progress is out of range', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'info',
        'priority' => 5,
        'progress' => 101
    ];

    expect(fn() => $service->createNotification($data))
        ->toThrow(\Exception::class);
});

test('creates info notification', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'info',
        'priority' => 5
    ];

    $result = $service->createNotification($data);

    expect($result->status)->toBe('info');
});

test('creates warning notification', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'warning',
        'priority' => 5
    ];

    $result = $service->createNotification($data);

    expect($result->status)->toBe('warning');
});

test('creates error notification', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'error',
        'priority' => 5
    ];

    $result = $service->createNotification($data);

    expect($result->status)->toBe('error');
});

test('creates success notification', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'success',
        'priority' => 5
    ];

    $result = $service->createNotification($data);

    expect($result->status)->toBe('success');
});

test('creates notification with attachment', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification with attachment',
        'status' => 'info',
        'priority' => 5,
        'attachment' => '/path/to/document.pdf'
    ];

    $result = $service->createNotification($data);

    expect($result)
        ->toBeInstanceOf(NotificationInterface::class)
        ->and($result->attachment)
        ->toBe('/path/to/document.pdf');
});

test('creates notification with category and avatar', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $data = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'info',
        'priority' => 5,
        'category' => 'something',
        'avatar' => 'user-icon.png'
    ];

    $result = $service->createNotification($data);

    expect($result)
        ->toBeInstanceOf(NotificationInterface::class)
        ->and($result->category)
        ->toBe('something')
        ->and($result->avatar)
        ->toBe('user-icon.png');
});

test('creates notification with valid actions array', function () {
    Event::fake();

    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $actions = [
        ['label' => 'View', 'url' => 'https://example.com/view'],
        ['label' => 'Edit', 'url' => 'https://example.com/edit']
    ];

    $data = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification',
        'status' => 'info',
        'priority' => 5,
        'actions' => $actions
    ];

    $result = $service->createNotification($data);

    expect($result)
        ->toBeInstanceOf(NotificationInterface::class)
        ->and($result->actions)
        ->toBe($actions);
});
