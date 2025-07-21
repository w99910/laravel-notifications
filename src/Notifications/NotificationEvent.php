<?php

namespace Thomasbrillion\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationEvent extends Notification implements ShouldBroadcast
{
    use Queueable;

    protected string $source;
    protected string $status;
    protected string $message;
    protected \DateTime $createdAt;
    protected ?\DateTime $readAt = null;
    protected string $category = 'inbox';
    protected array $actions = [];
    protected ?int $progress = null;
    protected ?string $attachment = null;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected NotificationInterface $notification
    ) {
        $this->id = $notification->getId();
        $this->source = 'system';  // Default source, can be overridden
        $this->status = $notification->getStatus();
        $this->message = $notification->getMessage();
        $this->createdAt = $notification->getCreatedAt();
        $this->readAt = $notification->getReadAt();
        $this->category = $notification->getCategory() ?? 'inbox';
        $this->actions = $notification->getActions();
        $this->progress = $notification->getProgress();
        $this->attachment = $notification->getAttachment();
    }

    // public function

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id' => $this->id,
            'title' => $this->source,
            'status' => $this->status,
            'message' => $this->message,
            'created_at' => $this->createdAt->format(\DateTime::ATOM),
            'read_at' => $this->readAt ? $this->readAt->format(\DateTime::ATOM) : null,
            'category' => $this->category,
            'actions' => $this->actions,
            'progress' => $this->progress,
            'attachment' => $this->attachment,
        ];
    }
}
