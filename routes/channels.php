<?php

use App\Models\Team;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private channel for a team — any member of the team can join
Broadcast::channel('team.{teamId}', function ($user, $teamId) {
    $team = Team::find($teamId);
    return $team && $user->belongsToTeam($team);
});
