<?php

namespace App\Models;

use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    /** @use HasFactory<TeamFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'personal_team',
        'plan',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
        ];
    }

    public function projectLimit(): ?int
    {
        return null;
    }

    public function isAtProjectLimit(): bool
    {
        return false;
    }

    public function canUseAutomations(): bool
    {
        return true;
    }

    public function canUseAnalytics(): bool
    {
        return true;
    }

    public function planLabel(): string
    {
        return strtoupper($this->plan);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function labels(): HasMany
    {
        return $this->hasMany(Label::class);
    }
}
