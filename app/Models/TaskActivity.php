<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskActivity extends Model
{
    protected $fillable = ['task_id', 'user_id', 'action', 'old_value', 'new_value'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function description(): string
    {
        return match ($this->action) {
            'created'          => 'created this task',
            'status_changed'   => "moved from {$this->old_value} → {$this->new_value}",
            'assigned'         => "assigned to {$this->new_value}",
            'priority_changed' => "changed priority to {$this->new_value}",
            'due_date_set'     => "set due date to {$this->new_value}",
            'commented'        => 'added a comment',
            'attached_file'    => "attached file: {$this->new_value}",
            default            => $this->action,
        };
    }
}
