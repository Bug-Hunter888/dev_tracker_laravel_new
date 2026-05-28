<?php

namespace Database\Seeders;

use App\Models\Epic;
use App\Models\Label;
use App\Models\Message;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ──────────────────────────────────────────────────────────
        $admin = User::factory()->withPersonalTeam()->create([
            'name'              => 'Admin User',
            'email'             => 'admin@devtracker.test',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin'          => true,
            'is_onboarded'      => true,
        ]);

        $alice = User::factory()->withPersonalTeam()->create([
            'name'              => 'Alice Dev',
            'email'             => 'alice@devtracker.test',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
            'is_onboarded'      => true,
        ]);

        $bob = User::factory()->withPersonalTeam()->create([
            'name'              => 'Bob Engineer',
            'email'             => 'bob@devtracker.test',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
            'is_onboarded'      => true,
        ]);

        // ── Shared workspace team ──────────────────────────────────────────
        $team = Team::create([
            'user_id'       => $admin->id,
            'name'          => 'DevTracker Studio',
            'personal_team' => false,
            'plan'          => 'pro',
        ]);

        $team->users()->attach($alice->id, ['role' => 'editor']);
        $team->users()->attach($bob->id,   ['role' => 'editor']);

        // Switch everyone's active team to the shared workspace
        $admin->forceFill(['current_team_id' => $team->id])->save();
        $alice->forceFill(['current_team_id' => $team->id])->save();
        $bob->forceFill(['current_team_id'   => $team->id])->save();

        // ── Labels ─────────────────────────────────────────────────────────
        [$bug, $feature, $urgent, $frontend, $backend] = collect([
            ['name' => 'BUG',      'color' => '#ef4444'],
            ['name' => 'FEATURE',  'color' => '#39FF14'],
            ['name' => 'URGENT',   'color' => '#f97316'],
            ['name' => 'FRONTEND', 'color' => '#60a5fa'],
            ['name' => 'BACKEND',  'color' => '#a78bfa'],
        ])->map(fn ($l) => Label::create(['team_id' => $team->id] + $l));

        // ── Project 1: DevTracker App ──────────────────────────────────────
        $project = Project::create([
            'team_id'     => $team->id,
            'created_by'  => $admin->id,
            'name'        => 'DevTracker App',
            'description' => 'Main application — building the tracker itself.',
            'color'       => '#39FF14',
            'status'      => 'active',
            'start_date'  => Carbon::now()->subDays(30),
            'end_date'    => Carbon::now()->addDays(30),
        ]);

        // ── Epics ──────────────────────────────────────────────────────────
        $epicAuth = Epic::create([
            'project_id'  => $project->id,
            'created_by'  => $admin->id,
            'name'        => 'Authentication & Onboarding',
            'description' => 'Login, registration, teams, invites.',
            'color'       => '#60a5fa',
            'status'      => 'open',
        ]);

        $epicBoard = Epic::create([
            'project_id'  => $project->id,
            'created_by'  => $admin->id,
            'name'        => 'Kanban Board',
            'description' => 'Core board, columns, task detail, Livewire comments.',
            'color'       => '#a78bfa',
            'status'      => 'open',
        ]);

        // ── Sprint 1 (completed) ───────────────────────────────────────────
        $sprint1 = Sprint::create([
            'project_id' => $project->id,
            'name'       => 'Sprint 1 — Core Auth',
            'goal'       => 'Ship working auth and team management.',
            'start_date' => Carbon::now()->subDays(28),
            'end_date'   => Carbon::now()->subDays(15),
            'status'     => 'completed',
        ]);

        foreach ([
            ['title' => 'Set up Laravel + Jetstream',    'assigned_to' => $admin->id, 'story_points' => 3],
            ['title' => 'Design login & register pages', 'assigned_to' => $alice->id, 'story_points' => 5],
            ['title' => 'Implement email verification',  'assigned_to' => $bob->id,   'story_points' => 2],
            ['title' => 'Add admin role + middleware',   'assigned_to' => $admin->id, 'story_points' => 3],
        ] as $i => $data) {
            $task = Task::create(array_merge($data, [
                'project_id' => $project->id,
                'created_by' => $admin->id,
                'sprint_id'  => $sprint1->id,
                'epic_id'    => $epicAuth->id,
                'status'     => 'done',
                'priority'   => 'normal',
                'position'   => $i,
                'due_date'   => Carbon::now()->subDays(16),
            ]));
            $task->labels()->attach($backend->id);
        }

        // ── Sprint 2 (active) ──────────────────────────────────────────────
        $sprint2 = Sprint::create([
            'project_id' => $project->id,
            'name'       => 'Sprint 2 — Board Features',
            'goal'       => 'Deliver Kanban board, Livewire comments, and REST API.',
            'start_date' => Carbon::now()->subDays(7),
            'end_date'   => Carbon::now()->addDays(7),
            'status'     => 'active',
        ]);

        $sprint2Rows = [
            ['title' => 'Build Kanban board columns',    'status' => 'done',  'priority' => 'critical', 'assigned_to' => $alice->id, 'epic_id' => $epicBoard->id, 'story_points' => 5, 'labels' => [$frontend->id]],
            ['title' => 'Task creation & edit forms',    'status' => 'done',  'priority' => 'critical', 'assigned_to' => $admin->id, 'epic_id' => $epicBoard->id, 'story_points' => 3, 'labels' => [$backend->id]],
            ['title' => 'Task detail page with sidebar', 'status' => 'done',  'priority' => 'normal',   'assigned_to' => $alice->id, 'epic_id' => $epicBoard->id, 'story_points' => 5, 'labels' => [$frontend->id]],
            ['title' => 'Live comment form (Livewire)',  'status' => 'doing', 'priority' => 'normal',   'assigned_to' => $alice->id, 'epic_id' => $epicBoard->id, 'story_points' => 3, 'labels' => [$feature->id, $frontend->id]],
            ['title' => 'Sanctum token API endpoints',  'status' => 'doing', 'priority' => 'normal',   'assigned_to' => $bob->id,   'epic_id' => null,           'story_points' => 5, 'labels' => [$feature->id, $backend->id]],
            ['title' => 'Sprint burndown chart',        'status' => 'todo',  'priority' => 'normal',   'assigned_to' => $bob->id,   'epic_id' => $epicBoard->id, 'story_points' => 3, 'labels' => [$frontend->id]],
            ['title' => 'Mobile responsive navigation', 'status' => 'todo',  'priority' => 'low',      'assigned_to' => $alice->id, 'epic_id' => null,           'story_points' => 2, 'labels' => [$frontend->id]],
        ];

        $livewireTask = null;
        foreach ($sprint2Rows as $i => $data) {
            $labelIds = $data['labels'];
            unset($data['labels']);
            $task = Task::create(array_merge($data, [
                'project_id' => $project->id,
                'created_by' => $admin->id,
                'sprint_id'  => $sprint2->id,
                'position'   => $i,
                'due_date'   => Carbon::now()->addDays(5),
            ]));
            $task->labels()->attach($labelIds);
            if ($data['title'] === 'Live comment form (Livewire)') {
                $livewireTask = $task;
            }
        }

        // ── Standalone board tasks (no sprint) ────────────────────────────
        foreach ([
            ['title' => 'Fix overdue badge colour on dark bg', 'status' => 'todo',  'priority' => 'normal',   'assigned_to' => $bob->id,   'labels' => [$bug->id, $frontend->id],  'days' => 3],
            ['title' => 'Analytics dashboard metrics',          'status' => 'doing', 'priority' => 'normal',   'assigned_to' => $alice->id, 'labels' => [$feature->id],            'days' => 10],
            ['title' => 'Notification email on task assign',    'status' => 'todo',  'priority' => 'low',      'assigned_to' => null,       'labels' => [$backend->id],            'days' => 14],
            ['title' => 'Deploy to Railway',                    'status' => 'todo',  'priority' => 'critical', 'assigned_to' => $admin->id, 'labels' => [$urgent->id],             'days' => 7],
        ] as $i => $data) {
            $labelIds = $data['labels'];
            $days     = $data['days'];
            unset($data['labels'], $data['days']);
            $task = Task::create(array_merge($data, [
                'project_id' => $project->id,
                'created_by' => $admin->id,
                'position'   => $i,
                'due_date'   => Carbon::now()->addDays($days),
            ]));
            $task->labels()->attach($labelIds);
        }

        // ── Backlog ────────────────────────────────────────────────────────
        foreach ([
            ['title' => 'Dark mode toggle',           'priority' => 'low',    'labels' => [$feature->id]],
            ['title' => 'Export tasks to CSV',        'priority' => 'normal', 'labels' => [$feature->id, $backend->id]],
            ['title' => 'GitHub issues import',       'priority' => 'normal', 'labels' => [$feature->id, $backend->id]],
            ['title' => 'Recurring task automation',  'priority' => 'low',    'labels' => [$feature->id]],
            ['title' => 'Custom task status columns', 'priority' => 'normal', 'labels' => [$feature->id]],
        ] as $i => $data) {
            $labelIds = $data['labels'];
            unset($data['labels']);
            $task = Task::create(array_merge($data, [
                'project_id'  => $project->id,
                'created_by'  => $admin->id,
                'assigned_to' => null,
                'status'      => 'backlog',
                'position'    => $i,
            ]));
            $task->labels()->attach($labelIds);
        }

        // ── Comments on the Livewire task ──────────────────────────────────
        if ($livewireTask) {
            TaskComment::create(['task_id' => $livewireTask->id, 'user_id' => $admin->id, 'content' => 'Use wire:model.live so the char counter updates without a full page reload.', 'created_at' => now()->subMinutes(45)]);
            TaskComment::create(['task_id' => $livewireTask->id, 'user_id' => $alice->id, 'content' => 'Done — also added wire:poll.15s on the list so new comments appear automatically for other users.', 'created_at' => now()->subMinutes(30)]);
            TaskComment::create(['task_id' => $livewireTask->id, 'user_id' => $bob->id,   'content' => 'Solid. Should we also wire up delete so it works without a page reload?', 'created_at' => now()->subMinutes(10)]);
        }

        // ── Project 2: Marketing Site ──────────────────────────────────────
        $marketing = Project::create([
            'team_id'     => $team->id,
            'created_by'  => $alice->id,
            'name'        => 'Marketing Site',
            'description' => 'Landing page, pricing, blog.',
            'color'       => '#f97316',
            'status'      => 'active',
            'start_date'  => Carbon::now()->subDays(10),
            'end_date'    => Carbon::now()->addDays(20),
        ]);

        foreach ([
            ['title' => 'Design landing page hero',  'status' => 'done',  'priority' => 'critical', 'assigned_to' => $alice->id, 'days' => -1],
            ['title' => 'Write pricing copy',         'status' => 'doing', 'priority' => 'normal',   'assigned_to' => $admin->id, 'days' => 5],
            ['title' => 'SEO meta tags',              'status' => 'todo',  'priority' => 'low',      'assigned_to' => $bob->id,   'days' => 12],
            ['title' => 'Mobile layout audit',        'status' => 'todo',  'priority' => 'normal',   'assigned_to' => $alice->id, 'days' => 8],
        ] as $i => $data) {
            $days = $data['days'];
            unset($data['days']);
            Task::create(array_merge($data, [
                'project_id' => $marketing->id,
                'created_by' => $alice->id,
                'position'   => $i,
                'due_date'   => Carbon::now()->addDays($days),
            ]));
        }

        // ── Team chat messages ─────────────────────────────────────────────
        foreach ([
            [$admin->id, 'Sprint 2 is live — focus on Livewire and the Sanctum API this week.',          -60],
            [$alice->id, 'On it. Starting with the comment form.',                                        -50],
            [$bob->id,   'I\'ll handle the token endpoints. Should be done by tomorrow.',                 -40],
            [$alice->id, 'Livewire component is up — char counter and auto-refresh both working.',        -15],
            [$admin->id, 'Nice. Test it with two browser tabs open to verify the poll works end-to-end.',  -5],
        ] as [$uid, $text, $minsAgo]) {
            Message::create([
                'team_id'    => $team->id,
                'user_id'    => $uid,
                'content'    => $text,
                'created_at' => now()->addMinutes($minsAgo),
                'updated_at' => now()->addMinutes($minsAgo),
            ]);
        }
    }
}
