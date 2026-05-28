<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Notifications\Notification;

class TaskActionNotification extends Notification
{
    public function __construct(
        public readonly Task   $task,
        public readonly string $message,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id'    => $this->task->id,
            'task_title' => $this->task->title,
            'project_id' => $this->task->project_id,
            'message'    => $this->message,
        ];
    }
}
