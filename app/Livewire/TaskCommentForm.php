<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\TaskActivity;
use App\Models\TaskComment;
use Livewire\Component;

class TaskCommentForm extends Component
{
    public int $taskId;
    public string $content = '';

    protected $rules = [
        'content' => 'required|string|max:5000',
    ];

    public function submit(): void
    {
        $this->validate();

        $task = Task::findOrFail($this->taskId);
        abort_unless(auth()->user()->currentTeam->id === $task->project->team_id, 403);

        $task->comments()->create([
            'user_id' => auth()->id(),
            'content' => $this->content,
        ]);

        TaskActivity::create([
            'task_id'   => $task->id,
            'user_id'   => auth()->id(),
            'action'    => 'commented',
            'new_value' => substr($this->content, 0, 100),
        ]);

        $this->reset('content');
    }

    public function deleteComment(int $commentId): void
    {
        $comment = TaskComment::findOrFail($commentId);
        abort_unless(auth()->user()->currentTeam->id === $comment->task->project->team_id, 403);
        abort_unless($comment->user_id === auth()->id(), 403);

        $comment->delete();
    }

    public function render()
    {
        return view('livewire.task-comment-form', [
            'comments' => Task::findOrFail($this->taskId)
                ->comments()
                ->with('user')
                ->get(),
        ]);
    }
}
