<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class AdminTeamController extends Controller
{
    public function index(Request $request)
    {
        $query = Team::with('owner')
            ->withCount(['users', 'projects'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $teams = $query->paginate(20)->withQueryString();

        return view('admin.teams.index', compact('teams'));
    }

    public function changePlan(Request $request, Team $team)
    {
        $request->validate(['plan' => 'required|in:free,pro,team']);

        $team->update(['plan' => $request->plan]);

        return back()->with('success', "Team \"{$team->name}\" upgraded to " . strtoupper($request->plan) . '.');
    }

    public function destroy(Team $team)
    {
        $team->delete();

        return back()->with('success', 'Team and all its data deleted.');
    }
}
