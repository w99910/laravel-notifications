<?php

namespace Thomasbrillion\Notification\Interface\Models;

interface NotificationInterface
{
    public function getTitle(): string;

    public function getBody(): string;

    public function getUserId(): int;

    public function getType(): string;

    public function getPriority(): int;

    public function getTopicId(): ?int;

    public function isPersistent(): bool;

    public function getIcon(): ?string;

    public function getReadAt(): ?\DateTime;

    public function getCreatedAt(): \DateTime;

    public function getActions(): array;

    public function getQuery(): \Illuminate\Database\Query\Builder;
}
