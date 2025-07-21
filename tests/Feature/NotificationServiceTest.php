<?php

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Thomasbrillion\Notification\Events\NotificationEvent;
use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use Thomasbrillion\Notification\Models\Notification;
use Thomasbrillion\Notification\Services\NotificationService;
use Thomasbrillion\Notification\Tests\Models\User;

test('can be instantiated with User object', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    expect($service)->toBeInstanceOf(NotificationService::class);
});

test('can be instantiated with user ID', function () {
    // This would work if User::findOrFail is properly mocked
    $service = new NotificationService(1);

    expect($service)->toBeInstanceOf(NotificationService::class);
})->skip('Requires database mock');

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

test('generates correct WebSocket channel name', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    $channel = $service->getWSChannel();

    expect($channel)->toStartWith('users.');
});

test('encrypts user ID when encrypt function is available', function () {
    $user = new User(['id' => 1]);
    $service = new NotificationService($user);

    if (function_exists('encrypt')) {
        $channel = $service->getWSChannel();
        expect($channel)->not->toContain('1');
    } else {
        $channel = $service->getWSChannel();
        expect($channel)->toBe('users.1');
    }
});
