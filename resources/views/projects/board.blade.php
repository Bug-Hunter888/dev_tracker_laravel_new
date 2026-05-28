<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-white text-sm transition-colors">
                    &lt; BOARDS
                </a>
                <span class="text-gray-700">/</span>
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 border-2 border-white" style="background: {{ $project->color }}"></div>
                    <h1 class="text-xl font-bold text-white uppercase">{{ $project->name }}</h1>
                    <span class="text-xs border px-2 py-0.5
                        {{ $project->status === 'active' ? 'border-neon-green text-neon-green' : 'border-gray-600 text-gray-600' }}">
                        {{ strtoupper($project->status) }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Team member avatars --}}
                <div class="flex -space-x-1">
                    @foreach($teamMembers->take(5) as $member)
                        <div class="w-8 h-8 bg-gray-800 border-2 border-black flex items-center justify-center text-xs font-bold text-white"
                             title="{{ $member->name }}">
                            {{ strtoupper(substr($member->name, 0, 2)) }}
                        </div>
                    @endforeach
                    @if($teamMembers->count() > 5)
                        <div class="w-8 h-8 bg-gray-700 border-2 border-black flex items-center justify-center text-xs text-gray-400">
                            +{{ $teamMembers->count() - 5 }}
                        </div>
                    @endif
                </div>

                <a href="{{ route('sprints.index', $project) }}"
                   class="text-xs border-2 border-gray-700 px-3 py-1.5 text-gray-400 hover:border-blue-400 hover:text-blue-400 transition-colors font-bold">
                    SPRINTS
                </a>

                <a href="{{ route('projects.backlog', $project) }}"
                   class="text-xs border-2 border-gray-700 px-3 py-1.5 text-gray-400 hover:border-purple-400 hover:text-purple-400 transition-colors font-bold relative">
                    BACKLOG
                    @if(isset($backlogCount) && $backlogCount > 0)
                        <span class="ml-1 text-purple-400">({{ $backlogCount }})</span>
                    @endif
                </a>

                <a href="{{ route('projects.timeline', $project) }}"
                   class="text-xs border-2 border-gray-700 px-3 py-1.5 text-gray-400 hover:border-yellow-500 hover:text-yellow-500 transition-colors font-bold">
                    TIMELINE
                </a>

                <a href="{{ route('epics.index', $project) }}"
                   class="text-xs border-2 border-gray-700 px-3 py-1.5 text-gray-400 hover:border-purple-500 hover:text-purple-500 transition-colors font-bold">
                    EPICS
                </a>

                @can('manageAutomations', $project)
                <a href="{{ route('automations.index', $project) }}"
                   class="text-xs border-2 border-gray-700 px-3 py-1.5 text-gray-400 hover:border-neon-green hover:text-neon-green transition-colors font-bold">
                    AUTOMATIONS
                </a>
                @endcan

                @can('update', $project)
                <a href="{{ route('projects.edit', $project) }}"
                   class="text-xs border-2 border-gray-700 px-3 py-1.5 text-gray-400 hover:border-white hover:text-white transition-colors font-bold">
                    SETTINGS
                </a>
                @endcan
            </div>
        </div>

        @if($project->description)
            <p class="text-xs text-gray-500 mt-2 ml-12">{{ $project->description }}</p>
        @endif
    </x-slot>

    {{-- Stats Bar --}}
    <div class="border-b-2 border-gray-800 bg-dark-gray px-8 py-3 flex gap-8 text-xs font-mono">
        @php
            $total = $grouped['todo']->count() + $grouped['doing']->count() + $grouped['done']->count();
            $pct   = $total > 0 ? round($grouped['done']->count() / $total * 100) : 0;
        @endphp
        <div><span class="text-gray-600">TOTAL:</span> <span class="text-white font-bold">{{ $total }}</span></div>
        <div><span class="text-gray-600">TODO:</span> <span class="text-white font-bold">{{ $grouped['todo']->count() }}</span></div>
        <div><span class="text-gray-600">DOING:</span> <span class="text-yellow-500 font-bold">{{ $grouped['doing']->count() }}</span></div>
        <div><span class="text-gray-600">SHIPPED:</span> <span class="text-neon-green font-bold">{{ $grouped['done']->count() }}</span></div>
        <div class="hidden md:block"><span class="text-gray-600">COMPLETION:</span> <span class="text-neon-green font-bold">{{ $pct }}%</span></div>
        @if(isset($backlogCount) && $backlogCount > 0)
        <div class="hidden md:block">
            <a href="{{ route('projects.backlog', $project) }}" class="hover:text-purple-400 transition-colors">
                <span class="text-gray-600">BACKLOG:</span> <span class="text-purple-400 font-bold">{{ $backlogCount }}</span>
            </a>
        </div>
        @endif
        @if($project->end_date)
            <div class="hidden md:block">
                <span class="text-gray-600">DEADLINE:</span>
                <span class="{{ $project->end_date->isPast() ? 'text-red-500' : 'text-gray-300' }} font-bold">
                    {{ $project->end_date->format('d M Y') }}
                </span>
            </div>
        @endif
    </div>

    {{-- Label Filter Bar + Kanban share the same Alpine scope so x-show="active" works on cards --}}
    <div x-data="labelFilter()">

    @if($teamLabels->isNotEmpty())
    <div class="border-b-2 border-gray-800 bg-black px-8 py-3 flex items-center gap-3 flex-wrap">
        <span class="text-xs text-gray-700 font-mono uppercase tracking-widest shrink-0">FILTER:</span>
        <button @click="toggle(null)"
                :class="active === null ? 'border-white text-white' : 'border-gray-800 text-gray-600 hover:border-gray-600'"
                class="text-xs font-mono px-2 py-0.5 border transition-colors">
            ALL
        </button>
        @foreach($teamLabels as $label)
        <button @click="toggle({{ $label->id }})"
                :class="active === {{ $label->id }} ? 'opacity-100' : 'opacity-30 hover:opacity-60'"
                class="text-xs font-bold font-mono px-2 py-0.5 border-2 transition-opacity"
                style="color:{{ $label->color }};border-color:{{ $label->color }}">
            {{ $label->name }}
        </button>
        @endforeach
        <span x-show="active !== null" x-cloak class="text-xs text-gray-700 font-mono ml-1">
            — showing tasks with this label only
        </span>
    </div>
    @endif

    {{-- Kanban Board --}}
    <div class="p-6 overflow-x-auto min-h-screen bg-pitch-black">
        <div class="flex gap-6" style="min-width: 960px;">

            @foreach([
                ['key' => 'todo',  'label' => 'TO_DO',       'border' => 'border-white',       'text' => 'text-white',       'accent' => '#ffffff'],
                ['key' => 'doing', 'label' => 'IN_PROGRESS',  'border' => 'border-yellow-500',  'text' => 'text-yellow-500',  'accent' => '#eab308'],
                ['key' => 'done',  'label' => 'SHIPPED',      'border' => 'border-neon-green',  'text' => 'text-neon-green',  'accent' => '#39FF14'],
            ] as $col)

            <div class="flex-1 min-w-72" x-data="{ addOpen: false }">

                {{-- Column Header --}}
                <div class="flex justify-between items-center mb-4 border-b-4 {{ $col['border'] }} pb-3">
                    <h3 class="font-bold {{ $col['text'] }} text-sm tracking-widest uppercase">
                        # {{ $col['label'] }}
                        <span class="text-gray-500 ml-1">({{ $grouped[$col['key']]->count() }})</span>
                    </h3>
                    <button @click="addOpen = !addOpen"
                            class="text-xs text-gray-600 hover:text-neon-green transition-colors font-bold border border-gray-700 px-2 py-0.5 hover:border-neon-green">
                        + ADD
                    </button>
                </div>

                {{-- Add Task Form --}}
                <div x-show="addOpen" x-cloak x-transition
                     class="mb-4 border-2 border-neon-green bg-dark-gray p-4">
                    <form action="{{ route('tasks.store', $project) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="{{ $col['key'] }}">

                        <input type="text" name="title" placeholder="Task title..."
                               class="input-brutal text-sm mb-3" required>

                        <textarea name="description" rows="2" placeholder="Description (optional)..."
                                  class="input-brutal text-xs mb-3 resize-none"></textarea>

                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <div>
                                <label class="text-xs text-gray-600 block mb-1">PRIORITY</label>
                                <select name="priority"
                                        class="w-full bg-black text-white border-2 border-gray-700 p-2 text-xs focus:border-neon-green focus:outline-none">
                                    <option value="normal">NORMAL</option>
                                    <option value="critical">CRITICAL</option>
                                    <option value="low">LOW</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 block mb-1">ASSIGN_TO</label>
                                <select name="assigned_to"
                                        class="w-full bg-black text-white border-2 border-gray-700 p-2 text-xs focus:border-neon-green focus:outline-none">
                                    <option value="">UNASSIGNED</option>
                                    @foreach($teamMembers as $member)
                                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-xs text-gray-600 block mb-1">DUE_DATE</label>
                            <input type="date" name="due_date"
                                   class="w-full bg-black text-white border-2 border-gray-700 p-2 text-xs focus:border-neon-green focus:outline-none">
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="btn-brutal-sm flex-1 text-center">+ ADD_TASK</button>
                            <button type="button" @click="addOpen = false"
                                    class="text-xs border-2 border-gray-700 px-3 py-1 text-gray-500 hover:border-white hover:text-white transition-colors">
                                ESC
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Task Cards --}}
                <div class="space-y-3">
                    @forelse($grouped[$col['key']] as $task)
                        <div class="bg-black border-2 p-4 transition-colors
                            {{ $task->isOverdue() ? 'border-red-500' : 'border-gray-700 hover:border-gray-400' }}"
                             x-show="active === null || {{ json_encode($task->labels->pluck('id')) }}.includes(active)"
                             x-cloak>

                            {{-- Priority + Delete --}}
                            <div class="flex justify-between items-center mb-3">
                                @php
                                    $pColor = match($task->priority) {
                                        'critical' => ['text' => 'text-red-400',    'border' => 'border-red-400'],
                                        'low'      => ['text' => 'text-gray-500',   'border' => 'border-gray-600'],
                                        default    => ['text' => 'text-neon-green', 'border' => 'border-neon-green'],
                                    };
                                @endphp
                                <span class="text-xs font-bold px-2 py-0.5 border {{ $pColor['text'] }} {{ $pColor['border'] }}">
                                    {{ strtoupper($task->priority) }}
                                </span>

                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Delete task?')"
                                            class="text-gray-700 hover:text-red-500 transition-colors text-sm leading-none">✕</button>
                                </form>
                            </div>

                            {{-- Title --}}
                            <a href="{{ route('tasks.show', $task) }}"
                               class="block font-bold text-sm mb-3 leading-snug hover:text-neon-green transition-colors
                                {{ $col['key'] === 'done' ? 'line-through text-gray-500 hover:text-gray-400' : 'text-white' }}">
                                {{ $task->title }}
                            </a>

                            @if($task->description)
                                <p class="text-xs text-gray-600 mb-3 leading-relaxed line-clamp-2">
                                    {{ $task->description }}
                                </p>
                            @endif

                            {{-- Meta --}}
                            <div class="space-y-1 mb-3 text-xs">
                                @if($task->due_date)
                                    <div class="flex items-center gap-1 {{ $task->isOverdue() ? 'text-red-400' : 'text-gray-500' }}">
                                        <span>DUE:</span>
                                        <span>{{ $task->due_date->format('d M Y') }}</span>
                                        @if($task->isOverdue())
                                            <span class="text-red-500 font-bold ml-1">OVERDUE</span>
                                        @endif
                                    </div>
                                @endif

                                @if($task->assignee)
                                    <div class="flex items-center gap-2 text-gray-500">
                                        <span>ASSIGNED:</span>
                                        <div class="bg-gray-800 text-white px-1.5 py-0.5 font-bold text-xs">
                                            {{ strtoupper(substr($task->assignee->name, 0, 8)) }}
                                        </div>
                                    </div>
                                @endif

                                @if($task->comments->count())
                                    <div class="text-gray-700">
                                        {{ $task->comments->count() }} COMMENT{{ $task->comments->count() > 1 ? 'S' : '' }}
                                    </div>
                                @endif
                            </div>

                            {{-- Labels --}}
                            @if($task->labels->count())
                                <div class="flex flex-wrap gap-1 mb-3">
                                    @foreach($task->labels as $label)
                                        <span class="text-xs px-2 py-0.5 border font-bold"
                                              style="color: {{ $label->color }}; border-color: {{ $label->color }}">
                                            {{ $label->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Epic --}}
                            @if($task->epic)
                                <div class="mb-3">
                                    <a href="{{ route('epics.show', [$project, $task->epic]) }}"
                                       class="text-xs font-bold px-2 py-0.5 border transition-colors hover:opacity-80"
                                       style="color:{{ $task->epic->color }};border-color:{{ $task->epic->color }}">
                                        ◆ {{ $task->epic->name }}
                                    </a>
                                </div>
                            @endif

                            {{-- Column Actions --}}
                            <div class="flex gap-2 border-t border-gray-800 pt-2 mt-2">
                                @if($col['key'] === 'todo')
                                    <form action="{{ route('tasks.status', $task) }}" method="POST" class="flex-1">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="doing">
                                        <button type="submit"
                                                class="w-full text-xs border border-gray-700 py-1.5 hover:bg-yellow-500 hover:text-black hover:border-yellow-500 transition-all font-bold">
                                            START &gt;
                                        </button>
                                    </form>

                                @elseif($col['key'] === 'doing')
                                    <form action="{{ route('tasks.status', $task) }}" method="POST" class="flex-1">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="todo">
                                        <button type="submit"
                                                class="w-full text-xs border border-gray-700 py-1.5 hover:bg-gray-800 hover:text-white transition-all">
                                            &lt; BACK
                                        </button>
                                    </form>
                                    <form action="{{ route('tasks.status', $task) }}" method="POST" class="flex-1">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="done">
                                        <button type="submit"
                                                class="w-full text-xs border border-neon-green py-1.5 text-neon-green hover:bg-neon-green hover:text-black transition-all font-bold">
                                            SHIP &gt;
                                        </button>
                                    </form>

                                @elseif($col['key'] === 'done')
                                    <form action="{{ route('tasks.status', $task) }}" method="POST" class="flex-1">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="doing">
                                        <button type="submit"
                                                class="w-full text-xs border border-gray-700 py-1.5 hover:bg-gray-800 hover:text-white transition-all">
                                            REOPEN
                                        </button>
                                    </form>
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
    </div>{{-- end labelFilter() scope --}}
@push('scripts')
<script>
function labelFilter() {
    return {
        active: null,
        toggle(id) { this.active = this.active === id ? null : id; },
    };
}

(function () {
    const teamId = document.querySelector('meta[name="team-id"]')?.content;
    if (!teamId || !window.Echo) return;

    window.Echo.private('team.' + teamId)
        .listen('.task.moved', (e) => {
            showBoardBanner(
                `${e.moved_by} moved "${e.title}" → ${{ todo: 'TO_DO', doing: 'IN_PROGRESS', done: 'SHIPPED' }[e.new_status] ?? e.new_status}`
            );
        });

    function showBoardBanner(message) {
        const existing = document.getElementById('board-update-banner');
        if (existing) existing.remove();

        const banner = document.createElement('div');
        banner.id = 'board-update-banner';
        banner.className = 'fixed top-20 left-1/2 -translate-x-1/2 z-50 flex items-center gap-4 bg-black border-2 border-neon-green px-5 py-3 text-xs font-mono shadow-lg';
        banner.innerHTML = `
            <span class="text-neon-green">&gt;&gt; ${message}</span>
            <button onclick="window.location.reload()"
                    class="border border-neon-green text-neon-green px-3 py-1 hover:bg-neon-green hover:text-black transition-colors font-bold">
                REFRESH
            </button>
            <button onclick="this.parentElement.remove()" class="text-gray-600 hover:text-white transition-colors">✕</button>
        `;
        document.body.appendChild(banner);

        setTimeout(() => banner?.remove(), 12000);
    }
})();
</script>
@endpush
</x-app-layout>
