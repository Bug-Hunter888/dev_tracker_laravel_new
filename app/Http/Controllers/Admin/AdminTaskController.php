<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class AdminTaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['project.team', 'creator', 'assignee'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->paginate(25)->withQueryString();

        return view('admin.tasks.index', compact('tasks'));
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'status'   => 'sometimes|in:backlog,todo,doing,done',
            'priority' => 'sometimes|in:low,normal,critical',
        ]);

        $task->update($data);

        return back()->with('success', "Task \"{$task->title}\" updated.");
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return back()->with('success', 'Task deleted.');
    }
}
