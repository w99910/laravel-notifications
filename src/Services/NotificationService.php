<?php

namespace Thomasbrillion\Notification\Services;

use Illuminate\Foundation\Auth\User;
use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use Thomasbrillion\Notification\Models\Notification;
use Thomasbrillion\Notification\Services\Trait\CreateNotification;
use Thomasbrillion\Notification\Services\Trait\DeleteNotification;
use Thomasbrillion\Notification\Services\Trait\GetNotification;
use Thomasbrillion\Notification\Services\Trait\ReadNotification;
use Thomasbrillion\Notification\Services\Trait\SendNotification;
use Thomasbrillion\Notification\Services\Trait\UpdateNotification;

class NotificationService
{
    use GetNotification, ReadNotification, DeleteNotification, CreateNotification, UpdateNotification, SendNotification;

    protected User $user;

    protected NotificationInterface $notificationInterface;

    protected bool $logging = false;

    public function __construct(
        User $user,
        NotificationInterface|null|string $notificationInterface = null,
        bool $logging = false,
    ) {
        if (!method_exists($user, 'notify')) {
            throw new \InvalidArgumentException('User must implement the notify method. You may use Illuminate\Notifications\Notifiable trait.');
        }

        $this->user = $user;

        if (!$notificationInterface) {
            $notificationInterface = \config('notification.models.notification', Notification::class);
        }

        if (is_string($notificationInterface)) {
            if (!class_exists($notificationInterface)) {
                throw new \InvalidArgumentException('Notification interface class does not exist: ' . $notificationInterface);
            }

            $notificationInterface = new $notificationInterface();
        }

        if (!$notificationInterface instanceof NotificationInterface) {
            throw new \InvalidArgumentException('Notification interface must implement NotificationInterface');
        }

        $this->notificationInterface = $notificationInterface;

        $this->logging = $logging;
    }

    public function getWSChannel(): string
    {
        $userId = $this->user->getKey() ?? $this->user->id ?? $this->user->getAuthIdentifier();

        if (function_exists('encrypt')) {
            // Encrypt the user ID if the encrypt function is available
            // This is useful for obfuscating user IDs in the channel name
            $userId = encrypt((string) $userId);
        }

        return 'users.' . $userId;
    }

    protected function getDBQuery(): \Illuminate\Database\Query\Builder
    {
        return $this->notificationInterface->getDBQuery();
    }

    protected function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->notificationInterface->getEloquentQuery();
    }

    protected function getUser(): User
    {
        return $this->user;
    }

    protected function shouldLog(): bool
    {
        return $this->logging;
    }
}
