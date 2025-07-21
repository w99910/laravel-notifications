<?php

namespace Thomasbrillion\Notification\Services\Trait;

use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use \Thomasbrillion\Notification\Support\Validator;

trait CreateNotification
{
    use BaseDependency;

    public function createNotification(array $data): NotificationInterface
    {
        $validated = $this->validateNotificationData($data);

        $validated['user_id'] = $this->getUser()->id;

        $validated['created_at'] ??= now();
        $validated['read_at'] = null;  // Default to null for new notifications
        $validated['priority'] = $validated['priority'] ?? 5;  // Default priority if not set

        $validated['category'] = $validated['category'] ?? 'inbox';  // Default category if not set
        $notification = $this->getEloquentQuery()->create($validated);

        if ($this->shouldLog()) {
            \Log::info('Notification created', [
                'user_id' => $this->getUser()->id,
                'notification_id' => $notification->id,
                'title' => $notification->title,
                'body' => $notification->body,
            ]);
        }

        return $notification;
    }
}
