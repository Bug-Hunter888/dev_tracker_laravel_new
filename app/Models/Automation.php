<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Automation extends Model
{
    protected $fillable = [
        'project_id', 'name', 'trigger',
        'conditions', 'actions', 'enabled',
    ];

    protected $casts = [
        'conditions' => 'array',
        'actions'    => 'array',
        'enabled'    => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
