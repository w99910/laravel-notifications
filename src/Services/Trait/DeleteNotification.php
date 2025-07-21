<?php

namespace Thomasbrillion\Notification\Services\Trait;

trait DeleteNotification
{
    use BaseDependency;

    public function deleteNotification(int|string $notificationId): bool
    {
        $response = $this
            ->getDBQuery()
            ->where('user_id', $this->getUser()->id)
            ->where('id', $notificationId)
            ->delete();

        if ($this->shouldLog()) {
            \Log::info('Notification deleted', [
                'user_id' => $this->getUser()->id,
                'notification_id' => $notificationId,
            ]);
        }

        return $response;
    }

    public function deleteAllNotifications(): bool
    {
        $response = $this
            ->getDBQuery()
            ->where('user_id', $this->getUser()->id)
            ->delete();

        if ($this->shouldLog()) {
            \Log::info('All notifications deleted for user', [
                'user_id' => $this->getUser()->id,
                'response' => $response,
            ]);
        }

        return $response;
    }
}
