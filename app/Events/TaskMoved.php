<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskMoved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Task $task,
        public string $oldStatus,
        public string $newStatus,
        public string $movedBy,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('team.' . $this->task->project->team_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'task_id'    => $this->task->id,
            'title'      => $this->task->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'moved_by'   => $this->movedBy,
        ];
    }

    public function broadcastAs(): string
    {
        return 'task.moved';
    }
}
