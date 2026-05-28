<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class AdminProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['team', 'creator'])
            ->withCount(['sprints', 'tasks'])
            ->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        $projects = $query->paginate(25)->withQueryString();
        $teams    = Team::orderBy('name')->get(['id', 'name']);
        $users    = User::orderBy('name')->get(['id', 'name']);

        return view('admin.projects.index', compact('projects', 'teams', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'team_id'     => 'required|exists:teams,id',
            'created_by'  => 'required|exists:users,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'color'       => 'nullable|string|max:7',
            'status'      => 'required|in:active,archived,completed',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $data['color'] = $data['color'] ?? '#39FF14';

        Project::create($data);

        return back()->with('success', "Project \"{$data['name']}\" created.");
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'color'       => 'nullable|string|max:7',
            'status'      => 'required|in:active,archived,completed',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $project->update($data);

        return back()->with('success', "Project \"{$project->name}\" updated.");
    }

    public function destroy(Project $project)
    {
        $name = $project->name;
        // DB cascade handles sprints → tasks → comments/attachments
        $project->delete();

        return back()->with('success', "Project \"{$name}\" and all its data deleted.");
    }
}
