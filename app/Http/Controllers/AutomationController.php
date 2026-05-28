<?php

namespace App\Http\Controllers;

use App\Models\Automation;
use App\Models\Project;
use Illuminate\Http\Request;

class AutomationController extends Controller
{
    public function index(Project $project)
    {
        $this->authorize('manageAutomations', $project);

        $automations = $project->automations()->latest()->get();
        $canUseAutomations = $project->team->canUseAutomations();

        return view('automations.index', compact('project', 'automations', 'canUseAutomations'));
    }

    public function store(Request $request, Project $project)
    {
        $this->authorize('manageAutomations', $project);

        if (!$project->team->canUseAutomations()) {
            return redirect()->route('automations.index', $project)
                ->with('error', 'Automations require PRO or TEAM plan. Ask your admin to upgrade.');
        }

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'trigger' => 'required|in:task_marked_done,task_marked_doing,task_marked_todo,task_assigned',
            'action_type'  => 'required|in:notify_assignee,notify_creator,set_priority,move_status',
            'action_value' => 'nullable|string|max:255',
            'action_message' => 'nullable|string|max:500',
        ]);

        $action = ['type' => $validated['action_type']];

        if (in_array($validated['action_type'], ['set_priority', 'move_status'])) {
            $action['value'] = $validated['action_value'] ?? '';
        }
        if (in_array($validated['action_type'], ['notify_assignee', 'notify_creator'])) {
            $action['message'] = $validated['action_message'] ?? '';
        }

        $project->automations()->create([
            'name'    => $validated['name'],
            'trigger' => $validated['trigger'],
            'actions' => [$action],
            'enabled' => true,
        ]);

        return redirect()->route('automations.index', $project)->with('success', 'Automation created.');
    }

    public function toggle(Automation $automation)
    {
        $this->authorize('manageAutomations', $automation->project);

        $automation->update(['enabled' => !$automation->enabled]);

        return back()->with('success', 'Automation ' . ($automation->enabled ? 'disabled' : 'enabled') . '.');
    }

    public function destroy(Automation $automation)
    {
        $this->authorize('manageAutomations', $automation->project);

        $project = $automation->project;
        $automation->delete();

        return redirect()->route('automations.index', $project)->with('success', 'Automation deleted.');
    }
}
