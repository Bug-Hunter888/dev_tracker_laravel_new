<?php

namespace App\Http\Controllers;

use App\Actions\Jetstream\InviteTeamMember;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OnboardingController extends Controller
{
    public function welcome()
    {
        if (auth()->user()->is_onboarded) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.welcome');
    }

    public function sprint()
    {
        if (auth()->user()->is_onboarded) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.sprint');
    }

    public function storeSprint(Request $request)
    {
        $validated = $request->validate([
            'sprint_name' => 'required|string|max:100',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        $user = auth()->user();
        $team = $user->currentTeam ?? $user->ownedTeams()->first();

        $project = Project::create([
            'team_id'    => $team->id,
            'created_by' => $user->id,
            'name'       => 'My First Project',
            'status'     => 'active',
            'color'      => '#39FF14',
        ]);

        $sprint = Sprint::create([
            'project_id' => $project->id,
            'name'       => $validated['sprint_name'],
            'start_date' => $validated['start_date'],
            'end_date'   => $validated['end_date'],
            'status'     => 'planning',
        ]);

        session([
            'onboarding_project_id' => $project->id,
            'onboarding_sprint_id'  => $sprint->id,
        ]);

        return redirect()->route('onboarding.backlog');
    }

    public function backlog()
    {
        if (auth()->user()->is_onboarded) {
            return redirect()->route('dashboard');
        }

        $sprint = Sprint::find(session('onboarding_sprint_id'));

        if (! $sprint) {
            return redirect()->route('onboarding.sprint');
        }

        $user            = auth()->user();
        $team            = $user->currentTeam ?? $user->ownedTeams()->first();
        $members         = $team ? $team->allUsers() : collect([$user]);
        $pendingInvites  = $team ? $team->teamInvitations()->latest()->get() : collect();

        return view('onboarding.backlog', compact('sprint', 'members', 'pendingInvites'));
    }

    public function storeBacklog(Request $request)
    {
        $request->validate([
            'tasks'          => 'required|array|min:1',
            'tasks.*'        => 'required|string|max:255',
            'assigned_to.*'  => 'nullable|exists:users,id',
        ]);

        $user      = auth()->user();
        $sprintId  = session('onboarding_sprint_id');
        $projectId = session('onboarding_project_id');

        $assignees = $request->input('assigned_to', []);

        foreach ($request->tasks as $index => $title) {
            if (trim($title) === '') {
                continue;
            }
            Task::create([
                'project_id'  => $projectId,
                'sprint_id'   => $sprintId,
                'created_by'  => $user->id,
                'assigned_to' => $assignees[$index] ?: null,
                'title'       => $title,
                'status'      => 'backlog',
                'priority'    => 'normal',
                'position'    => $index,
            ]);
        }

        return redirect()->route('onboarding.board');
    }

    public function invite(Request $request, InviteTeamMember $inviter)
    {
        $request->validate(['email' => 'required|email']);

        $user = auth()->user();
        $team = $user->currentTeam ?? $user->ownedTeams()->first();

        try {
            $inviter->invite($user, $team, $request->email, 'editor');
            return redirect()->route('onboarding.backlog')
                ->with('invite_success', 'Invite sent to ' . $request->email);
        } catch (ValidationException $e) {
            $msg = collect($e->errors())->flatten()->first();
            return redirect()->route('onboarding.backlog')
                ->with('invite_error', $msg);
        }
    }

    public function skipBacklog()
    {
        return redirect()->route('onboarding.board');
    }

    public function board()
    {
        if (auth()->user()->is_onboarded) {
            return redirect()->route('dashboard');
        }

        $project = Project::find(session('onboarding_project_id'));

        if (! $project) {
            return redirect()->route('onboarding.sprint');
        }

        return view('onboarding.board', compact('project'));
    }

    public function storeBoard(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'color' => 'nullable|string|max:7',
        ]);

        $project = Project::find(session('onboarding_project_id'));

        if ($project) {
            $project->update([
                'name'  => $validated['name'],
                'color' => $validated['color'] ?? '#39FF14',
            ]);
        }

        $this->completeOnboarding();

        return redirect()->route('dashboard')->with('success', 'Workspace ready. Let\'s ship.');
    }

    public function skipBoard()
    {
        $this->completeOnboarding();

        return redirect()->route('dashboard')->with('success', 'Workspace ready. Let\'s ship.');
    }

    private function completeOnboarding(): void
    {
        auth()->user()->update(['is_onboarded' => true]);
        session()->forget(['onboarding_project_id', 'onboarding_sprint_id']);
    }
}
