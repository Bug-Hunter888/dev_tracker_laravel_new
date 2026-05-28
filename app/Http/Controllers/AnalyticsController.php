<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskActivity;

class AnalyticsController extends Controller
{
    public function index()
    {
        $team = auth()->user()->currentTeam;

        if (!$team) {
            $redirect = auth()->user()->is_admin ? 'admin.dashboard' : 'dashboard';
            return redirect()->route($redirect)->with('error', 'No team selected.');
        }

        $teamId = $team->id;

        if (!$team->canUseAnalytics()) {
            return view('analytics.locked', compact('team'));
        }

        $totalTasks   = Task::whereHas('project', fn($q) => $q->where('team_id', $teamId))->count();
        $doneTasks    = Task::whereHas('project', fn($q) => $q->where('team_id', $teamId))->where('status', 'done')->count();
        $doingTasks   = Task::whereHas('project', fn($q) => $q->where('team_id', $teamId))->where('status', 'doing')->count();
        $overdueTasks = Task::whereHas('project', fn($q) => $q->where('team_id', $teamId))
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->where('status', '!=', 'done')
            ->count();

        $projects = Project::where('team_id', $teamId)
            ->withCount([
                'tasks',
                'tasks as done_count'  => fn($q) => $q->where('status', 'done'),
                'tasks as doing_count' => fn($q) => $q->where('status', 'doing'),
                'tasks as todo_count'  => fn($q) => $q->where('status', 'todo'),
            ])
            ->get();

        $todoTasks = $totalTasks - $doneTasks - $doingTasks;

        $priorityBreakdown = Task::whereHas('project', fn($q) => $q->where('team_id', $teamId))
            ->selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority');

        $shippedByUser = Task::whereHas('project', fn($q) => $q->where('team_id', $teamId))
            ->where('status', 'done')
            ->whereNotNull('assigned_to')
            ->with('assignee:id,name')
            ->get()
            ->groupBy('assigned_to')
            ->map(fn($tasks) => [
                'name'  => $tasks->first()->assignee->name,
                'count' => $tasks->count(),
            ])
            ->sortByDesc('count')
            ->values();

        $activityDays = TaskActivity::whereHas('task.project', fn($q) => $q->where('team_id', $teamId))
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $heatmap = collect(range(29, 0))->map(fn($i) => [
            'date'  => now()->subDays($i)->format('Y-m-d'),
            'label' => now()->subDays($i)->format('d M'),
            'count' => (int) ($activityDays[now()->subDays($i)->format('Y-m-d')] ?? 0),
        ]);

        return view('analytics.index', compact(
            'totalTasks', 'doneTasks', 'doingTasks', 'overdueTasks', 'todoTasks',
            'priorityBreakdown', 'projects', 'shippedByUser', 'heatmap'
        ));
    }
}
