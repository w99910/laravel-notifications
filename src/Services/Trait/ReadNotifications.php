<?php

namespace Thomasbrillion\Notification\Services\Trait;

trait ReadNotifications
{
    use BaseDependency;

    public function markAsRead(int|string $notificationId): bool
    {
        $validated = $this->validated($data);

        return $this
            ->getNotificationQuery()
            ->where('user_id', $this->getUser()->id)
            ->where('id', $notificationId)
            ->update(['read_at' => now()]);
    }

    public function markAllAsRead(): bool
    {
        return $this
            ->getNotificationQuery()
            ->where('user_id', $this->getUser()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
