<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    public function __construct(
        public readonly string $senderName,
        public readonly string $preview,
        public readonly string $teamName,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => null,
            'message' => "@{$this->senderName}: {$this->preview}",
        ];
    }
}
