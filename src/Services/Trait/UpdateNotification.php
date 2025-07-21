<?php

namespace Thomasbrillion\Notification\Services\Trait;

use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use Thomasbrillion\Notification\Notifications\NotificationEvent;
use Thomasbrillion\Notification\Support\Validator;

trait UpdateNotification
{
    use BaseDependency;

    protected function validateUpdateData(array $data): array
    {
        $rules = [
            'title' => 'nullable|string|max:255',
            'message' => 'nullable|string',
            'status' => 'nullable|string|in:info,warning,error,success',
            'priority' => 'nullable|integer|min:1|max:10',
            'category' => 'nullable|string|nullable',
            'avatar' => 'nullable|string|nullable',
            'actions' => 'nullable|array|nullable',
            'actions.*.label' => 'required|string|nullable',
            'actions.*.url' => 'required|url|nullable',
            'progress' => 'nullable|integer|min:0|max:100|nullable',
            'attachment' => 'nullable|string|nullable',
            'created_at' => 'nullable|date|nullable',
            'updated_at' => 'nullable|date|nullable',
        ];

        return Validator::tryValidate($data, $rules);
    }

    public function updateNotification(int $id, array $data, bool $notify = true): NotificationInterface
    {
        $validated = $this->validateUpdateData($data);

        $notification = $this->getEloquentQuery()->where('id', $id)->update($validated);

        if (!$notification) {
            throw new \Exception('Notification not found or could not be updated.');
        }

        $notification = $this->getEloquentQuery()->find($id);

        if ($notify) {
            $this->getUser()->notify(new NotificationEvent($notification));
        }

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

    public function updateNotificationProgress(int $id, int $progress, bool $notify = true): NotificationInterface
    {
        if ($progress < 0 || $progress > 100) {
            throw new \InvalidArgumentException('Progress must be between 0 and 100.');
        }

        $data = [
            'progress' => $progress
        ];

        return $this->updateNotification($id, $data, $notify);
    }
}
