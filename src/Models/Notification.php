<?php

namespace Thomasbrillion\Notification\Models;

use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use \Illuminate\Database\Eloquent\Model;

class Notification extends Model implements NotificationInterface
{
    protected $fillable = [
        'title',
        'message',
        'user_id',
        'status',
        'priority',
        'category',
        'avatar',
        'read_at',
        'created_at',
        'actions',
        'progress',
        'attachment',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'actions' => 'array',
        'progress' => 'integer',
    ];

    public function getId(): int|string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getUserId(): int|string
    {
        return $this->user_id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function getReadAt(): ?\DateTime
    {
        return $this->read_at;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function getActions(): array
    {
        return $this->actions ?? [];
    }

    public function getProgress(): ?int
    {
        return $this->progress;
    }

    public function getAttachment(): ?string
    {
        return $this->attachment;
    }

    public function getDBQuery(): \Illuminate\Database\Query\Builder
    {
        return $this->getQuery();
    }

    public function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->newQuery();
    }
}
