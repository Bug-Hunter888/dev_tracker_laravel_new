<?php

namespace App\Http\Controllers;

use App\Models\Epic;
use App\Models\Project;
use Illuminate\Http\Request;

class EpicController extends Controller
{
    private function authorizeProject(Project $project): void
    {
        abort_unless(auth()->user()->currentTeam->id === $project->team_id, 403);
    }

    public function index(Project $project)
    {
        $this->authorizeProject($project);

        $epics = $project->epics()
            ->withCount(['tasks as total_count'])
            ->withCount(['tasks as done_count' => fn($q) => $q->where('status', 'done')])
            ->get();

        return view('epics.index', compact('project', 'epics'));
    }

    public function store(Request $request, Project $project)
    {
        $this->authorizeProject($project);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color'       => 'nullable|string|max:7',
        ]);

        $project->epics()->create([
            ...$validated,
            'created_by' => auth()->id(),
            'color'      => $validated['color'] ?? '#8B5CF6',
        ]);

        return redirect()->route('epics.index', $project)->with('success', 'Epic created.');
    }

    public function show(Project $project, Epic $epic)
    {
        $this->authorizeProject($project);
        abort_unless($epic->project_id === $project->id, 404);

        $tasks = $epic->tasks()->with(['assignee', 'labels', 'sprint'])->get();

        $grouped = [
            'todo'    => $tasks->where('status', 'todo')->values(),
            'doing'   => $tasks->where('status', 'doing')->values(),
            'done'    => $tasks->where('status', 'done')->values(),
            'backlog' => $tasks->where('status', 'backlog')->values(),
        ];

        $total = $tasks->count();
        $done  = $tasks->where('status', 'done')->count();
        $pct   = $total > 0 ? round($done / $total * 100) : 0;

        return view('epics.show', compact('project', 'epic', 'tasks', 'grouped', 'total', 'done', 'pct'));
    }

    public function update(Request $request, Project $project, Epic $epic)
    {
        $this->authorizeProject($project);
        abort_unless($epic->project_id === $project->id, 404);

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color'       => 'nullable|string|max:7',
            'status'      => 'nullable|in:open,closed',
        ]);

        $epic->update($validated);

        return redirect()->back()->with('success', 'Epic updated.');
    }

    public function destroy(Project $project, Epic $epic)
    {
        $this->authorizeProject($project);
        abort_unless($epic->project_id === $project->id, 404);

        $epic->delete();

        return redirect()->route('epics.index', $project)->with('success', 'Epic deleted. Tasks remain unaffected.');
    }
}
