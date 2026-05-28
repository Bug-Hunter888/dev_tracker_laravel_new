<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('sprints.index', $project) }}" class="text-gray-500 hover:text-white text-sm transition-colors">&lt; SPRINTS</a>
                <span class="text-gray-700">/</span>
                <div class="flex items-center gap-3">
                    <h1 class="text-xl font-bold text-white uppercase">{{ $sprint->name }}</h1>
                    @if($sprint->isPlanning())
                        <span class="text-xs border border-yellow-600 text-yellow-600 px-2 py-0.5">PLANNING</span>
                    @elseif($sprint->isActive())
                        <span class="text-xs border border-neon-green text-neon-green px-2 py-0.5">ACTIVE</span>
                    @else
                        <span class="text-xs border border-gray-700 text-gray-600 px-2 py-0.5">COMPLETED</span>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if($canManage)
                    @if($sprint->isActive())
                    <form action="{{ route('sprints.complete', [$project, $sprint]) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit"
                                onclick="return confirm('Complete this sprint? Unfinished tasks return to backlog.')"
                                class="text-xs border border-gray-700 text-gray-400 px-3 py-1.5 hover:border-red-500 hover:text-red-500 transition-colors font-mono">
                            COMPLETE SPRINT
                        </button>
                    </form>
                    @endif
                    @if($sprint->isPlanning() && !$project->sprints()->where('status', 'active')->exists())
                    <form action="{{ route('sprints.start', [$project, $sprint]) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit"
                                onclick="return confirm('Start this sprint? Backlog items will be moved to TO_DO.')"
                                class="text-xs border-2 border-neon-green text-neon-green px-4 py-1.5 hover:bg-neon-green hover:text-black transition-colors font-bold font-mono">
                            START SPRINT &rarr;
                        </button>
                    </form>
                    @endif
                @endif
            </div>
        </div>

        <div class="flex items-center gap-6 mt-2 ml-0 text-xs text-gray-600">
            <span>{{ $sprint->start_date->format('d M Y') }} — {{ $sprint->end_date->format('d M Y') }}</span>
            @if($sprint->goal)
                <span class="italic text-gray-500">Goal: {{ $sprint->goal }}</span>
            @endif
        </div>
    </x-slot>

    @php
        $total = $sprintTasks->count();
        $done  = $sprintTasks->where('status', 'done')->count();
        $pct   = $total > 0 ? round($done / $total * 100) : 0;
    @endphp

    {{-- Progress bar --}}
    @if($total > 0)
    <div class="border-b-2 border-gray-800 bg-dark-gray px-8 py-3">
        <div class="flex items-center gap-6 text-xs font-mono mb-2">
            <span><span class="text-gray-600">TOTAL:</span> <span class="text-white font-bold">{{ $total }}</span></span>
            <span><span class="text-gray-600">DONE:</span> <span class="text-neon-green font-bold">{{ $done }}</span></span>
            <span><span class="text-gray-600">REMAINING:</span> <span class="text-yellow-500 font-bold">{{ $total - $done }}</span></span>
            <span class="text-neon-green font-bold">{{ $pct }}%</span>
        </div>
        <div class="h-1.5 bg-gray-900 border border-gray-800 max-w-md">
            <div class="h-full bg-neon-green transition-all" style="width: {{ $pct }}%"></div>
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- PLANNING VIEW: two-column backlog picker                      --}}
    {{-- ============================================================ --}}
    @if($sprint->isPlanning())
    <div class="p-6 bg-pitch-black min-h-screen">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 max-w-6xl">

            {{-- Sprint items (left) --}}
            <div>
                <p class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-3">
                    // IN_THIS_SPRINT <span class="text-gray-700">({{ $sprintTasks->count() }})</span>
                </p>
                @if($sprintTasks->isEmpty())
                    <div class="border-2 border-dashed border-gray-800 p-8 text-center">
                        <p class="text-gray-700 text-xs">No tasks yet — add from the backlog &rarr;</p>
                    </div>
                @else
                <div class="space-y-2">
                    @foreach($sprintTasks->sortBy(fn($t) => match($t->priority) { 'critical'=>0,'normal'=>1,default=>2 }) as $task)
                    @php
                        $pStyle = match($task->priority) {
                            'critical' => ['text'=>'text-red-400','border'=>'border-red-400'],
                            'low'      => ['text'=>'text-gray-500','border'=>'border-gray-600'],
                            default    => ['text'=>'text-neon-green','border'=>'border-neon-green'],
                        };
                    @endphp
                    <div class="border border-gray-800 bg-black px-4 py-3 flex items-center gap-3">
                        <span class="text-xs font-bold px-2 py-0.5 border shrink-0 w-20 text-center {{ $pStyle['text'] }} {{ $pStyle['border'] }}">
                            {{ strtoupper($task->priority) }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-white font-bold truncate">{{ $task->title }}</p>
                            <div class="flex items-center gap-3 mt-0.5">
                                @if($task->assignee)
                                    <p class="text-xs text-gray-600">{{ $task->assignee->name }}</p>
                                @endif
                                @if($task->epic)
                                    <span class="text-xs font-bold" style="color:{{ $task->epic->color }}">◆ {{ $task->epic->name }}</span>
                                @endif
                            </div>
                        </div>
                        @if($task->story_points)
                        <span class="text-xs font-mono text-gray-600 border border-gray-800 px-2 py-0.5 shrink-0">{{ $task->story_points }}pt</span>
                        @endif
                        @if($canManage)
                        <form action="{{ route('sprints.tasks.remove', [$project, $sprint, $task]) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-xs border border-gray-800 text-gray-700 px-2 py-1 hover:border-red-500 hover:text-red-500 transition-colors font-mono shrink-0">
                                &larr; REMOVE
                            </button>
                        </form>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Backlog items (right) --}}
            <div>
                <p class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-3">
                    // BACKLOG <span class="text-gray-700">({{ $backlogTasks->count() }})</span>
                </p>
                @if($backlogTasks->isEmpty())
                    <div class="border-2 border-dashed border-gray-800 p-8 text-center">
                        <p class="text-gray-700 text-xs">No backlog items. <a href="{{ route('projects.backlog', $project) }}" class="text-gray-500 hover:text-white underline">Add to backlog →</a></p>
                    </div>
                @else
                <div class="space-y-2">
                    @foreach($backlogTasks as $task)
                    @php
                        $pStyle = match($task->priority) {
                            'critical' => ['text'=>'text-red-400','border'=>'border-red-400'],
                            'low'      => ['text'=>'text-gray-500','border'=>'border-gray-600'],
                            default    => ['text'=>'text-neon-green','border'=>'border-neon-green'],
                        };
                    @endphp
                    <div class="border border-gray-800 bg-black px-4 py-3 flex items-center gap-3 hover:border-gray-600 transition-colors">
                        <span class="text-xs font-bold px-2 py-0.5 border shrink-0 w-20 text-center {{ $pStyle['text'] }} {{ $pStyle['border'] }}">
                            {{ strtoupper($task->priority) }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-white font-bold truncate">{{ $task->title }}</p>
                            <div class="flex items-center gap-3 mt-0.5">
                                @if($task->assignee)
                                    <p class="text-xs text-gray-600">{{ $task->assignee->name }}</p>
                                @endif
                                @if($task->epic)
                                    <span class="text-xs font-bold" style="color:{{ $task->epic->color }}">◆ {{ $task->epic->name }}</span>
                                @endif
                            </div>
                        </div>
                        @if($task->story_points)
                        <span class="text-xs font-mono text-gray-600 border border-gray-800 px-2 py-0.5 shrink-0">{{ $task->story_points }}pt</span>
                        @endif
                        @if($task->labels->count())
                        <div class="hidden md:flex gap-1 shrink-0">
                            @foreach($task->labels->take(2) as $label)
                                <span class="text-xs px-1.5 py-0.5 border font-bold"
                                      style="color:{{ $label->color }};border-color:{{ $label->color }}">
                                    {{ $label->name }}
                                </span>
                            @endforeach
                        </div>
                        @endif
                        @if($canManage)
                        <form action="{{ route('sprints.tasks.add', [$project, $sprint, $task]) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="text-xs border border-neon-green text-neon-green px-2 py-1 hover:bg-neon-green hover:text-black transition-colors font-bold font-mono shrink-0">
                                ADD &rarr;
                            </button>
                        </form>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- BOARD VIEW: kanban for active / completed sprints            --}}
    {{-- ============================================================ --}}
    @else

    {{-- Burndown Chart --}}
    @if($chartData)
    <div class="border-b-2 border-gray-800 bg-dark-gray px-8 py-6">
        <p class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-1">// BURNDOWN_CHART</p>
        <p class="text-xs text-gray-700 mb-4">ideal (dashed) vs actual — {{ $chartData['unit'] }} remaining per day</p>
        <div style="max-width:680px; position:relative; height:200px;">
            <canvas id="burndown-chart"></canvas>
        </div>
        <div class="flex gap-6 mt-3 text-xs font-mono text-gray-600">
            <span><span class="inline-block w-6 border-t border-dashed border-gray-500 align-middle mr-1"></span>IDEAL</span>
            <span><span class="inline-block w-6 border-t-2 border-neon-green align-middle mr-1"></span>ACTUAL</span>
            <span class="text-gray-700">TOTAL: <span class="text-white">{{ $chartData['totalPoints'] }} {{ $chartData['unit'] }}</span></span>
        </div>
    </div>
    @endif

    <div class="p-6 overflow-x-auto min-h-screen bg-pitch-black">
        <div class="flex gap-6" style="min-width: 960px;">

            @foreach([
                ['key'=>'todo',  'label'=>'TO_DO',      'border'=>'border-white',      'text'=>'text-white'],
                ['key'=>'doing', 'label'=>'IN_PROGRESS', 'border'=>'border-yellow-500', 'text'=>'text-yellow-500'],
                ['key'=>'done',  'label'=>'SHIPPED',     'border'=>'border-neon-green', 'text'=>'text-neon-green'],
            ] as $col)

            <div class="flex-1 min-w-72">
                <div class="flex justify-between items-center mb-4 border-b-4 {{ $col['border'] }} pb-3">
                    <h3 class="font-bold {{ $col['text'] }} text-sm tracking-widest uppercase">
                        # {{ $col['label'] }}
                        <span class="text-gray-500 ml-1">({{ $grouped[$col['key']]->count() }})</span>
                    </h3>
                </div>

                <div class="space-y-3">
                    @forelse($grouped[$col['key']] as $task)
                    @php
                        $pColor = match($task->priority) {
                            'critical' => ['text'=>'text-red-400',    'border'=>'border-red-400'],
                            'low'      => ['text'=>'text-gray-500',   'border'=>'border-gray-600'],
                            default    => ['text'=>'text-neon-green', 'border'=>'border-neon-green'],
                        };
                    @endphp
                    <div class="bg-black border-2 p-4 transition-colors
                        {{ $task->isOverdue() ? 'border-red-500' : 'border-gray-700 hover:border-gray-400' }}">

                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold px-2 py-0.5 border {{ $pColor['text'] }} {{ $pColor['border'] }}">
                                {{ strtoupper($task->priority) }}
                            </span>
                            @if($task->story_points)
                            <span class="text-xs font-mono text-gray-600">{{ $task->story_points }}pt</span>
                            @endif
                        </div>

                        <a href="{{ route('tasks.show', $task) }}"
                           class="block font-bold text-sm mb-3 leading-snug hover:text-neon-green transition-colors
                            {{ $col['key'] === 'done' ? 'line-through text-gray-500 hover:text-gray-400' : 'text-white' }}">
                            {{ $task->title }}
                        </a>

                        @if($task->assignee)
                        <div class="text-xs text-gray-500 mb-3">
                            ASSIGNED: <span class="bg-gray-800 text-white px-1.5 py-0.5 font-bold">
                                {{ strtoupper(substr($task->assignee->name, 0, 8)) }}
                            </span>
                        </div>
                        @endif

                        @if($task->due_date)
                        <div class="text-xs {{ $task->isOverdue() ? 'text-red-400' : 'text-gray-500' }} mb-3">
                            DUE: {{ $task->due_date->format('d M Y') }}
                            @if($task->isOverdue()) <span class="text-red-500 font-bold ml-1">OVERDUE</span> @endif
                        </div>
                        @endif

                        @if($task->labels->count())
                        <div class="flex flex-wrap gap-1 mb-3">
                            @foreach($task->labels as $label)
                                <span class="text-xs px-2 py-0.5 border font-bold"
                                      style="color:{{ $label->color }};border-color:{{ $label->color }}">
                                    {{ $label->name }}
                                </span>
                            @endforeach
                        </div>
                        @endif

                        @if($task->epic)
                        <div class="mb-3">
                            <a href="{{ route('epics.show', [$project, $task->epic]) }}"
                               class="text-xs font-bold hover:opacity-80 transition-opacity"
                               style="color:{{ $task->epic->color }}">
                                ◆ {{ $task->epic->name }}
                            </a>
                        </div>
                        @endif

                        {{-- Status move actions --}}
                        <div class="flex gap-2 border-t border-gray-800 pt-2 mt-2">
                            @if($sprint->isActive())
                                @if($col['key'] === 'todo')
                                <form action="{{ route('tasks.status', $task) }}" method="POST" class="flex-1">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="doing">
                                    <button type="submit" class="w-full text-xs border border-gray-700 py-1.5 hover:bg-yellow-500 hover:text-black hover:border-yellow-500 transition-all font-bold">
                                        START &gt;
                                    </button>
                                </form>
                                @elseif($col['key'] === 'doing')
                                <form action="{{ route('tasks.status', $task) }}" method="POST" class="flex-1">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="todo">
                                    <button type="submit" class="w-full text-xs border border-gray-700 py-1.5 hover:bg-gray-800 transition-all">
                                        &lt; BACK
                                    </button>
                                </form>
                                <form action="{{ route('tasks.status', $task) }}" method="POST" class="flex-1">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="done">
                                    <button type="submit" class="w-full text-xs border border-neon-green py-1.5 text-neon-green hover:bg-neon-green hover:text-black transition-all font-bold">
                                        SHIP &gt;
                                    </button>
                                </form>
                                @elseif($col['key'] === 'done')
                                <form action="{{ route('tasks.status', $task) }}" method="POST" class="flex-1">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="doing">
                                    <button type="submit" class="w-full text-xs border border-gray-700 py-1.5 hover:bg-gray-800 transition-all">
                                        REOPEN
                                    </button>
                                </form>
                                @endif
                                {{-- Remove from sprint — owners/admins only --}}
                                @if($canManage)
                                <form action="{{ route('sprints.tasks.remove', [$project, $sprint, $task]) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Remove from sprint? Task will go back to backlog.')"
                                            class="text-xs border border-gray-800 text-gray-700 px-2 py-1.5 hover:border-red-500 hover:text-red-500 transition-colors font-mono">
                                        ↩
                                    </button>
                                </form>
                                @endif
                            @endif
                        </div>
                    </div>
                    @empty
                        <div class="border-2 border-dashed border-gray-800 p-8 text-center">
                            <p class="text-gray-700 text-xs">NO_TASKS_HERE</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @endforeach
        </div>
    </div>
    @endif

@push('scripts')
@if(isset($chartData) && $chartData)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
(function () {
    const ctx = document.getElementById('burndown-chart');
    if (!ctx) return;

    const labels   = @json($chartData['labels']);
    const ideal    = @json($chartData['ideal']);
    const actual   = @json($chartData['actual']);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Ideal',
                    data: ideal,
                    borderColor: '#4B5563',
                    borderDash: [6, 3],
                    borderWidth: 1.5,
                    pointRadius: 0,
                    tension: 0,
                    fill: false,
                },
                {
                    label: 'Actual',
                    data: actual,
                    borderColor: '#39FF14',
                    backgroundColor: 'rgba(57,255,20,0.06)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#39FF14',
                    tension: 0.15,
                    fill: true,
                    spanGaps: false,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#000',
                    borderColor: '#374151',
                    borderWidth: 1,
                    titleColor: '#9CA3AF',
                    bodyColor: '#fff',
                    titleFont: { family: 'monospace', size: 11 },
                    bodyFont: { family: 'monospace', size: 11 },
                },
            },
            scales: {
                x: {
                    ticks: { color: '#6B7280', font: { family: 'monospace', size: 10 }, maxRotation: 45 },
                    grid: { color: '#111827' },
                },
                y: {
                    beginAtZero: true,
                    ticks: { color: '#6B7280', font: { family: 'monospace', size: 10 }, stepSize: 1 },
                    grid: { color: '#111827' },
                },
            },
        },
    });
})();
</script>
@endif
@endpush

</x-app-layout>
