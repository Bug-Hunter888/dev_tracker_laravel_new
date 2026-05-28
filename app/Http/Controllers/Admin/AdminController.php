<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'totalUsers'    => User::count(),
            'totalTeams'    => Team::count(),
            'totalProjects' => Project::count(),
            'totalSprints'  => Sprint::count(),
            'totalTasks'    => Task::count(),
            'recentUsers'   => User::latest()->take(5)->get(),
        ]);
    }
}
