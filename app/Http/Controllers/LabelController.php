<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index()
    {
        $team   = auth()->user()->currentTeam;
        $labels = $team->labels()->withCount('tasks')->orderBy('name')->get();

        return view('labels.index', compact('labels', 'team'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:50',
            'color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        auth()->user()->currentTeam->labels()->create($validated);

        return back()->with('success', 'Label created.');
    }

    public function update(Request $request, Label $label)
    {
        abort_unless(auth()->user()->currentTeam->id === $label->team_id, 403);

        $validated = $request->validate([
            'name'  => 'required|string|max:50',
            'color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $label->update($validated);

        return back()->with('success', 'Label updated.');
    }

    public function destroy(Label $label)
    {
        abort_unless(auth()->user()->currentTeam->id === $label->team_id, 403);

        $label->delete();

        return back()->with('success', 'Label deleted.');
    }
}
