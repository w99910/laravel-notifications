<?php

namespace Thomasbrillion\Notification\Services\Trait;

use \Thomasbrillion\Notification\Support\Validator;

trait GetNotification
{
    use BaseDependency;

    protected function validated(array $data)
    {
        $rules = [
            'offset' => 'integer|min:0|nullable',
            'limit' => 'integer|min:1|nullable',
            'type' => 'string|nullable|in:warning,info,success,error',
            'read' => 'boolean|nullable',
            'category' => 'string|nullable',
            'priority' => 'integer|nullable',
            'created_at' => 'date|nullable',
            'start_date' => 'date|nullable',
            'end_date' => 'date|nullable',
            'order_by' => 'string|nullable|in:created_at,priority,id,category',
            'order_direction' => 'string|nullable|in:asc,desc',
        ];

        return Validator::tryValidate($data, $rules);
    }

    protected function prepareGetQuery(array $data)
    {
        $validated = $this->validated($data);

        $query = $this
            ->getEloquentQuery()
            ->where('user_id', $this->getUser()->id);

        if (isset($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (isset($validated['read'])) {
            $query->whereNotNull('read_at');
        }

        if (isset($validated['category'])) {
            $query->where('category', $validated['category']);
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

        if (isset($validated['order_by'])) {
            $order = $validated['order_direction'] ?? 'desc';

            $query->orderBy($validated['order_by'], $order);
        } else {
            $query->orderBy('created_at', 'desc');
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

    public function getNotificationCount(array $data = []): int
    {
        return $this
            ->prepareGetQuery($data)
            ->count();
    }

    public function getUnreadNotifications(): \Illuminate\Database\Eloquent\Collection
    {
        return $this
            ->getEloquentQuery()
            ->where('user_id', $this->getUser()->id)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getReadNotifications(): \Illuminate\Database\Eloquent\Collection
    {
        return $this
            ->getEloquentQuery()
            ->where('user_id', $this->getUser()->id)
            ->whereNotNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUnreadNotificationsCount(): int
    {
        return $this
            ->getEloquentQuery()
            ->where('user_id', $this->getUser()->id)
            ->whereNull('read_at')
            ->count();
    }

    public function getReadNotificationsCount(): int
    {
        return $this
            ->getEloquentQuery()
            ->where('user_id', $this->getUser()->id)
            ->whereNotNull('read_at')
            ->count();
    }
}
