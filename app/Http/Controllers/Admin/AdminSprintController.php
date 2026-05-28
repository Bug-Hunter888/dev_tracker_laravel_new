<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sprint;
use Illuminate\Http\Request;

class AdminSprintController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name'       => 'required|string|max:255',
            'goal'       => 'nullable|string',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'status'     => 'required|in:planning,active,completed',
        ]);

        Sprint::create($data);

        return back()->with('success', "Sprint \"{$data['name']}\" created.");
    }

    public function update(Request $request, Sprint $sprint)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'goal'       => 'nullable|string',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'status'     => 'required|in:planning,active,completed',
        ]);

        $sprint->update($data);

        return back()->with('success', "Sprint \"{$sprint->name}\" updated.");
    }

    public function destroy(Sprint $sprint)
    {
        $name = $sprint->name;
        // Unlink tasks from sprint before deleting (tasks stay, sprint reference removed)
        $sprint->tasks()->update(['sprint_id' => null]);
        $sprint->delete();

        return back()->with('success', "Sprint \"{$name}\" deleted.");
    }
}
