<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Project extends Model
{
    protected $fillable = [
        'team_id', 'created_by', 'name', 'description',
        'color', 'status', 'start_date', 'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('position');
    }

    public function labels(): HasMany
    {
        return $this->hasMany(Label::class, 'team_id', 'team_id');
    }

    public function sprints(): HasMany
    {
        return $this->hasMany(Sprint::class)->latest();
    }

    public function epics(): HasMany
    {
        return $this->hasMany(Epic::class)->orderBy('name');
    }

    public function automations(): HasMany
    {
        return $this->hasMany(Automation::class);
    }

    public function todoTasks()
    {
        return $this->tasks()->where('status', 'todo');
    }

    public function doingTasks()
    {
        return $this->tasks()->where('status', 'doing');
    }

    public function doneTasks()
    {
        return $this->tasks()->where('status', 'done');
    }
}
