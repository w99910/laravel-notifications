<?php

namespace Thomasbrillion\Notification\Services\Trait;

use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use Thomasbrillion\Notification\Notifications\NotificationEvent;

trait UpdateNotification
{
    use BaseDependency;

    public function updateNotification(int $id, array $data): NotificationInterface
    {
        $validated = $this->validateNotificationData($data);

        $notification = $this->getEloquentQuery()->where('id', $id)->update($validated);

        if (!$notification) {
            throw new \Exception('Notification not found or could not be updated.');
        }

        $$this->getUser()->notify(new NotificationEvent($notification));

        if ($this->shouldLog()) {
            \Log::info('Notification updated', [
                'user_id' => $this->getUser()->id,
                'notification_id' => $notification->id,
                'title' => $notification->title,
                'body' => $notification->body,
                'progress' => $notification->progress ?? null,
            ]);
        }

        return $notification;
    }

    public function updateNotificationProgress(int $id, int $progress): NotificationInterface
    {
        if ($progress < 0 || $progress > 100) {
            throw new \InvalidArgumentException('Progress must be between 0 and 100.');
        }

        $data = [
            'progress' => $progress
        ];

        return $this->updateNotification($id, $data);
    }
}
