<?php

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use Thomasbrillion\Notification\Models\Notification;
use Thomasbrillion\Notification\Notifications\NotificationEvent;
use Thomasbrillion\Notification\Services\NotificationService;
use Thomasbrillion\Notification\Tests\Models\User;

test('can be instantiated with User object', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    expect($service)->toBeInstanceOf(NotificationService::class);
});

test('can be instantiated with custom notification interface', function () {
    $user = new User(['id' => 1]);
    $notification = new Notification();
    $service = new NotificationService($user, $notification);

    expect($service)->toBeInstanceOf(NotificationService::class);
});

test('throws exception for invalid notification interface', function () {
    $user = new User(['id' => 1]);

    expect(fn() => new NotificationService($user, 'invalid'))
        ->toThrow(\InvalidArgumentException::class);
});
