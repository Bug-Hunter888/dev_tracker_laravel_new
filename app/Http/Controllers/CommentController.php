<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskActivity;
use App\Models\TaskComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        abort_unless(auth()->user()->currentTeam->id === $task->project->team_id, 403);

        $request->validate(['content' => 'required|string|max:5000']);

        $task->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        TaskActivity::create([
            'task_id'   => $task->id,
            'user_id'   => auth()->id(),
            'action'    => 'commented',
            'new_value' => substr($request->content, 0, 100),
        ]);

        return redirect()->route('tasks.show', $task)->with('success', 'Comment added.');
    }

    public function destroy(TaskComment $comment)
    {
        $task = $comment->task;
        abort_unless(auth()->user()->currentTeam->id === $task->project->team_id, 403);
        abort_unless($comment->user_id === auth()->id(), 403);

        $comment->delete();

        return redirect()->route('tasks.show', $task)->with('success', 'Comment deleted.');
    }
}
