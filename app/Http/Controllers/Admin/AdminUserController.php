<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['ownedTeams', 'teams', 'createdProjects'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
            );
        }

        if ($request->filled('filter')) {
            match ($request->filter) {
                'admin'    => $query->where('is_admin', true),
                'disabled' => $query->where('is_banned', true),
                default    => null,
            };
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $createdProjects = Project::where('created_by', $user->id)
            ->with(['sprints', 'team'])
            ->withCount(['tasks', 'sprints'])
            ->latest()
            ->get();

        $teams = $user->allTeams()->load('owner');

        $assignedTasks = Task::where('assigned_to', $user->id)
            ->with(['project', 'sprint'])
            ->latest()
            ->take(10)
            ->get();

        $taskStats = [
            'created'  => Task::where('created_by', $user->id)->count(),
            'assigned' => Task::where('assigned_to', $user->id)->count(),
            'done'     => Task::where('assigned_to', $user->id)->where('status', 'done')->count(),
        ];

        return view('admin.users.show', compact('user', 'createdProjects', 'teams', 'assignedTasks', 'taskStats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'is_admin' => 'nullable|boolean',
        ]);

        User::factory()->withPersonalTeam()->create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => Hash::make($data['password']),
            'email_verified_at' => now(),
            'is_admin'          => (bool) ($data['is_admin'] ?? false),
        ]);

        return back()->with('success', "User {$data['name']} created.");
    }

    public function update(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot modify your own account from this panel.');
        }

        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'is_admin'  => 'nullable|boolean',
            'is_banned' => 'nullable|boolean',
        ]);

        $data['is_admin']  = (bool) ($data['is_admin'] ?? false);
        $data['is_banned'] = (bool) ($data['is_banned'] ?? false);

        // Clear sessions when disabling
        if ($data['is_banned'] && !$user->is_banned) {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        $user->update($data);

        return back()->with('success', 'User updated.');
    }

    public function ban(User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'Cannot disable an admin user.');
        }

        $wasDisabled = $user->is_banned;
        $user->update(['is_banned' => !$wasDisabled]);

        if (!$wasDisabled) {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        return back()->with('success', 'User ' . ($wasDisabled ? 'enabled' : 'disabled') . '.');
    }

    public function makeAdmin(User $user)
    {
        $user->update(['is_admin' => !$user->is_admin]);

        return back()->with('success', 'User admin status updated.');
    }

    public function resetPassword(User $user)
    {
        $status = Password::sendResetLink(['email' => $user->email]);

        return back()->with(
            $status === Password::RESET_LINK_SENT ? 'success' : 'error',
            $status === Password::RESET_LINK_SENT
                ? 'Password reset link sent to ' . $user->email
                : 'Failed to send reset link.'
        );
    }

    public function deleteData(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete your own data.');
        }

        $count = Project::where('created_by', $user->id)->count();
        // DB cascades handle sprints, tasks, comments, attachments
        Project::where('created_by', $user->id)->delete();

        return back()->with('success', "Deleted {$count} project(s) and all associated data for {$user->name}.");
    }

    public function destroy(User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'Cannot delete an admin user.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete your own account.');
        }

        DB::table('sessions')->where('user_id', $user->id)->delete();
        $name = $user->name;
        // DB cascade on created_by FK deletes their projects → sprints → tasks
        $user->delete();

        return back()->with('success', "User {$name} and all their data deleted.");
    }
}
