<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-white text-sm transition-colors">
                &lt; BOARDS
            </a>
            <span class="text-gray-700">/</span>
            <h1 class="text-xl font-bold text-white uppercase">ANALYTICS_</h1>
        </div>
        <p class="text-xs text-gray-500 mt-1">{{ auth()->user()->currentTeam->name }}</p>
    </x-slot>

    {{-- Stats Bar --}}
    <div class="border-b-2 border-gray-800 bg-dark-gray px-8 py-3 flex flex-wrap gap-6 text-xs font-mono">
        <div><span class="text-gray-600">TOTAL_TASKS:</span> <span class="text-white font-bold">{{ $totalTasks }}</span></div>
        <div><span class="text-gray-600">SHIPPED:</span> <span class="text-neon-green font-bold">{{ $doneTasks }}</span></div>
        <div><span class="text-gray-600">IN_PROGRESS:</span> <span class="text-yellow-500 font-bold">{{ $doingTasks }}</span></div>
        <div><span class="text-gray-600">OVERDUE:</span>
            <span class="font-bold {{ $overdueTasks > 0 ? 'text-red-500' : 'text-gray-500' }}">{{ $overdueTasks }}</span>
        </div>
        @if($totalTasks > 0)
            <div><span class="text-gray-600">COMPLETION:</span>
                <span class="text-neon-green font-bold">{{ round($doneTasks / $totalTasks * 100) }}%</span>
            </div>
        @endif
    </div>

    <div class="p-6 bg-pitch-black min-h-screen space-y-8">

        {{-- Activity Heatmap --}}
        <div class="border-2 border-gray-800 p-6">
            <h2 class="text-xs font-bold text-gray-500 tracking-widest mb-4">// ACTIVITY_HEATMAP &mdash; LAST_30_DAYS</h2>
            @php $maxCount = $heatmap->max('count') ?: 1; @endphp
            <div class="flex gap-1 flex-wrap">
                @foreach($heatmap as $day)
                    @php
                        $intensity = $day['count'] > 0 ? max(0.12, $day['count'] / $maxCount) : 0;
                        $bg = $day['count'] > 0 ? "rgba(57,255,20,{$intensity})" : '#0d0d0d';
                        $textColor = $intensity > 0.5 ? '#000000' : '#39FF14';
                    @endphp
                    <div class="w-9 h-9 border border-gray-900 flex flex-col items-center justify-center cursor-default relative group"
                         style="background: {{ $bg }}">
                        @if($day['count'] > 0)
                            <span class="text-xs font-black leading-none" style="color: {{ $textColor }}; font-size: 10px;">
                                {{ $day['count'] }}
                            </span>
                        @endif
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 hidden group-hover:block z-10
                                    bg-black border border-gray-700 px-2 py-1 text-xs text-gray-300 whitespace-nowrap">
                            {{ $day['label'] }}: {{ $day['count'] }} actions
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex items-center gap-2 mt-3 text-xs text-gray-700">
                <span>LESS</span>
                @foreach([0, 0.2, 0.45, 0.7, 1.0] as $lvl)
                    <div class="w-4 h-4 border border-gray-900"
                         style="background: {{ $lvl === 0 ? '#0d0d0d' : 'rgba(57,255,20,'.$lvl.')' }}"></div>
                @endforeach
                <span>MORE</span>
            </div>
        </div>

        {{-- Shipped per User + Project Completion --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Shipped by Member --}}
            <div class="border-2 border-gray-800 p-6">
                <h2 class="text-xs font-bold text-gray-500 tracking-widest mb-4">// SHIPPED_BY_MEMBER</h2>
                @if($shippedByUser->isEmpty())
                    <p class="text-xs text-gray-700 py-8 text-center">No shipped tasks assigned to members yet.</p>
                @else
                    <canvas id="shippedChart"></canvas>
                @endif
            </div>

            {{-- Project Completion --}}
            <div class="border-2 border-gray-800 p-6">
                <h2 class="text-xs font-bold text-gray-500 tracking-widest mb-4">// PROJECT_COMPLETION</h2>
                @forelse($projects as $proj)
                    @php $pct = $proj->tasks_count > 0 ? round($proj->done_count / $proj->tasks_count * 100) : 0; @endphp
                    <div class="mb-5">
                        <div class="flex justify-between items-center mb-1.5">
                            <a href="{{ route('projects.show', $proj) }}"
                               class="text-xs font-bold text-white hover:text-neon-green transition-colors flex items-center gap-2">
                                <span class="inline-block w-2 h-2" style="background: {{ $proj->color }}"></span>
                                {{ strtoupper($proj->name) }}
                            </a>
                            <span class="text-xs font-bold {{ $pct === 100 ? 'text-neon-green' : 'text-gray-400' }}">
                                {{ $pct }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-900 h-2 border border-gray-800">
                            <div class="h-full"
                                 style="width: {{ $pct }}%; background: {{ $pct === 100 ? '#39FF14' : '#eab308' }}"></div>
                        </div>
                        <div class="text-xs text-gray-700 mt-1 flex gap-3">
                            <span>{{ $proj->done_count }}/{{ $proj->tasks_count }} shipped</span>
                            @if($proj->doing_count) <span class="text-yellow-600">{{ $proj->doing_count }} doing</span> @endif
                            @if($proj->todo_count)  <span>{{ $proj->todo_count }} todo</span> @endif
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-700 py-8 text-center">No projects yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Pie charts row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Task Status Distribution --}}
            <div class="border-2 border-gray-800 p-6">
                <h2 class="text-xs font-bold text-gray-500 tracking-widest mb-6">// TASK_STATUS_DISTRIBUTION</h2>
                @if($totalTasks > 0)
                    <div class="flex items-center gap-8">
                        <div class="relative w-40 h-40 shrink-0">
                            <canvas id="statusPieChart"></canvas>
                        </div>
                        <ul class="space-y-3 text-xs font-mono flex-1">
                            <li class="flex items-center justify-between">
                                <span class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 inline-block" style="background:#374151;"></span>
                                    <span class="text-gray-400">TO_DO</span>
                                </span>
                                <span class="font-bold text-gray-300">{{ $todoTasks }}</span>
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 inline-block" style="background:#eab308;"></span>
                                    <span class="text-yellow-500">IN_PROGRESS</span>
                                </span>
                                <span class="font-bold text-yellow-500">{{ $doingTasks }}</span>
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 inline-block" style="background:#39FF14;"></span>
                                    <span class="text-neon-green">SHIPPED</span>
                                </span>
                                <span class="font-bold text-neon-green">{{ $doneTasks }}</span>
                            </li>
                            <li class="flex items-center justify-between border-t border-gray-800 pt-3 mt-1">
                                <span class="text-gray-600">TOTAL</span>
                                <span class="font-bold text-white">{{ $totalTasks }}</span>
                            </li>
                        </ul>
                    </div>
                @else
                    <p class="text-xs text-gray-700 py-8 text-center">No tasks yet.</p>
                @endif
            </div>

            {{-- Priority Mix --}}
            <div class="border-2 border-gray-800 p-6">
                <h2 class="text-xs font-bold text-gray-500 tracking-widest mb-6">// PRIORITY_MIX</h2>
                @if($totalTasks > 0)
                    <div class="flex items-center gap-8">
                        <div class="relative w-40 h-40 shrink-0">
                            <canvas id="priorityPieChart"></canvas>
                        </div>
                        <ul class="space-y-3 text-xs font-mono flex-1">
                            <li class="flex items-center justify-between">
                                <span class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 inline-block" style="background:#ef4444;"></span>
                                    <span class="text-red-400">CRITICAL</span>
                                </span>
                                <span class="font-bold text-red-400">{{ $priorityBreakdown['critical'] ?? 0 }}</span>
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 inline-block" style="background:#39FF14;"></span>
                                    <span class="text-neon-green">NORMAL</span>
                                </span>
                                <span class="font-bold text-neon-green">{{ $priorityBreakdown['normal'] ?? 0 }}</span>
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 inline-block" style="background:#6b7280;"></span>
                                    <span class="text-gray-500">LOW</span>
                                </span>
                                <span class="font-bold text-gray-400">{{ $priorityBreakdown['low'] ?? 0 }}</span>
                            </li>
                            <li class="flex items-center justify-between border-t border-gray-800 pt-3 mt-1">
                                <span class="text-gray-600">TOTAL</span>
                                <span class="font-bold text-white">{{ $totalTasks }}</span>
                            </li>
                        </ul>
                    </div>
                @else
                    <p class="text-xs text-gray-700 py-8 text-center">No tasks yet.</p>
                @endif
            </div>

        </div>

        {{-- Per-project task breakdown table --}}
        @if($projects->isNotEmpty())
        <div class="border-2 border-gray-800 p-6">
            <h2 class="text-xs font-bold text-gray-500 tracking-widest mb-4">// PROJECT_BREAKDOWN</h2>
            <table class="w-full text-xs font-mono">
                <thead>
                    <tr class="border-b border-gray-800 text-gray-600">
                        <th class="text-left pb-2 pr-4">PROJECT</th>
                        <th class="text-right pb-2 px-4">TODO</th>
                        <th class="text-right pb-2 px-4">DOING</th>
                        <th class="text-right pb-2 px-4">SHIPPED</th>
                        <th class="text-right pb-2 px-4">TOTAL</th>
                        <th class="text-right pb-2 pl-4">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $proj)
                    <tr class="border-b border-gray-900 hover:bg-gray-900 transition-colors">
                        <td class="py-2 pr-4">
                            <a href="{{ route('projects.show', $proj) }}"
                               class="flex items-center gap-2 text-white hover:text-neon-green transition-colors">
                                <span class="w-2 h-2 inline-block" style="background: {{ $proj->color }}"></span>
                                {{ strtoupper($proj->name) }}
                            </a>
                        </td>
                        <td class="text-right py-2 px-4 text-gray-400">{{ $proj->todo_count }}</td>
                        <td class="text-right py-2 px-4 text-yellow-600">{{ $proj->doing_count }}</td>
                        <td class="text-right py-2 px-4 text-neon-green">{{ $proj->done_count }}</td>
                        <td class="text-right py-2 px-4 text-gray-300">{{ $proj->tasks_count }}</td>
                        <td class="text-right py-2 pl-4">
                            <span class="border px-2 py-0.5 text-xs
                                {{ $proj->status === 'active' ? 'border-neon-green text-neon-green' : 'border-gray-700 text-gray-600' }}">
                                {{ strtoupper($proj->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    Chart.defaults.color = '#6b7280';
    Chart.defaults.font.family = "'Fira Code', monospace";
    Chart.defaults.font.size = 11;

    const pieDefaults = {
        responsive: true,
        maintainAspectRatio: true,
        cutout: '68%',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#000',
                borderColor: '#333',
                borderWidth: 1,
                titleColor: '#39FF14',
                bodyColor: '#fff',
                padding: 10,
                callbacks: {
                    label: ctx => ` ${ctx.parsed} (${Math.round(ctx.parsed / ctx.dataset.data.reduce((a,b)=>a+b,0)*100)}%)`,
                },
            },
        },
    };

    @if($totalTasks > 0)
    new Chart(document.getElementById('statusPieChart'), {
        type: 'doughnut',
        data: {
            labels: ['TO_DO', 'IN_PROGRESS', 'SHIPPED'],
            datasets: [{
                data: [{{ $todoTasks }}, {{ $doingTasks }}, {{ $doneTasks }}],
                backgroundColor: ['#374151', '#eab308', '#39FF14'],
                borderColor: '#0a0a0a',
                borderWidth: 3,
                hoverBorderColor: '#fff',
                hoverBorderWidth: 1,
            }]
        },
        options: pieDefaults,
    });

    new Chart(document.getElementById('priorityPieChart'), {
        type: 'doughnut',
        data: {
            labels: ['CRITICAL', 'NORMAL', 'LOW'],
            datasets: [{
                data: [
                    {{ $priorityBreakdown['critical'] ?? 0 }},
                    {{ $priorityBreakdown['normal'] ?? 0 }},
                    {{ $priorityBreakdown['low'] ?? 0 }},
                ],
                backgroundColor: ['#ef4444', '#39FF14', '#6b7280'],
                borderColor: '#0a0a0a',
                borderWidth: 3,
                hoverBorderColor: '#fff',
                hoverBorderWidth: 1,
            }]
        },
        options: pieDefaults,
    });
    @endif

    @if($shippedByUser->isNotEmpty())
    const shippedData = @json($shippedByUser);
    new Chart(document.getElementById('shippedChart'), {
        type: 'bar',
        data: {
            labels: shippedData.map(d => d.name.toUpperCase()),
            datasets: [{
                label: 'TASKS SHIPPED',
                data: shippedData.map(d => d.count),
                backgroundColor: '#39FF14',
                borderColor: '#39FF14',
                borderWidth: 0,
                borderRadius: 0,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#000',
                    borderColor: '#39FF14',
                    borderWidth: 1,
                    titleColor: '#39FF14',
                    bodyColor: '#fff',
                    padding: 10,
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.x} task${ctx.parsed.x !== 1 ? 's' : ''} shipped`,
                    },
                },
            },
            scales: {
                x: {
                    grid: { color: '#1a1a1a' },
                    ticks: { color: '#6b7280', precision: 0 },
                    border: { color: '#374151' },
                },
                y: {
                    grid: { color: '#1a1a1a' },
                    ticks: { color: '#9ca3af' },
                    border: { color: '#374151' },
                },
            },
        }
    });
    @endif
    </script>
    @endpush
</x-app-layout>
