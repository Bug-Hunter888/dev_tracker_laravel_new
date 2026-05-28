<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamInvitation;

class AdminCollaboratorController extends Controller
{
    public function index()
    {
        $teams = Team::with(['owner', 'users', 'teamInvitations'])
            ->where('personal_team', false)
            ->latest()
            ->get();

        $pendingInvites = TeamInvitation::with('team')
            ->where('status', 'pending')
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->latest()
            ->get();

        $expiredInvites = TeamInvitation::with('team')
            ->where(fn ($q) => $q->where('status', 'expired')->orWhere('expires_at', '<=', now()))
            ->latest()
            ->get();

        return view('admin.collaborators.index', compact('teams', 'pendingInvites', 'expiredInvites'));
    }

    public function revokeInvitation(TeamInvitation $invitation)
    {
        $invitation->update(['status' => 'revoked']);
        $invitation->delete();

        return back()->with('success', 'Invitation revoked.');
    }
}
