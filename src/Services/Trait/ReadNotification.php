<?php

namespace Thomasbrillion\Notification\Services\Trait;

trait ReadNotification
{
    use BaseDependency;

    public function markAsRead(int|string|array $notificationId): bool
    {
        if (is_array($notificationId)) {
            return $this
                ->getDBQuery()
                ->where('user_id', $this->getUser()->id)
                ->whereIn('id', $notificationId)
                ->update(['read_at' => now()]);
        }

        return $this
            ->getDBQuery()
            ->where('user_id', $this->getUser()->id)
            ->where('id', $notificationId)
            ->update(['read_at' => now()]);
    }

    public function markAllAsRead(): bool
    {
        return $this
            ->getDBQuery()
            ->where('user_id', $this->getUser()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
