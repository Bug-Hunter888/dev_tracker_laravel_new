<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-white text-sm transition-colors">&lt; BOARDS</a>
                <span class="text-gray-700">/</span>
                <a href="{{ route('projects.show', $project) }}"
                   class="text-gray-500 hover:text-white text-sm transition-colors uppercase">
                    {{ $project->name }}
                </a>
                <span class="text-gray-700">/</span>
                <h1 class="text-xl font-bold text-white uppercase">BACKLOG_</h1>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('sprints.index', $project) }}"
                   class="text-xs border-2 border-gray-700 px-3 py-1.5 text-gray-400 hover:border-blue-400 hover:text-blue-400 transition-colors font-bold">
                    SPRINTS
                </a>
                <a href="{{ route('projects.show', $project) }}"
                   class="text-xs border-2 border-gray-700 px-3 py-1.5 text-gray-400 hover:border-white hover:text-white transition-colors font-bold">
                    KANBAN
                </a>
                <a href="{{ route('projects.timeline', $project) }}"
                   class="text-xs border-2 border-gray-700 px-3 py-1.5 text-gray-400 hover:border-yellow-500 hover:text-yellow-500 transition-colors font-bold">
                    TIMELINE
                </a>
                <a href="{{ route('epics.index', $project) }}"
                   class="text-xs border-2 border-gray-700 px-3 py-1.5 text-gray-400 hover:border-purple-500 hover:text-purple-500 transition-colors font-bold">
                    EPICS
                </a>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-2 ml-12">
            Unstarted work — promote tasks to the board when ready to action.
        </p>
    </x-slot>

    {{-- Stats bar --}}
    <div class="border-b-2 border-gray-800 bg-dark-gray px-8 py-3 flex gap-8 text-xs font-mono">
        @php
            $critical = $tasks->where('priority', 'critical')->count();
            $normal   = $tasks->where('priority', 'normal')->count();
            $low      = $tasks->where('priority', 'low')->count();
        @endphp
        <div><span class="text-gray-600">BACKLOG:</span> <span class="text-white font-bold">{{ $tasks->count() }}</span></div>
        <div><span class="text-gray-600">CRITICAL:</span> <span class="text-red-400 font-bold">{{ $critical }}</span></div>
        <div><span class="text-gray-600">NORMAL:</span> <span class="text-neon-green font-bold">{{ $normal }}</span></div>
        <div><span class="text-gray-600">LOW:</span> <span class="text-gray-500 font-bold">{{ $low }}</span></div>
    </div>

    <div class="p-6 bg-pitch-black min-h-screen max-w-5xl">

        {{-- Add to backlog form --}}
        <div x-data="{ open: false }" class="mb-6">
            <button @click="open = !open"
                    class="text-xs border-2 border-neon-green text-neon-green px-4 py-2 font-bold hover:bg-neon-green hover:text-black transition-colors font-mono">
                + ADD_TO_BACKLOG
            </button>

            <div x-show="open" x-cloak x-transition
                 class="mt-3 border-2 border-neon-green bg-black p-5">
                <p class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-4">// NEW_BACKLOG_ITEM</p>
                <form action="{{ route('tasks.store', $project) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="backlog">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="md:col-span-2">
                            <label class="text-xs text-gray-600 block mb-1">TITLE *</label>
                            <input type="text" name="title" placeholder="Describe the work item..."
                                   required maxlength="255"
                                   class="w-full bg-black text-white border-2 border-gray-700 px-3 py-2 text-sm font-mono focus:border-neon-green focus:outline-none">
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-xs text-gray-600 block mb-1">DESCRIPTION</label>
                            <textarea name="description" rows="2" placeholder="Optional details..."
                                      class="w-full bg-black text-white border-2 border-gray-700 px-3 py-2 text-xs font-mono focus:border-neon-green focus:outline-none resize-none"></textarea>
                        </div>

                        <div>
                            <label class="text-xs text-gray-600 block mb-1">PRIORITY</label>
                            <select name="priority"
                                    class="w-full bg-black text-white border-2 border-gray-700 px-3 py-2 text-xs font-mono focus:border-neon-green focus:outline-none">
                                <option value="normal">NORMAL</option>
                                <option value="critical">CRITICAL</option>
                                <option value="low">LOW</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs text-gray-600 block mb-1">ASSIGN_TO</label>
                            <select name="assigned_to"
                                    class="w-full bg-black text-white border-2 border-gray-700 px-3 py-2 text-xs font-mono focus:border-neon-green focus:outline-none">
                                <option value="">UNASSIGNED</option>
                                @foreach($teamMembers as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-xs text-gray-600 block mb-1">DUE_DATE</label>
                            <input type="date" name="due_date"
                                   class="w-full bg-black text-white border-2 border-gray-700 px-3 py-2 text-xs font-mono focus:border-neon-green focus:outline-none">
                        </div>

                        <div>
                            <label class="text-xs text-gray-600 block mb-1">STORY_POINTS</label>
                            <input type="number" name="story_points" min="0" max="999" placeholder="—"
                                   class="w-full bg-black text-white border-2 border-gray-700 px-3 py-2 text-xs font-mono focus:border-neon-green focus:outline-none">
                        </div>

                        @if(isset($projectEpics) && $projectEpics->isNotEmpty())
                        <div>
                            <label class="text-xs text-gray-600 block mb-1">EPIC</label>
                            <select name="epic_id"
                                    class="w-full bg-black text-white border-2 border-gray-700 px-3 py-2 text-xs font-mono focus:border-neon-green focus:outline-none">
                                <option value="">NO EPIC</option>
                                @foreach($projectEpics as $epic)
                                    <option value="{{ $epic->id }}">{{ $epic->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-neon-green text-black font-bold font-mono text-sm px-6 py-2 hover:bg-white transition-colors">
                            + ADD
                        </button>
                        <button type="button" @click="open = false"
                                class="text-xs border border-gray-700 px-4 py-2 text-gray-500 hover:text-white transition-colors font-mono">
                            ESC
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Backlog list --}}
        @if($tasks->isEmpty())
            <div class="border-2 border-dashed border-gray-800 p-16 text-center">
                <p class="text-gray-700 text-xs font-mono mb-1">// BACKLOG_EMPTY</p>
                <p class="text-gray-700 text-xs">Add items above or move tasks here from the board.</p>
            </div>
        @else
            <div class="text-xs text-gray-700 font-mono uppercase tracking-widest mb-3">
                // PRODUCT_BACKLOG — sorted by priority
            </div>

            <div class="space-y-2">
                @foreach($tasks as $task)
                @php
                    $pStyle = match($task->priority) {
                        'critical' => ['text' => 'text-red-400',    'border' => 'border-red-400',    'bg' => 'bg-red-950'],
                        'low'      => ['text' => 'text-gray-500',   'border' => 'border-gray-600',   'bg' => 'bg-gray-950'],
                        default    => ['text' => 'text-neon-green', 'border' => 'border-neon-green', 'bg' => 'bg-black'],
                    };
                @endphp
                <div class="border border-gray-800 hover:border-gray-600 transition-colors bg-black">
                    <div class="flex items-center gap-4 px-4 py-3">

                        {{-- Priority badge --}}
                        <span class="text-xs font-bold px-2 py-0.5 border shrink-0 w-20 text-center {{ $pStyle['text'] }} {{ $pStyle['border'] }}">
                            {{ strtoupper($task->priority) }}
                        </span>

                        {{-- Title + meta --}}
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('tasks.show', $task) }}"
                               class="text-sm font-bold text-white hover:text-neon-green transition-colors truncate block">
                                {{ $task->title }}
                            </a>
                            <div class="flex items-center gap-4 mt-1 text-xs text-gray-600 flex-wrap">
                                @if($task->assignee)
                                    <span>{{ $task->assignee->name }}</span>
                                @else
                                    <span class="text-gray-800">unassigned</span>
                                @endif

                                @if($task->due_date)
                                    <span class="{{ $task->isOverdue() ? 'text-red-500' : '' }}">
                                        due {{ $task->due_date->format('d M Y') }}
                                    </span>
                                @endif

                                @if($task->epic)
                                    <a href="{{ route('epics.show', [$project, $task->epic]) }}"
                                       class="font-bold hover:opacity-80 transition-opacity"
                                       style="color:{{ $task->epic->color }}">
                                        ◆ {{ $task->epic->name }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Labels --}}
                        @if($task->labels->count())
                            <div class="hidden md:flex items-center gap-1 shrink-0">
                                @foreach($task->labels as $label)
                                    <span class="text-xs px-1.5 py-0.5 border font-bold"
                                          style="color:{{ $label->color }};border-color:{{ $label->color }}">
                                        {{ $label->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 shrink-0">
                            {{-- Add to sprint (planning or active sprint exists) --}}
                            @if(isset($planningSprint) && $planningSprint && !$task->sprint_id)
                            <form action="{{ route('sprints.tasks.add', [$project, $planningSprint, $task]) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="text-xs border border-blue-500 text-blue-400 px-2 py-1 hover:bg-blue-500 hover:text-black transition-colors font-bold font-mono whitespace-nowrap">
                                    + SPRINT
                                </button>
                            </form>
                            @elseif(isset($activeSprint) && $activeSprint && !$task->sprint_id)
                            <form action="{{ route('sprints.tasks.add', [$project, $activeSprint, $task]) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="text-xs border border-blue-500 text-blue-400 px-2 py-1 hover:bg-blue-500 hover:text-black transition-colors font-bold font-mono whitespace-nowrap">
                                    + SPRINT
                                </button>
                            </form>
                            @elseif($task->sprint_id)
                            <span class="text-xs text-blue-400 font-mono border border-blue-900 px-2 py-1">IN SPRINT</span>
                            @endif

                            {{-- Promote to board --}}
                            <form action="{{ route('tasks.status', $task) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="todo">
                                <button type="submit"
                                        class="text-xs border border-neon-green text-neon-green px-3 py-1 hover:bg-neon-green hover:text-black transition-colors font-bold font-mono whitespace-nowrap">
                                    PROMOTE &rarr;
                                </button>
                            </form>

                            <a href="{{ route('tasks.show', $task) }}"
                               class="text-xs border border-gray-700 text-gray-500 px-2 py-1 hover:border-white hover:text-white transition-colors font-mono">
                                VIEW
                            </a>

                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Delete \'{{ addslashes($task->title) }}\'?')"
                                        class="text-gray-700 hover:text-red-500 transition-colors text-sm leading-none px-1">
                                    ✕
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($task->description)
                        <div class="px-4 pb-3 ml-24">
                            <p class="text-xs text-gray-700 leading-relaxed line-clamp-1">{{ $task->description }}</p>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
