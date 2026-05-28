<x-app-layout>
    <div class="max-w-4xl mx-auto px-6 py-10">

        {{-- Header --}}
        <div class="mb-8">
            <p class="text-xs font-mono text-neon-green tracking-widest uppercase mb-1">// global search</p>
            <h1 class="text-2xl font-bold text-white tracking-tight">SEARCH</h1>
        </div>

        {{-- Search form --}}
        <form method="GET" action="{{ route('search') }}" class="mb-10">
            <div class="flex gap-0">
                <span class="border-2 border-r-0 border-white bg-black px-4 flex items-center text-neon-green font-mono text-sm shrink-0">$</span>
                <input
                    type="text"
                    name="q"
                    value="{{ $q }}"
                    placeholder="search projects and tasks..."
                    autofocus
                    autocomplete="off"
                    class="flex-1 bg-black border-2 border-r-0 border-white text-white font-mono text-sm px-4 py-3 focus:outline-none focus:border-neon-green placeholder-gray-700"
                >
                <button type="submit"
                        class="bg-neon-green text-black font-mono font-bold text-sm px-6 py-3 hover:bg-white transition-colors shrink-0">
                    FIND
                </button>
            </div>
            @if($q)
                <p class="text-xs font-mono text-gray-600 mt-2">
                    {{ $projects->count() + $tasks->count() }} result{{ ($projects->count() + $tasks->count()) !== 1 ? 's' : '' }} for "<span class="text-gray-400">{{ $q }}</span>"
                </p>
            @else
                <p class="text-xs font-mono text-gray-700 mt-2">Type at least 2 characters to search.</p>
            @endif
        </form>

        @if($q && $projects->isEmpty() && $tasks->isEmpty())
            {{-- No results --}}
            <div class="border-2 border-gray-800 bg-black px-8 py-12 text-center">
                <p class="text-2xl font-mono text-gray-700 mb-2">[ NULL ]</p>
                <p class="text-sm font-mono text-gray-600">No projects or tasks match "<span class="text-gray-400">{{ $q }}</span>"</p>
            </div>

        @else
            {{-- Projects --}}
            @if($projects->isNotEmpty())
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-4">
                    <p class="text-xs font-mono font-bold uppercase tracking-widest text-gray-500">BOARDS</p>
                    <span class="text-xs font-mono text-gray-700">{{ $projects->count() }}</span>
                </div>
                <div class="space-y-1">
                    @foreach($projects as $project)
                    <a href="{{ route('projects.show', $project) }}"
                       class="flex items-center justify-between px-5 py-4 border border-gray-800 bg-black hover:border-white hover:bg-gray-950 transition-colors group">
                        <div class="flex items-center gap-4 min-w-0">
                            {{-- Color dot --}}
                            <span class="w-2 h-2 rounded-full shrink-0"
                                  style="background:{{ $project->color ?? '#39FF14' }};"></span>
                            <div class="min-w-0">
                                <p class="text-sm font-mono font-bold text-white group-hover:text-neon-green transition-colors truncate">
                                    {!! preg_replace('/(' . preg_quote($q, '/') . ')/i', '<mark class="bg-neon-green text-black px-0.5">$1</mark>', e($project->name)) !!}
                                </p>
                                @if($project->description)
                                <p class="text-xs font-mono text-gray-600 truncate mt-0.5">
                                    {!! preg_replace('/(' . preg_quote($q, '/') . ')/i', '<mark class="bg-neon-green text-black px-0.5">$1</mark>', e(Str::limit($project->description, 80))) !!}
                                </p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-4 shrink-0 ml-4">
                            <span class="text-xs font-mono text-gray-600">
                                {{ $project->tasks()->count() }} task{{ $project->tasks()->count() !== 1 ? 's' : '' }}
                            </span>
                            <span class="text-xs font-mono px-2 py-0.5 border
                                {{ $project->status === 'active'   ? 'border-neon-green text-neon-green'  : '' }}
                                {{ $project->status === 'archived' ? 'border-gray-700 text-gray-600'     : '' }}
                                {{ $project->status === 'on_hold'  ? 'border-yellow-600 text-yellow-600' : '' }}">
                                {{ strtoupper($project->status ?? 'ACTIVE') }}
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Tasks --}}
            @if($tasks->isNotEmpty())
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <p class="text-xs font-mono font-bold uppercase tracking-widest text-gray-500">TASKS</p>
                    <span class="text-xs font-mono text-gray-700">{{ $tasks->count() }}</span>
                </div>
                <div class="space-y-1">
                    @foreach($tasks as $task)
                    @php
                        $statusLabel = ['todo' => 'TO_DO', 'doing' => 'IN_PROGRESS', 'done' => 'SHIPPED'][$task->status] ?? $task->status;
                        $statusColor = match($task->status) {
                            'done'  => 'border-neon-green text-neon-green',
                            'doing' => 'border-yellow-600 text-yellow-600',
                            default => 'border-gray-700 text-gray-600',
                        };
                        $priorityColor = match($task->priority) {
                            'critical' => 'text-red-500',
                            'low'      => 'text-gray-600',
                            default    => 'text-neon-green',
                        };
                    @endphp
                    <a href="{{ route('tasks.show', $task) }}"
                       class="flex items-center justify-between px-5 py-4 border border-gray-800 bg-black hover:border-white hover:bg-gray-950 transition-colors group">
                        <div class="flex items-center gap-4 min-w-0">
                            {{-- Priority marker --}}
                            <span class="font-mono font-bold text-sm shrink-0 {{ $priorityColor }}">!</span>
                            <div class="min-w-0">
                                <p class="text-sm font-mono font-bold text-white group-hover:text-neon-green transition-colors truncate">
                                    {!! preg_replace('/(' . preg_quote($q, '/') . ')/i', '<mark class="bg-neon-green text-black px-0.5">$1</mark>', e($task->title)) !!}
                                </p>
                                <p class="text-xs font-mono text-gray-600 mt-0.5">
                                    {{ $task->project->name }}
                                    @if($task->assignee)
                                        <span class="text-gray-700"> · </span>
                                        <span class="text-gray-500">{{ $task->assignee->name }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0 ml-4">
                            @if($task->due_date)
                                <span class="text-xs font-mono {{ $task->isOverdue() ? 'text-red-500' : 'text-gray-600' }}">
                                    {{ $task->due_date->format('M d') }}
                                </span>
                            @endif
                            <span class="text-xs font-mono px-2 py-0.5 border {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        @endif
    </div>
</x-app-layout>
