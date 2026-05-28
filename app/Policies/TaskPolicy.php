<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function delete(User $user, Task $task): bool
    {
        $project = $task->project;
        $team    = $user->currentTeam;

        if ($team->id !== $project->team_id) return false;

        return $task->created_by === $user->id
            || $project->created_by === $user->id
            || $team->user_id === $user->id
            || $user->hasTeamRole($team, 'admin');
    }
}
