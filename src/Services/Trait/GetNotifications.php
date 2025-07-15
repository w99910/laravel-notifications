<?php

namespace Thomasbrillion\Notification\Services\Trait;

use \Thomasbrillion\Notification\Support\Validator;

trait GetNotifications
{
    use BaseDependency;

    protected function validated(array $data)
    {
        $rules = [
            'offset' => 'integer|min:0|nullable',
            'limit' => 'integer|min:1|nullable',
            'type' => 'string|nullable|in:warning,info,success,error',
            'read' => 'boolean|nullable',
            'persistent' => 'boolean|nullable',
            'topic_id' => 'integer|string|nullable',
            'priority' => 'integer|nullable',
            'created_at' => 'date|nullable',
            'start_date' => 'date|nullable',
            'end_date' => 'date|nullable',
            'order_by' => 'string|nullable|in:created_at,priority',
            'order' => 'string|nullable|in:asc,desc',
        ];

        return Validator::tryValidate($data, $rules);
    }

    protected function prepareGetQuery(array $data)
    {
        $validated = $this->validated($data);

        $query = $this
            ->getNotificationQuery()
            ->where('user_id', $this->getUser()->id);

        if (isset($validated['type'])) {
            $query->where('type', $validated['type']);
        }

        if (isset($validated['read'])) {
            $query->whereNotNull('read_at');
        }

        if (isset($validated['persistent'])) {
            $query->where('persistent', $validated['persistent']);
        }

        if (isset($validated['topic_id'])) {
            $query->where('topic_id', $validated['topic_id']);
        }

        if (isset($validated['priority'])) {
            $query->where('priority', $validated['priority']);
        }

        if (isset($validated['created_at'])) {
            $query->whereDate('created_at', $validated['created_at']);
        }

        if (isset($validated['start_date'])) {
            $query->whereDate('created_at', '>=', $validated['start_date']);
        }

        if (isset($validated['end_date'])) {
            $query->whereDate('created_at', '<=', $validated['end_date']);
        }

        $order = $validated['order'] ?? 'desc';

        if (isset($validated['order_by'])) {
            $query->orderBy($validated['order_by'], $order);
        } else {
            $query->orderBy('created_at', $order);
        }

        if (isset($validated['offset'])) {
            $query->offset($validated['offset']);
        }

        if (isset($validated['limit'])) {
            $query->limit($validated['limit']);
        }

        return $query;
    }

    public function getNotifications(array $data): \Illuminate\Database\Eloquent\Collection
    {
        return $this
            ->prepareGetQuery($data)
            ->get();
    }

    public function getNotificationCount(array $data): int
    {
        return $this
            ->prepareGetQuery($data)
            ->count();
    }

    public function getUnreadNotificationsCount(): int
    {
        return $this
            ->getNotificationQuery()
            ->where('user_id', $this->getUser()->id)
            ->whereNull('read_at')
            ->count();
    }

    public function getReadNotificationsCount(): int
    {
        return $this
            ->getNotificationQuery()
            ->where('user_id', $this->getUser()->id)
            ->whereNotNull('read_at')
            ->count();
    }
}
