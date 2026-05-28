<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProjectController extends Controller
{
    public function index()
    {
        $team = auth()->user()->currentTeam;

        if (!$team) {
            return redirect()->route('teams.create');
        }

        $projects = $team->projects()
            ->withCount(['tasks as todo_count' => fn($q) => $q->where('status', 'todo')])
            ->withCount(['tasks as doing_count' => fn($q) => $q->where('status', 'doing')])
            ->withCount(['tasks as done_count' => fn($q) => $q->where('status', 'done')])
            ->latest()
            ->get();

        return view('projects.index', compact('projects', 'team'));
    }

    public function create()
    {
        $team = auth()->user()->currentTeam;

        if ($team->isAtProjectLimit()) {
            return redirect()->route('dashboard')
                ->with('error', 'FREE plan is limited to 3 projects. Ask your admin to upgrade to PRO.');
        }

        return view('projects.create');
    }

    public function store(Request $request)
    {
        $team = auth()->user()->currentTeam;

        if ($team->isAtProjectLimit()) {
            return redirect()->route('dashboard')
                ->with('error', 'FREE plan is limited to 3 projects. Ask your admin to upgrade to PRO.');
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'color'       => 'nullable|string|max:7',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $project = auth()->user()->currentTeam->projects()->create([
            ...$validated,
            'created_by' => auth()->id(),
            'color'      => $validated['color'] ?? '#39FF14',
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'Board created.');
    }

    public function show(Project $project)
    {
        abort_unless(auth()->user()->currentTeam?->id === $project->team_id, 403);

        $tasks = $project->tasks()
            ->with(['assignee', 'labels', 'comments', 'epic'])
            ->orderBy('position')
            ->get();

        $grouped = [
            'todo'  => $tasks->where('status', 'todo')->values(),
            'doing' => $tasks->where('status', 'doing')->values(),
            'done'  => $tasks->where('status', 'done')->values(),
        ];

        $teamMembers  = $project->team->allUsers();
        $teamLabels   = $project->team->labels()->orderBy('name')->get();
        $backlogCount = $tasks->where('status', 'backlog')->count();

        return view('projects.board', compact('project', 'grouped', 'teamMembers', 'teamLabels', 'backlogCount'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'color'       => 'nullable|string|max:7',
            'status'      => 'nullable|in:active,archived,completed',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)->with('success', 'Board updated.');
    }

    public function backlog(Project $project)
    {
        abort_unless(auth()->user()->currentTeam?->id === $project->team_id, 403);

        $tasks = $project->tasks()
            ->with(['assignee', 'labels', 'sprint', 'epic'])
            ->where('status', 'backlog')
            ->orderByRaw("CASE priority WHEN 'critical' THEN 0 WHEN 'normal' THEN 1 ELSE 2 END")
            ->orderBy('position')
            ->get();

        $teamMembers    = $project->team->allUsers();
        $teamLabels     = $project->team->labels()->orderBy('name')->get();
        $planningSprint = $project->sprints()->where('status', 'planning')->first();
        $activeSprint   = $project->sprints()->where('status', 'active')->first();
        $projectEpics   = $project->epics()->orderBy('name')->get();

        return view('projects.backlog', compact('project', 'tasks', 'teamMembers', 'teamLabels', 'planningSprint', 'activeSprint', 'projectEpics'));
    }

    public function timeline(Project $project)
    {
        abort_unless(auth()->user()->currentTeam?->id === $project->team_id, 403);

        $tasks = $project->tasks()->with(['assignee', 'labels'])->orderBy('due_date')->get();

        $withDates    = $tasks->whereNotNull('due_date')->sortBy('due_date')->values();
        $withoutDates = $tasks->whereNull('due_date')->values();

        if ($withDates->isNotEmpty()) {
            $earliest   = $withDates->min(fn($t) => $t->created_at->startOfDay()->timestamp);
            $rangeStart = Carbon::createFromTimestamp($earliest)->subDays(3)->startOfDay();
            $rangeEnd   = $withDates->max('due_date')->copy()->addDays(3)->startOfDay();
        } else {
            $rangeStart = now()->startOfDay();
            $rangeEnd   = now()->addDays(30)->startOfDay();
        }

        $totalDays = max(1, $rangeStart->diffInDays($rangeEnd));

        return view('projects.timeline', compact(
            'project', 'withDates', 'withoutDates',
            'rangeStart', 'rangeEnd', 'totalDays'
        ));
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('dashboard')->with('success', 'Board deleted.');
    }
}
