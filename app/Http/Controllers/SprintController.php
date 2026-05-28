<?php

namespace App\Http\Controllers;

use App\Models\Sprint;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class SprintController extends Controller
{
    private function authorizeProject(Project $project): void
    {
        abort_unless(auth()->user()->currentTeam->id === $project->team_id, 403);
    }

    private function canManageSprint(Project $project): bool
    {
        $user = auth()->user();
        $team = $project->team;
        return $team->user_id === $user->id
            || $team->userHasPermission($user, 'delete');
    }

    public function index(Project $project)
    {
        $this->authorizeProject($project);

        $sprints = $project->sprints()
            ->withCount(['tasks as total_count'])
            ->withCount(['tasks as done_count' => fn($q) => $q->where('status', 'done')])
            ->orderByRaw("CASE status WHEN 'active' THEN 0 WHEN 'planning' THEN 1 ELSE 2 END")
            ->orderBy('start_date')
            ->get();

        $activeSprint   = $sprints->firstWhere('status', 'active');
        $planningSprint = $sprints->firstWhere('status', 'planning');
        $canManage      = $this->canManageSprint($project);

        return view('sprints.index', compact('project', 'sprints', 'activeSprint', 'planningSprint', 'canManage'));
    }

    public function store(Request $request, Project $project)
    {
        $this->authorizeProject($project);
        abort_unless($this->canManageSprint($project), 403);

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'goal'       => 'nullable|string|max:500',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $project->sprints()->create($validated);

        return redirect()->route('sprints.index', $project)->with('success', 'Sprint created.');
    }

    public function show(Project $project, Sprint $sprint)
    {
        $this->authorizeProject($project);
        abort_unless($sprint->project_id === $project->id, 404);

        $sprintTasks = $sprint->tasks()
            ->with(['assignee', 'labels', 'comments', 'epic'])
            ->get();

        // Planning view: show backlog items that can be added
        $backlogTasks = collect();
        if ($sprint->isPlanning()) {
            $backlogTasks = $project->tasks()
                ->with(['assignee', 'labels', 'epic'])
                ->where('status', 'backlog')
                ->whereNull('sprint_id')
                ->orderByRaw("CASE priority WHEN 'critical' THEN 0 WHEN 'normal' THEN 1 ELSE 2 END")
                ->get();
        }

        $grouped = [
            'todo'  => $sprintTasks->where('status', 'todo')->values(),
            'doing' => $sprintTasks->where('status', 'doing')->values(),
            'done'  => $sprintTasks->where('status', 'done')->values(),
        ];

        $teamMembers = $project->team->allUsers();
        $canManage   = $this->canManageSprint($project);

        $chartData = null;
        if (!$sprint->isPlanning() && $sprintTasks->isNotEmpty()) {
            $chartData = $this->buildBurndownData($sprint, $sprintTasks);
        }

        return view('sprints.show', compact('project', 'sprint', 'sprintTasks', 'backlogTasks', 'grouped', 'teamMembers', 'chartData', 'canManage'));
    }

    private function buildBurndownData(Sprint $sprint, $sprintTasks): array
    {
        $start = $sprint->start_date->copy()->startOfDay();
        $end   = $sprint->end_date->copy()->startOfDay();
        $today = now()->startOfDay();

        $usePoints   = $sprintTasks->sum('story_points') > 0;
        $totalPoints = $usePoints
            ? (int) $sprintTasks->sum('story_points')
            : $sprintTasks->count();

        $totalDays = max(1, $start->diffInDays($end));
        $labels = $ideal = $actual = [];
        $current = $start->copy();
        $dayIndex = 0;

        while ($current->lte($end)) {
            $labels[] = $current->format('d M');
            $ideal[]  = round($totalPoints * (1 - $dayIndex / $totalDays), 1);

            if ($current->lte($today)) {
                $done = $sprintTasks->filter(fn($t) =>
                    $t->status === 'done' &&
                    $t->updated_at->copy()->startOfDay()->lte($current)
                );
                $doneAmt = $usePoints ? (int) $done->sum('story_points') : $done->count();
                $actual[] = max(0, $totalPoints - $doneAmt);
            }

            $current->addDay();
            $dayIndex++;
        }

        $unit = $usePoints ? 'story pts' : 'tasks';

        return compact('labels', 'ideal', 'actual', 'totalPoints', 'usePoints', 'unit');
    }

    public function start(Project $project, Sprint $sprint)
    {
        $this->authorizeProject($project);
        abort_unless($this->canManageSprint($project), 403);
        abort_unless($sprint->project_id === $project->id, 404);
        abort_unless($sprint->isPlanning(), 422);

        // Only one active sprint per project at a time
        abort_if($project->sprints()->where('status', 'active')->exists(), 422);

        $sprint->update(['status' => 'active']);

        // Promote all backlog tasks in this sprint to todo
        $sprint->tasks()->where('status', 'backlog')->update(['status' => 'todo']);

        return redirect()->route('sprints.show', [$project, $sprint])
            ->with('success', 'Sprint started! Backlog items promoted to TO_DO.');
    }

    public function complete(Project $project, Sprint $sprint)
    {
        $this->authorizeProject($project);
        abort_unless($this->canManageSprint($project), 403);
        abort_unless($sprint->project_id === $project->id, 404);
        abort_unless($sprint->isActive(), 422);

        // Unfinished tasks go back to backlog and lose sprint assignment
        $sprint->tasks()->where('status', '!=', 'done')->update([
            'status'    => 'backlog',
            'sprint_id' => null,
        ]);

        $sprint->update(['status' => 'completed']);

        return redirect()->route('sprints.index', $project)
            ->with('success', 'Sprint completed. Unfinished tasks returned to backlog.');
    }

    public function destroy(Project $project, Sprint $sprint)
    {
        $this->authorizeProject($project);
        abort_unless($this->canManageSprint($project), 403);
        abort_unless($sprint->project_id === $project->id, 404);
        abort_unless($sprint->isPlanning(), 422);

        // Clear sprint assignment from tasks before deleting
        $sprint->tasks()->update(['sprint_id' => null]);
        $sprint->delete();

        return redirect()->route('sprints.index', $project)->with('success', 'Sprint deleted.');
    }

    public function addTask(Project $project, Sprint $sprint, Task $task)
    {
        $this->authorizeProject($project);
        abort_unless($this->canManageSprint($project), 403);
        abort_unless($sprint->project_id === $project->id, 404);
        abort_unless($task->project_id === $project->id, 404);
        abort_unless(!$sprint->isCompleted(), 422);

        $task->update(['sprint_id' => $sprint->id]);

        return redirect()->back()->with('success', '"' . $task->title . '" added to sprint.');
    }

    public function removeTask(Project $project, Sprint $sprint, Task $task)
    {
        $this->authorizeProject($project);
        abort_unless($this->canManageSprint($project), 403);
        abort_unless($sprint->project_id === $project->id, 404);
        abort_unless($task->sprint_id === $sprint->id, 404);

        $task->update(['sprint_id' => null, 'status' => 'backlog']);

        return redirect()->back()->with('success', 'Task removed from sprint.');
    }
}
