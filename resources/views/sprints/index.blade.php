<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-white text-sm transition-colors">&lt; BOARDS</a>
                <span class="text-gray-700">/</span>
                <a href="{{ route('projects.show', $project) }}" class="text-gray-500 hover:text-white text-sm transition-colors uppercase">{{ $project->name }}</a>
                <span class="text-gray-700">/</span>
                <h1 class="text-xl font-bold text-white uppercase">SPRINTS_</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.backlog', $project) }}"
                   class="text-xs border-2 border-gray-700 px-3 py-1.5 text-gray-400 hover:border-purple-400 hover:text-purple-400 transition-colors font-bold">
                    BACKLOG
                </a>
                <a href="{{ route('projects.show', $project) }}"
                   class="text-xs border-2 border-gray-700 px-3 py-1.5 text-gray-400 hover:border-white hover:text-white transition-colors font-bold">
                    KANBAN
                </a>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-2 ml-12">Plan and manage time-boxed sprints for {{ $project->name }}.</p>
    </x-slot>

    <div class="p-6 bg-pitch-black min-h-screen max-w-5xl">

        {{-- Create Sprint form — owners and admins only --}}
        @if($canManage)
        <div x-data="{ open: false }" class="mb-8">
            <button @click="open = !open"
                    class="text-xs border-2 border-neon-green text-neon-green px-4 py-2 font-bold hover:bg-neon-green hover:text-black transition-colors font-mono">
                + NEW_SPRINT
            </button>

            <div x-show="open" x-cloak x-transition
                 class="mt-3 border-2 border-neon-green bg-black p-5">
                <p class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-4">// CREATE_SPRINT</p>
                <form action="{{ route('sprints.store', $project) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="md:col-span-2">
                            <label class="text-xs text-gray-600 block mb-1">SPRINT NAME *</label>
                            <input type="text" name="name" placeholder="e.g. Sprint 1 — Auth System"
                                   required maxlength="255"
                                   class="w-full bg-black text-white border-2 border-gray-700 px-3 py-2 text-sm font-mono focus:border-neon-green focus:outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs text-gray-600 block mb-1">SPRINT GOAL</label>
                            <input type="text" name="goal" placeholder="What should be achieved by the end of this sprint?"
                                   maxlength="500"
                                   class="w-full bg-black text-white border-2 border-gray-700 px-3 py-2 text-sm font-mono focus:border-neon-green focus:outline-none">
                        </div>
                        <div>
                            <label class="text-xs text-gray-600 block mb-1">START DATE *</label>
                            <input type="date" name="start_date" required
                                   class="w-full bg-black text-white border-2 border-gray-700 px-3 py-2 text-sm font-mono focus:border-neon-green focus:outline-none">
                        </div>
                        <div>
                            <label class="text-xs text-gray-600 block mb-1">END DATE *</label>
                            <input type="date" name="end_date" required
                                   class="w-full bg-black text-white border-2 border-gray-700 px-3 py-2 text-sm font-mono focus:border-neon-green focus:outline-none">
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-neon-green text-black font-bold font-mono text-sm px-6 py-2 hover:bg-white transition-colors">
                            CREATE
                        </button>
                        <button type="button" @click="open = false"
                                class="text-xs border border-gray-700 px-4 py-2 text-gray-500 hover:text-white transition-colors font-mono">
                            ESC
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @else
        <div class="mb-8"></div>
        @endif

        @if($sprints->isEmpty())
            <div class="border-2 border-dashed border-gray-800 p-16 text-center">
                <p class="text-gray-700 text-xs font-mono mb-1">// NO_SPRINTS_YET</p>
                <p class="text-gray-700 text-xs">Create your first sprint above, then add backlog items to it.</p>
            </div>
        @else

        {{-- Active Sprint --}}
        @if($activeSprint)
        <div class="mb-6">
            <p class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-3">// ACTIVE_SPRINT</p>
            @php
                $done  = $activeSprint->done_count;
                $total = $activeSprint->total_count;
                $pct   = $total > 0 ? round($done / $total * 100) : 0;
            @endphp
            <div class="border-2 border-neon-green bg-black p-5">
                <div class="flex justify-between items-start flex-wrap gap-4 mb-3">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h2 class="text-base font-bold text-white">{{ $activeSprint->name }}</h2>
                            <span class="text-xs border border-neon-green text-neon-green px-2 py-0.5">ACTIVE</span>
                        </div>
                        <p class="text-xs text-gray-600">
                            {{ $activeSprint->start_date->format('d M Y') }} — {{ $activeSprint->end_date->format('d M Y') }}
                            @if($activeSprint->end_date->isPast())
                                <span class="text-red-500 ml-2">OVERDUE</span>
                            @else
                                <span class="text-gray-700 ml-2">{{ now()->diffInDays($activeSprint->end_date) }}d remaining</span>
                            @endif
                        </p>
                        @if($activeSprint->goal)
                            <p class="text-xs text-gray-500 mt-1 italic">Goal: {{ $activeSprint->goal }}</p>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('sprints.show', [$project, $activeSprint]) }}"
                           class="text-xs border-2 border-neon-green text-neon-green px-3 py-1.5 hover:bg-neon-green hover:text-black transition-colors font-bold font-mono">
                            VIEW BOARD &rarr;
                        </a>
                        @if($canManage)
                        <form action="{{ route('sprints.complete', [$project, $activeSprint]) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    onclick="return confirm('Complete this sprint? Unfinished tasks will return to the backlog.')"
                                    class="text-xs border border-gray-700 text-gray-400 px-3 py-1.5 hover:border-red-500 hover:text-red-500 transition-colors font-mono">
                                COMPLETE
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                {{-- Progress bar --}}
                <div class="mt-2">
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>{{ $done }}/{{ $total }} tasks done</span>
                        <span class="text-neon-green font-bold">{{ $pct }}%</span>
                    </div>
                    <div class="h-2 bg-gray-900 border border-gray-800">
                        <div class="h-full bg-neon-green transition-all" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Planning Sprints --}}
        @php $planningSprints = $sprints->where('status', 'planning'); @endphp
        @if($planningSprints->isNotEmpty())
        <div class="mb-6">
            <p class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-3">
                // PLANNING <span class="text-gray-700">({{ $planningSprints->count() }})</span>
            </p>
            @foreach($planningSprints as $sprint)
            <div class="border border-gray-700 bg-black p-5 mb-3 hover:border-gray-500 transition-colors">
                <div class="flex justify-between items-start flex-wrap gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h2 class="text-sm font-bold text-white">{{ $sprint->name }}</h2>
                            <span class="text-xs border border-yellow-600 text-yellow-600 px-2 py-0.5">PLANNING</span>
                        </div>
                        <p class="text-xs text-gray-600">
                            {{ $sprint->start_date->format('d M Y') }} — {{ $sprint->end_date->format('d M Y') }}
                        </p>
                        @if($sprint->goal)
                            <p class="text-xs text-gray-500 mt-1 italic">Goal: {{ $sprint->goal }}</p>
                        @endif
                        <p class="text-xs text-gray-700 mt-1">{{ $sprint->total_count }} task{{ $sprint->total_count !== 1 ? 's' : '' }} planned</p>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('sprints.show', [$project, $sprint]) }}"
                           class="text-xs border border-gray-700 text-gray-400 px-3 py-1.5 hover:border-white hover:text-white transition-colors font-mono">
                            PLAN &rarr;
                        </a>
                        @if($canManage)
                            @if(!$activeSprint)
                            <form action="{{ route('sprints.start', [$project, $sprint]) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        onclick="return confirm('Start this sprint? Backlog items will be moved to TO_DO.')"
                                        class="text-xs border border-neon-green text-neon-green px-3 py-1.5 hover:bg-neon-green hover:text-black transition-colors font-bold font-mono">
                                    START &rarr;
                                </button>
                            </form>
                            @endif
                            <form action="{{ route('sprints.destroy', [$project, $sprint]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Delete this sprint? Tasks will be unassigned.')"
                                        class="text-xs border border-gray-800 text-gray-700 px-3 py-1.5 hover:border-red-500 hover:text-red-500 transition-colors font-mono">
                                    DEL
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Completed Sprints --}}
        @php $completedSprints = $sprints->where('status', 'completed'); @endphp
        @if($completedSprints->isNotEmpty())
        <div x-data="{ open: false }">
            <button @click="open = !open"
                    class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-3 hover:text-gray-400 transition-colors flex items-center gap-2">
                <span>// COMPLETED <span class="text-gray-700">({{ $completedSprints->count() }})</span></span>
                <span x-text="open ? '▲' : '▼'" class="text-gray-700 text-xs"></span>
            </button>
            <div x-show="open" x-cloak class="space-y-2">
                @foreach($completedSprints as $sprint)
                @php
                    $done  = $sprint->done_count;
                    $total = $sprint->total_count;
                    $pct   = $total > 0 ? round($done / $total * 100) : 0;
                @endphp
                <div class="border border-gray-900 bg-black px-5 py-3 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <span class="text-xs border border-gray-800 text-gray-700 px-2 py-0.5">DONE</span>
                        <span class="text-sm text-gray-500 font-bold">{{ $sprint->name }}</span>
                        <span class="text-xs text-gray-700">
                            {{ $sprint->start_date->format('d M') }} — {{ $sprint->end_date->format('d M Y') }}
                        </span>
                        <span class="text-xs text-gray-700">{{ $done }}/{{ $total }} shipped ({{ $pct }}%)</span>
                    </div>
                    <a href="{{ route('sprints.show', [$project, $sprint]) }}"
                       class="text-xs text-gray-700 hover:text-gray-400 transition-colors font-mono">
                        VIEW
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @endif
    </div>
</x-app-layout>
