<?php

namespace Thomasbrillion\Notification\Services\Trait;

use Thomasbrillion\Notification\Support\Validator;

trait BaseDependency
{
    abstract protected function getDBQuery(): \Illuminate\Database\Query\Builder;

    abstract protected function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder;

    abstract protected function getUser(): \Illuminate\Foundation\Auth\User;

    abstract protected function shouldLog(): bool;

    protected function validateNotificationData(array $data): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'status' => 'required|string|in:info,warning,error,success',
            'priority' => 'nullable|integer|min:1|max:10',
            'category' => 'nullable|integer|nullable',
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
}
