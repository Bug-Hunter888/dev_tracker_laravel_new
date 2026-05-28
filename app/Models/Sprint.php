<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sprint extends Model
{
    protected $fillable = ['project_id', 'name', 'goal', 'start_date', 'end_date', 'status'];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function isPlanning(): bool  { return $this->status === 'planning'; }
    public function isActive(): bool    { return $this->status === 'active'; }
    public function isCompleted(): bool { return $this->status === 'completed'; }

    public function doneCount(): int
    {
        return $this->tasks()->where('status', 'done')->count();
    }

    public function totalCount(): int
    {
        return $this->tasks()->count();
    }
}
