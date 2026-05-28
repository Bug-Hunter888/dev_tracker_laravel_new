<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    private function isPrivileged(User $user, Project $project): bool
    {
        $team = $user->currentTeam;
        if ($team->id !== $project->team_id) return false;

        return $team->user_id === $user->id
            || $user->hasTeamRole($team, 'admin')
            || $project->created_by === $user->id;
    }

    public function update(User $user, Project $project): bool
    {
        return $this->isPrivileged($user, $project);
    }

    public function delete(User $user, Project $project): bool
    {
        return $this->isPrivileged($user, $project);
    }

    public function manageAutomations(User $user, Project $project): bool
    {
        return $this->isPrivileged($user, $project);
    }
}
