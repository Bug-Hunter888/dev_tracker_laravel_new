<?php

namespace App\Http\Controllers;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'techStack'    => $this->techStack(),
            'stats'        => $this->stats(),
            'features'     => $this->features(),
            'steps'        => $this->steps(),
            'testimonials' => $this->testimonials(),
            'pricing'      => $this->pricing(),
            'boardData'    => $this->boardData(),
            'velocityBars' => [40, 55, 30, 70, 50, 80, 65, 90, 75, 60, 85, 95],
        ]);
    }

    private function techStack(): array
    {
        $items = [
            'Laravel', 'MySQL', 'WebSockets', 'Tailwind CSS', 'Alpine.js',
            'Laravel Reverb', 'Kanban', 'Automations', 'Real-Time', 'Teams',
            'Analytics', 'File Attachments',
        ];
        return array_merge($items, $items); // duplicate for seamless marquee loop
    }

    private function stats(): array
    {
        return [
            ['value' => '10,000+', 'count' => 10000, 'suffix' => '+', 'label' => 'TASKS SHIPPED'],
            ['value' => '500+',    'count' => 500,   'suffix' => '+', 'label' => 'DEV TEAMS'],
            ['value' => '99.9%',   'count' => null,                   'label' => 'UPTIME SLA'],
            ['value' => '0',       'count' => null,                   'label' => 'BLOAT'],
        ];
    }

    private function features(): array
    {
        return [
            [
                'icon'        => "┌─┬─┐<br>├─┼─┤<br>└─┴─┘",
                'title'       => 'KANBAN_BOARDS',
                'description' => 'Visual task management with TODO, IN_PROGRESS, and DONE columns. Priority labels, due dates, and assignees baked in.',
            ],
            [
                'icon'        => "&lt;●&gt;<br>&nbsp;╲&nbsp;╱<br>&nbsp;&nbsp;◉",
                'title'       => 'REAL_TIME_UPDATES',
                'description' => 'See board changes live as teammates move tasks. WebSocket-powered — no refresh needed. Banner alerts keep everyone synced.',
            ],
            [
                'icon'        => "IF&nbsp;──▶<br>THEN&nbsp;▶<br>DO&nbsp;───▶",
                'title'       => 'AUTOMATIONS',
                'description' => 'Trigger-action rules: when a task ships, auto-notify, change priority, or reassign. Zero manual overhead.',
            ],
            [
                'icon'        => "▁▃▅▇<br>█▇▅▃<br>▁▂▄▆",
                'title'       => 'ANALYTICS',
                'description' => 'Track velocity, overdue tasks, completion rates, and per-member output. Charts that actually show where the bottleneck is.',
            ],
            [
                'icon'        => "[A]:&nbsp;hey<br>[B]:&nbsp;ack<br>[A]:&nbsp;done▌",
                'title'       => 'TEAM_CHAT',
                'description' => 'Built-in real-time messaging — no Slack tab required. Chat panel lives on every page, scoped per team.',
            ],
            [
                'icon'        => "[📎 spec.pdf]<br>[📎 mockup.fig]<br>[📎 repro.log]",
                'title'       => 'FILE_ATTACHMENTS',
                'description' => 'Attach files directly to tasks. Designs, specs, logs — keep context where the work is. Download anytime.',
            ],
        ];
    }

    private function steps(): array
    {
        return [
            [
                'number'      => '01',
                'title'       => 'CREATE_ACCOUNT',
                'description' => 'Sign up free. Create your team workspace, invite teammates by email. Roles handled automatically — owner, admin, or member.',
                'code'        => '$ register --team="my-squad"',
                'cursor'      => true,
            ],
            [
                'number'      => '02',
                'title'       => 'CREATE_PROJECT',
                'description' => 'Name your project, set a deadline, pick a colour. Add tasks to the kanban, assign them, set priorities and due dates.',
                'code'        => '$ project.create --name="v2-launch"',
                'cursor'      => false,
            ],
            [
                'number'      => '03',
                'title'       => 'SHIP_TASKS',
                'description' => 'Move tasks across the board, get notified on changes, and watch the analytics dashboard fill up with green numbers.',
                'code'        => '$ task.ship --id=42 --notify',
                'cursor'      => false,
            ],
        ];
    }

    private function testimonials(): array
    {
        return [
            [
                'stars'   => 5,
                'quote'   => '"Finally a PM tool that doesn\'t slow us down. We killed Jira two months ago and never looked back. The kanban board just works."',
                'name'    => 'Maya R.',
                'role'    => 'Lead Dev · Forge Studio',
                'initial' => 'M',
                'accent'  => true,
            ],
            [
                'stars'   => 5,
                'quote'   => '"The brutalist design is intentional and I respect it. Does exactly what it says, zero fluff. Automations alone saved us hours a week."',
                'name'    => 'Tom K.',
                'role'    => 'Freelance Engineer',
                'initial' => 'T',
                'accent'  => false,
            ],
            [
                'stars'   => 5,
                'quote'   => '"Real-time updates and team chat in one tool. Our deploy notifications now trigger task status changes automatically. Huge win."',
                'name'    => 'Sarah L.',
                'role'    => 'DevOps · Stackline',
                'initial' => 'S',
                'accent'  => false,
            ],
        ];
    }

    private function pricing(): array
    {
        return [
            [
                'tier'     => '01',
                'name'     => 'FREE',
                'price'    => '$0',
                'period'   => '/forever',
                'featured' => false,
                'badge'    => null,
                'features' => [
                    ['text' => 'Up to 3 projects',  'included' => true],
                    ['text' => '5 team members',     'included' => true],
                    ['text' => 'Kanban boards',      'included' => true],
                    ['text' => 'Team chat',          'included' => true],
                    ['text' => 'Automations',        'included' => false],
                    ['text' => 'Analytics',          'included' => false],
                    ['text' => 'File attachments',   'included' => false],
                ],
                'cta'      => 'START_FREE',
                'cta_type' => 'ghost',
            ],
            [
                'tier'     => '02',
                'name'     => 'PRO',
                'price'    => '$9',
                'period'   => '/mo per team',
                'featured' => true,
                'badge'    => '★ MOST POPULAR',
                'features' => [
                    ['text' => 'Unlimited projects',      'included' => true],
                    ['text' => 'Up to 15 members',        'included' => true],
                    ['text' => 'Kanban + Timeline views', 'included' => true],
                    ['text' => 'Workflow automations',    'included' => true],
                    ['text' => 'Analytics dashboard',     'included' => true],
                    ['text' => 'File attachments (5GB)',  'included' => true],
                    ['text' => 'Email notifications',     'included' => true],
                ],
                'cta'      => 'START_PRO >>',
                'cta_type' => 'primary',
            ],
            [
                'tier'     => '03',
                'name'     => 'TEAM',
                'price'    => '$29',
                'period'   => '/mo per team',
                'featured' => false,
                'badge'    => null,
                'features' => [
                    ['text' => 'Everything in PRO',     'included' => true],
                    ['text' => 'Unlimited members',     'included' => true],
                    ['text' => 'Unlimited file storage','included' => true],
                    ['text' => 'Priority support',      'included' => true],
                    ['text' => 'Audit logs',            'included' => true],
                    ['text' => 'SSO / custom auth',     'included' => true],
                    ['text' => 'Dedicated onboarding',  'included' => true],
                ],
                'cta'      => 'CONTACT_US',
                'cta_type' => 'ghost',
            ],
        ];
    }

    private function boardData(): array
    {
        return [
            'totalMembers' => 4,
            'totalTasks'   => 10,

            'priorityColors' => [
                'red'    => 'border-red-500 text-red-400',
                'yellow' => 'border-yellow-600 text-yellow-500',
                'gray'   => 'border-gray-600 text-gray-500',
            ],

            'columns' => [
                [
                    'id'             => 'todo',
                    'label'          => 'TODO',
                    'header_classes' => 'text-white border-white',
                    'task_accent'    => '',
                    'shipped'        => false,
                    'tasks' => [
                        ['title' => 'Fix OAuth callback',     'priority' => 'CRITICAL', 'color' => 'red',    'user' => 'MJ'],
                        ['title' => 'Write E2E tests',        'priority' => 'NORMAL',   'color' => 'yellow', 'user' => 'TK'],
                        ['title' => 'Add CSV export',         'priority' => 'LOW',      'color' => 'gray',   'user' => 'SL'],
                        ['title' => 'Update email templates', 'priority' => 'LOW',      'color' => 'gray',   'user' => 'RD'],
                    ],
                ],
                [
                    'id'             => 'doing',
                    'label'          => 'IN_PROGRESS',
                    'header_classes' => 'text-yellow-500 border-yellow-500',
                    'task_accent'    => 'border-l-2 border-l-yellow-500',
                    'shipped'        => false,
                    'tasks' => [
                        ['title' => 'Build kanban drag & drop', 'priority' => 'CRITICAL', 'color' => 'red',    'user' => 'MJ'],
                        ['title' => 'Setup Reverb WebSockets',  'priority' => 'NORMAL',   'color' => 'yellow', 'user' => 'TK'],
                        ['title' => 'Analytics charts v2',      'priority' => 'NORMAL',   'color' => 'yellow', 'user' => 'SL'],
                    ],
                ],
                [
                    'id'             => 'done',
                    'label'          => 'SHIPPED',
                    'header_classes' => 'text-neon-green border-neon-green',
                    'task_accent'    => '',
                    'shipped'        => true,
                    'tasks' => [
                        ['title' => 'DB schema + migrations',  'user' => 'MJ'],
                        ['title' => 'User auth & roles',       'user' => 'TK'],
                        ['title' => 'Jetstream + Teams setup', 'user' => 'SL'],
                    ],
                ],
            ],

            'sprint' => [
                ['label' => 'Completed',   'value' => '18', 'color' => 'text-neon-green'],
                ['label' => 'In progress', 'value' => '3',  'color' => 'text-yellow-500'],
                ['label' => 'Overdue',     'value' => '2',  'color' => 'text-red-400'],
                ['label' => 'Backlog',     'value' => '4',  'color' => 'text-white'],
            ],

            'teamMembers' => [
                ['initials' => 'MJ', 'name' => 'miyulas',  'status' => 'active'],
                ['initials' => 'TK', 'name' => 'tomk',     'status' => 'idle'],
                ['initials' => 'SL', 'name' => 'saral',    'status' => 'active'],
                ['initials' => 'RD', 'name' => 'rodrigo',  'status' => 'away'],
            ],

            'activity' => [
                ['icon' => '✓', 'color' => 'text-neon-green', 'text' => 'miyulas shipped #38'],
                ['icon' => '→', 'color' => 'text-yellow-500', 'text' => 'tomk started #41'],
                ['icon' => '+', 'color' => 'text-gray-500',   'text' => 'saral added #42'],
            ],
        ];
    }
}
