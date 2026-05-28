<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskActionNotification;

class AutomationService
{
    /**
     * Run all enabled automations for a project whose trigger matches.
     */
    public function run(Task $task, string $trigger): void
    {
        $automations = $task->project->automations()
            ->where('enabled', true)
            ->where('trigger', $trigger)
            ->get();

        foreach ($automations as $automation) {
            $this->execute($task, $automation->actions);
        }
    }

    private function execute(Task $task, array $actions): void
    {
        foreach ($actions as $action) {
            match ($action['type'] ?? '') {
                'notify_assignee' => $this->notifyUser(
                    $task->assignee,
                    $task,
                    $action['message'] ?? 'A task you are assigned to was updated.'
                ),
                'notify_creator' => $this->notifyUser(
                    $task->creator,
                    $task,
                    $action['message'] ?? 'A task you created was updated.'
                ),
                'set_priority' => $this->setPriority($task, $action['value'] ?? 'normal'),
                'move_status'  => $this->moveStatus($task, $action['value'] ?? 'todo'),
                default        => null,
            };
        }
    }

    private function notifyUser(?User $user, Task $task, string $message): void
    {
        if (!$user) return;
        $user->notify(new TaskActionNotification($task, $message));
    }

    private function setPriority(Task $task, string $priority): void
    {
        if (in_array($priority, ['low', 'normal', 'critical'])) {
            $task->update(['priority' => $priority]);
        }
    }

    private function moveStatus(Task $task, string $status): void
    {
        if (in_array($status, ['todo', 'doing', 'done'])) {
            $maxPos = $task->project->tasks()->where('status', $status)->max('position') + 1;
            $task->update(['status' => $status, 'position' => $maxPos]);
        }
    }
}
