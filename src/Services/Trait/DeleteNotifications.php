<?php

namespace Thomasbrillion\Notification\Services\Trait;

trait DeleteNotifications
{
    use BaseDependency;

    public function deleteNotification(int|string $notificationId): bool
    {
        return $this
            ->getNotificationQuery()
            ->where('user_id', $this->getUser()->id)
            ->where('id', $notificationId)
            ->delete();
    }

    public function deleteAllNotifications(): bool
    {
        return $this
            ->getNotificationQuery()
            ->where('user_id', $this->getUser()->id)
            ->delete();
    }
}
