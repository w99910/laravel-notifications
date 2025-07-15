<?php

namespace Thomasbrillion\Notification\Services;

use Illuminate\Foundation\Auth\User;
use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use Thomasbrillion\Notification\Models\Notification;
use Thomasbrillion\Notification\Services\Trait\DeleteNotifications;
use Thomasbrillion\Notification\Services\Trait\GetNotifications;
use Thomasbrillion\Notification\Services\Trait\ReadNotifications;
use \Illuminate\Database\Query\Builder;

class NotificationService
{
    use GetNotifications, ReadNotifications, DeleteNotifications;

    public function __construct(
        protected User $user,
        protected NotificationInterface|null $notificationInterface = null
    ) {
        if (!$this->notificationInterface) {
            $this->notificationInterface = \config('notification.models.notification', new Notification());
        }
    }

    protected function getNotificationQuery(): Builder
    {
        return $this->notificationInterface->getQuery();
    }

    protected function getUser(): User
    {
        return $this->user;
    }
}
