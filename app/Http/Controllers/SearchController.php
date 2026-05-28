<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q    = trim($request->input('q', ''));
        $team = auth()->user()->currentTeam;

        $projects = collect();
        $tasks    = collect();

        if (mb_strlen($q) >= 2) {
            $projects = Project::where('team_id', $team->id)
                ->where(fn($q2) => $q2
                    ->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                )
                ->orderBy('name')
                ->limit(10)
                ->get();

            $tasks = Task::whereHas('project', fn($pq) => $pq->where('team_id', $team->id))
                ->where(fn($q2) => $q2
                    ->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                )
                ->with(['project', 'assignee'])
                ->orderByDesc('created_at')
                ->limit(25)
                ->get();
        }

        return view('search.index', compact('q', 'projects', 'tasks'));
    }
}
