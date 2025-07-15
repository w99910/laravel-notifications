<?php

namespace Thomasbrillion\Notification\Models;

use Thomasbrillion\Notification\Interface\Models\NotificationInterface;
use \Illuminate\Database\Eloquent\Model;

class Notification extends Model implements NotificationInterface
{
    protected $fillable = ['title', 'body', 'user_id', 'type', 'priority', 'topic_id', 'persistent', 'icon', 'read_at', 'created_at', 'actions'];

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getTopicId(): ?int
    {
        return $this->topic_id;
    }

    public function isPersistent(): bool
    {
        return $this->persistent;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getReadAt(): ?\DateTime
    {
        return $this->read_at ? new \DateTime($this->read_at) : null;
    }

    public function getCreatedAt(): \DateTime
    {
        return new \DateTime($this->created_at);
    }

    public function getActions(): array
    {
        return $this->actions ?? [];
    }
}
