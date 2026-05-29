<?php

namespace App\Http\Controllers;

use App\Events\TaskMoved;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskActivity;
use App\Notifications\TaskActionNotification;
use App\Services\AutomationService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function show(Task $task)
    {
        $project = $task->project;
        abort_unless(auth()->user()->currentTeam->id === $project->team_id, 403);

        $task->load(['assignee', 'creator', 'labels', 'comments.user', 'attachments.uploader', 'activities.user', 'epic']);
        $teamMembers  = $project->team->allUsers();
        $teamLabels   = $project->team->labels;
        $projectEpics = $project->epics()->orderBy('name')->get();

        return view('tasks.show', compact('task', 'project', 'teamMembers', 'teamLabels', 'projectEpics'));
    }

    public function store(Request $request, Project $project)
    {
        abort_unless(auth()->user()->currentTeam->id === $project->team_id, 403);

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'status'       => 'required|in:todo,doing,done,backlog',
            'priority'     => 'nullable|in:low,normal,critical',
            'assigned_to'  => 'nullable|exists:users,id',
            'due_date'     => 'nullable|date',
            'story_points' => 'nullable|integer|min:0|max:999',
            'epic_id'      => 'nullable|exists:epics,id',
        ]);

        $position = $project->tasks()->where('status', $validated['status'])->max('position') + 1;

        $task = $project->tasks()->create([
            ...$validated,
            'created_by' => auth()->id(),
            'priority'   => $validated['priority'] ?? 'normal',
            'position'   => $position,
        ]);

        TaskActivity::create([
            'task_id'   => $task->id,
            'user_id'   => auth()->id(),
            'action'    => 'created',
            'new_value' => $task->status,
        ]);

        return redirect()->back()->with('success', 'Task added.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $project = $task->project;
        abort_unless(auth()->user()->currentTeam->id === $project->team_id, 403);

        $request->validate(['status' => 'required|in:todo,doing,done,backlog']);

        $oldStatus = $task->status;
        $newStatus = $request->status;

        $task->update([
            'status'   => $newStatus,
            'position' => $project->tasks()->where('status', $newStatus)->max('position') + 1,
        ]);

        TaskActivity::create([
            'task_id'   => $task->id,
            'user_id'   => auth()->id(),
            'action'    => 'status_changed',
            'old_value' => $oldStatus,
            'new_value' => $newStatus,
        ]);

        try {
            broadcast(new TaskMoved($task, $oldStatus, $newStatus, auth()->user()->name))->toOthers();
        } catch (\Exception $e) {
            // Reverb server not running — real-time update skipped, page redirect still works
        }

        // Notify team members about the status change
        $mover  = auth()->user()->name;
        $label  = strtoupper($newStatus);
        $notify = new TaskActionNotification(
            $task,
            "{$mover} moved \"{$task->title}\" → {$label}"
        );

        $task->project->team->allUsers()
            ->reject(fn($u) => $u->id === auth()->id())
            ->each(fn($u) => $u->notify($notify));

        $trigger = match ($newStatus) {
            'done'  => 'task_marked_done',
            'doing' => 'task_marked_doing',
            'todo'  => 'task_marked_todo',
            default => null,
        };
        if ($trigger) app(AutomationService::class)->run($task, $trigger);

        return redirect()->back();
    }

    public function update(Request $request, Task $task)
    {
        $project = $task->project;
        abort_unless(auth()->user()->currentTeam->id === $project->team_id, 403);

        $validated = $request->validate([
            'title'        => 'sometimes|required|string|max:255',
            'description'  => 'nullable|string',
            'status'       => 'nullable|in:todo,doing,done,backlog',
            'priority'     => 'nullable|in:low,normal,critical',
            'assigned_to'  => 'nullable|exists:users,id',
            'due_date'     => 'nullable|date',
            'story_points' => 'nullable|integer|min:0|max:999',
            'epic_id'      => 'nullable|exists:epics,id',
            'label_ids'    => 'nullable|array',
            'label_ids.*'  => 'exists:labels,id',
        ]);

        if ($request->has('label_ids')) {
            $task->labels()->sync($validated['label_ids'] ?? []);
        }
        unset($validated['label_ids']);

        if (isset($validated['status']) && $validated['status'] !== $task->status) {
            $oldStatus = $task->status;
            $validated['position'] = $project->tasks()->where('status', $validated['status'])->max('position') + 1;
            TaskActivity::create([
                'task_id'   => $task->id,
                'user_id'   => auth()->id(),
                'action'    => 'status_changed',
                'old_value' => $oldStatus,
                'new_value' => $validated['status'],
            ]);
        }

        $assigneeChanged = isset($validated['assigned_to'])
            && $validated['assigned_to'] != $task->assigned_to;

        $task->update($validated);

        if ($assigneeChanged && $task->assignee) {
            app(AutomationService::class)->run($task->fresh(), 'task_assigned');
        }

        $redirect = $request->has('from_board')
            ? redirect()->route('projects.show', $project)
            : redirect()->route('tasks.show', $task);

        return $redirect->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $project = $task->project;
        $task->delete();

        return redirect()->route('projects.show', $project)->with('success', 'Task deleted.');
    }
}
