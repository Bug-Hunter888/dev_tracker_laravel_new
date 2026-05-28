<?php

namespace App\Http\Controllers;

use App\Models\TeamInvitation;
use Illuminate\Http\Request;
use Laravel\Jetstream\Contracts\AddsTeamMembers;

class TeamInvitationController extends Controller
{
    public function accept(Request $request, $invitationId)
    {
        $invitation = TeamInvitation::findOrFail($invitationId);

        // Expired check
        if ($invitation->isExpired()) {
            $invitation->update(['status' => 'expired']);

            return redirect()->route('register')
                ->withErrors(['invitation' => 'This invitation link has expired. Please ask the team owner to send a new one.']);
        }

        // Revoked check
        if ($invitation->status === 'revoked') {
            return redirect()->route('register')
                ->withErrors(['invitation' => 'This invitation has been revoked.']);
        }

        $team = $invitation->team;

        // If the invited email doesn't have an account yet, redirect to register
        // with the invitation URL stored so they come back after registering.
        $invitedUser = \App\Models\User::where('email', $invitation->email)->first();

        if (! $invitedUser) {
            session(['url.intended' => $request->fullUrl()]);
            return redirect()->route('register')
                ->with('invitation_email', $invitation->email)
                ->with('status', 'Please create an account to join ' . $team->name . '.');
        }

        // Add member to team (this also sets is_onboarded=true and switches current_team)
        app(AddsTeamMembers::class)->add(
            $team->owner,
            $team,
            $invitation->email,
            $invitation->role
        );

        $invitation->delete();

        return redirect()->route('dashboard')
            ->with('success', 'You have joined ' . $team->name . '. Welcome to the team!');
    }
}
