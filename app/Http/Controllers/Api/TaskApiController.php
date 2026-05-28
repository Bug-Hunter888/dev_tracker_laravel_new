<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tasks = Task::whereHas('project', function ($q) use ($request) {
            $q->where('team_id', $request->user()->currentTeam->id);
        })
            ->with(['assignee:id,name', 'project:id,name'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json($tasks);
    }

    public function show(Request $request, Task $task): JsonResponse
    {
        abort_unless($request->user()->currentTeam->id === $task->project->team_id, 403);

        return response()->json(
            $task->load(['assignee:id,name', 'project:id,name', 'labels:id,name,color'])
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'project_id'   => 'required|exists:projects,id',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'priority'     => 'nullable|in:low,normal,critical',
            'status'       => 'nullable|in:backlog,todo,doing,done',
            'assigned_to'  => 'nullable|exists:users,id',
            'due_date'     => 'nullable|date',
            'story_points' => 'nullable|integer|min:0|max:999',
        ]);

        $project = Project::findOrFail($data['project_id']);
        abort_unless($request->user()->currentTeam->id === $project->team_id, 403);

        $task = $project->tasks()->create(array_merge(
            $data,
            ['created_by' => $request->user()->id]
        ));

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        abort_unless($request->user()->currentTeam->id === $task->project->team_id, 403);

        $data = $request->validate([
            'title'        => 'sometimes|string|max:255',
            'description'  => 'nullable|string',
            'priority'     => 'sometimes|in:low,normal,critical',
            'status'       => 'sometimes|in:backlog,todo,doing,done',
            'assigned_to'  => 'nullable|exists:users,id',
            'due_date'     => 'nullable|date',
            'story_points' => 'nullable|integer|min:0|max:999',
        ]);

        $task->update($data);

        return response()->json($task->fresh()->load('assignee:id,name'));
    }

    public function destroy(Request $request, Task $task): JsonResponse
    {
        abort_unless($request->user()->currentTeam->id === $task->project->team_id, 403);

        $task->delete();

        return response()->json(null, 204);
    }
}
