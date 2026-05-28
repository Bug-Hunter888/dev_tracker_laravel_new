<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('epics.index', $project) }}" class="text-gray-500 hover:text-white text-sm transition-colors">&lt; EPICS</a>
                <span class="text-gray-700">/</span>
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 border-2 shrink-0" style="background:{{ $epic->color }};border-color:{{ $epic->color }}"></div>
                    <h1 class="text-xl font-bold text-white uppercase">{{ $epic->name }}</h1>
                    <span class="text-xs border px-2 py-0.5 font-mono
                        {{ $epic->status === 'open' ? 'border-purple-600 text-purple-400' : 'border-gray-700 text-gray-600' }}">
                        {{ strtoupper($epic->status) }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Inline edit --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="text-xs border-2 border-gray-700 px-3 py-1.5 text-gray-400 hover:border-white hover:text-white transition-colors font-mono">
                        EDIT
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false"
                         class="absolute right-0 mt-2 w-80 bg-black border-2 border-white p-4 z-10 shadow-hard">
                        <p class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-3">// EDIT_EPIC</p>
                        <form action="{{ route('epics.update', [$project, $epic]) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="space-y-3">
                                <input type="text" name="name" value="{{ $epic->name }}" required maxlength="255"
                                       class="w-full bg-black text-white border-2 border-gray-700 px-3 py-2 text-sm font-mono focus:border-white focus:outline-none">
                                <textarea name="description" rows="2" placeholder="Description..."
                                          class="w-full bg-black text-white border-2 border-gray-700 px-3 py-2 text-xs font-mono focus:border-white focus:outline-none resize-none">{{ $epic->description }}</textarea>
                                <div class="flex items-center gap-3">
                                    <input type="color" name="color" value="{{ $epic->color }}"
                                           class="w-10 h-8 border-2 border-gray-700 bg-black cursor-pointer">
                                    <span class="text-xs text-gray-700">Epic color</span>
                                </div>
                                <button type="submit"
                                        class="w-full text-xs border-2 border-white text-white py-2 font-bold hover:bg-white hover:text-black transition-colors font-mono">
                                    SAVE_CHANGES
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Close / Reopen --}}
                <form action="{{ route('epics.update', [$project, $epic]) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="{{ $epic->status === 'open' ? 'closed' : 'open' }}">
                    <button type="submit"
                            class="text-xs border-2 px-3 py-1.5 font-bold font-mono transition-colors
                            {{ $epic->status === 'open'
                                ? 'border-gray-700 text-gray-400 hover:border-red-500 hover:text-red-500'
                                : 'border-neon-green text-neon-green hover:bg-neon-green hover:text-black' }}">
                        {{ $epic->status === 'open' ? 'CLOSE EPIC' : 'REOPEN EPIC' }}
                    </button>
                </form>
            </div>
        </div>

        @if($epic->description)
            <p class="text-xs text-gray-500 mt-2 ml-12">{{ $epic->description }}</p>
        @endif
    </x-slot>

    {{-- Progress bar --}}
    <div class="border-b-2 border-gray-800 bg-dark-gray px-8 py-3">
        <div class="flex items-center gap-6 text-xs font-mono mb-2">
            <span><span class="text-gray-600">TASKS:</span> <span class="text-white font-bold">{{ $total }}</span></span>
            <span><span class="text-gray-600">DONE:</span> <span class="text-neon-green font-bold">{{ $done }}</span></span>
            <span><span class="text-gray-600">REMAINING:</span> <span class="text-yellow-500 font-bold">{{ $total - $done }}</span></span>
            <span class="font-bold" style="color:{{ $epic->color }}">{{ $pct }}%</span>
        </div>
        @if($total > 0)
        <div class="h-1.5 bg-gray-900 border border-gray-800 max-w-md">
            <div class="h-full transition-all" style="width:{{ $pct }}%;background:{{ $epic->color }}"></div>
        </div>
        @endif
    </div>

    <div class="p-6 bg-pitch-black min-h-screen max-w-5xl">

        @if($tasks->isEmpty())
            <div class="border-2 border-dashed border-gray-800 p-16 text-center">
                <p class="text-gray-700 text-xs font-mono mb-2">// NO_TASKS_IN_THIS_EPIC</p>
                <p class="text-gray-700 text-xs">
                    Open a task's detail page and set its EPIC field to <span class="text-gray-500">"{{ $epic->name }}"</span>.
                </p>
                <a href="{{ route('projects.backlog', $project) }}"
                   class="inline-block mt-4 text-xs border border-gray-700 text-gray-500 px-3 py-1.5 hover:border-white hover:text-white transition-colors font-mono">
                    &rarr; GO TO BACKLOG
                </a>
            </div>
        @else
            @php
                $sections = [
                    ['key' => 'todo',    'label' => 'TO_DO',       'color' => 'text-white'],
                    ['key' => 'doing',   'label' => 'IN_PROGRESS',  'color' => 'text-yellow-500'],
                    ['key' => 'done',    'label' => 'SHIPPED',      'color' => 'text-neon-green'],
                    ['key' => 'backlog', 'label' => 'BACKLOG',      'color' => 'text-purple-400'],
                ];
            @endphp

            @foreach($sections as $section)
                @if($grouped[$section['key']]->isNotEmpty())
                <div class="mb-6">
                    <p class="text-xs font-mono uppercase tracking-widest font-bold mb-2 {{ $section['color'] }}">
                        // {{ $section['label'] }}
                        <span class="text-gray-700">({{ $grouped[$section['key']]->count() }})</span>
                    </p>
                    <div class="space-y-2">
                        @foreach($grouped[$section['key']] as $task)
                        @php
                            $pStyle = match($task->priority) {
                                'critical' => ['text' => 'text-red-400',    'border' => 'border-red-400'],
                                'low'      => ['text' => 'text-gray-500',   'border' => 'border-gray-600'],
                                default    => ['text' => 'text-neon-green', 'border' => 'border-neon-green'],
                            };
                        @endphp
                        <div class="border border-gray-800 hover:border-gray-600 transition-colors bg-black px-4 py-3 flex items-center gap-4">
                            <span class="text-xs font-bold px-2 py-0.5 border shrink-0 w-20 text-center {{ $pStyle['text'] }} {{ $pStyle['border'] }}">
                                {{ strtoupper($task->priority) }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('tasks.show', $task) }}"
                                   class="text-sm font-bold hover:text-neon-green transition-colors
                                    {{ $section['key'] === 'done' ? 'line-through text-gray-500' : 'text-white' }}">
                                    {{ $task->title }}
                                </a>
                                <div class="flex items-center gap-3 mt-1 text-xs text-gray-600">
                                    @if($task->assignee)
                                        <span>{{ $task->assignee->name }}</span>
                                    @endif
                                    @if($task->sprint)
                                        <span class="border border-blue-900 text-blue-400 px-1.5 py-0.5">{{ $task->sprint->name }}</span>
                                    @endif
                                    @if($task->story_points)
                                        <span>{{ $task->story_points }}pt</span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('tasks.show', $task) }}"
                               class="text-xs border border-gray-700 text-gray-500 px-2 py-1 hover:border-white hover:text-white transition-colors font-mono shrink-0">
                                VIEW
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach
        @endif

        <div class="mt-8 pt-4 border-t border-gray-900 text-xs text-gray-700 font-mono">
            To add tasks to this epic: open any task &rarr; PROPERTIES &rarr; set EPIC to <span class="text-gray-500">"{{ $epic->name }}"</span>
        </div>
    </div>
</x-app-layout>
