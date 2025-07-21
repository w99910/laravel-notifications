<?php

namespace Thomasbrillion\Notification\Services\Trait;

use \Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use \Thomasbrillion\Notification\Notifications\NotificationEvent;

trait SendNotification
{
    use BaseDependency;

    public function sendNotification(NotificationInterface|string|int $notification)
    {
        if (!($notification instanceof NotificationInterface)) {
            // If a string or int is provided, we assume it's an ID or type
            $notification = $this->getEloquentQuery()->findOrFail($notification);
        }

        // Send via Laravel notifications
        $this->getUser()->notify(new NotificationEvent($notification));
        // Optionally, you can log the notification or perform additional actions here
        if ($this->shouldLog()) {
            \Log::info('Notification sent', [
                'user_id' => $this->getUser()->id,
                'notification_id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
            ]);
        }

        return true;  // Indicate that the notification was sent successfully
    }
}
