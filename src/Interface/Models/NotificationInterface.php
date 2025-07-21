<?php

namespace Thomasbrillion\Notification\Interface\Models;

interface NotificationInterface
{
    public function getId(): int|string;

    public function getTitle(): string;

    public function getMessage(): string;

    public function getUserId(): int|string;

    public function getStatus(): string;

    public function getPriority(): int;

    public function getCategory(): string;

    public function getAvatar(): ?string;

    public function getReadAt(): ?\DateTime;

    public function getCreatedAt(): \DateTime;

    public function getActions(): array;

    public function getProgress(): ?int;

    public function getAttachment(): ?string;

    public function getDBQuery(): \Illuminate\Database\Query\Builder;

    public function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder;
}
