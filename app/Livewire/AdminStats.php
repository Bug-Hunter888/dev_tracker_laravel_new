<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Livewire\Component;

class AdminStats extends Component
{
    public function render()
    {
        return view('livewire.admin-stats', [
            'totalUsers'    => User::count(),
            'adminUsers'    => User::where('is_admin', true)->count(),
            'bannedUsers'   => User::where('is_banned', true)->count(),
            'totalTeams'    => Team::count(),
            'totalProjects' => Project::count(),
            'totalSprints'  => Sprint::count(),
            'totalTasks'    => Task::count(),
            'doneTasks'     => Task::where('status', 'done')->count(),
        ]);
    }
}
