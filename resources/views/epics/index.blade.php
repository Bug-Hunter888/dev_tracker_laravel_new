<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('projects.show', $project) }}" class="text-gray-500 hover:text-white text-sm transition-colors">
                    &lt; {{ strtoupper($project->name) }}
                </a>
                <span class="text-gray-700">/</span>
                <h1 class="text-xl font-bold text-white uppercase">EPICS_</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.show', $project) }}"
                   class="text-xs border-2 border-gray-700 px-3 py-1.5 text-gray-400 hover:border-white hover:text-white transition-colors font-bold">
                    KANBAN
                </a>
                <a href="{{ route('projects.backlog', $project) }}"
                   class="text-xs border-2 border-gray-700 px-3 py-1.5 text-gray-400 hover:border-purple-400 hover:text-purple-400 transition-colors font-bold">
                    BACKLOG
                </a>
                <a href="{{ route('sprints.index', $project) }}"
                   class="text-xs border-2 border-gray-700 px-3 py-1.5 text-gray-400 hover:border-blue-400 hover:text-blue-400 transition-colors font-bold">
                    SPRINTS
                </a>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-2 ml-12">Large bodies of work spanning multiple sprints — group tasks under epics to track progress at scale.</p>
    </x-slot>

    {{-- Stats bar --}}
    @php $openCount = $epics->where('status', 'open')->count(); @endphp
    <div class="border-b-2 border-gray-800 bg-dark-gray px-8 py-3 flex gap-8 text-xs font-mono">
        <div><span class="text-gray-600">EPICS:</span> <span class="text-white font-bold">{{ $epics->count() }}</span></div>
        <div><span class="text-gray-600">OPEN:</span> <span class="text-purple-400 font-bold">{{ $openCount }}</span></div>
        <div><span class="text-gray-600">CLOSED:</span> <span class="text-gray-500 font-bold">{{ $epics->count() - $openCount }}</span></div>
    </div>

    <div class="p-6 bg-pitch-black min-h-screen max-w-5xl">

        {{-- Create form --}}
        <div x-data="{ open: false }" class="mb-6">
            <button @click="open = !open"
                    class="text-xs border-2 border-purple-500 text-purple-400 px-4 py-2 font-bold hover:bg-purple-500 hover:text-black transition-colors font-mono">
                + NEW_EPIC
            </button>

            <div x-show="open" x-cloak x-transition
                 class="mt-3 border-2 border-purple-500 bg-black p-5">
                <p class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-4">// CREATE_EPIC</p>
                <form action="{{ route('epics.store', $project) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="md:col-span-2">
                            <label class="text-xs text-gray-600 block mb-1">NAME *</label>
                            <input type="text" name="name" placeholder="e.g. User Authentication, Billing System..."
                                   required maxlength="255"
                                   class="w-full bg-black text-white border-2 border-gray-700 px-3 py-2 text-sm font-mono focus:border-purple-500 focus:outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs text-gray-600 block mb-1">DESCRIPTION</label>
                            <textarea name="description" rows="2" placeholder="What does this epic cover?"
                                      class="w-full bg-black text-white border-2 border-gray-700 px-3 py-2 text-xs font-mono focus:border-purple-500 focus:outline-none resize-none"></textarea>
                        </div>
                        <div>
                            <label class="text-xs text-gray-600 block mb-1">COLOR</label>
                            <div class="flex items-center gap-3">
                                <input type="color" name="color" value="#8B5CF6"
                                       class="w-10 h-8 border-2 border-gray-700 bg-black cursor-pointer">
                                <span class="text-xs text-gray-700">Identifies this epic on task cards</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-purple-500 text-white font-bold font-mono text-sm px-6 py-2 hover:bg-purple-400 transition-colors">
                            CREATE EPIC
                        </button>
                        <button type="button" @click="open = false"
                                class="text-xs border border-gray-700 px-4 py-2 text-gray-500 hover:text-white transition-colors font-mono">
                            ESC
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Epics list --}}
        @if($epics->isEmpty())
            <div class="border-2 border-dashed border-gray-800 p-16 text-center">
                <p class="text-gray-700 text-xs font-mono mb-1">// NO_EPICS_YET</p>
                <p class="text-gray-700 text-xs">Epics group related tasks across sprints. Create one above.</p>
            </div>
        @else
            <div class="text-xs text-gray-700 font-mono uppercase tracking-widest mb-3">// EPIC_LIST</div>
            <div class="space-y-3">
                @foreach($epics as $epic)
                @php
                    $epicPct = $epic->total_count > 0 ? round($epic->done_count / $epic->total_count * 100) : 0;
                @endphp
                <div class="border border-gray-800 hover:border-gray-600 transition-colors bg-black p-4
                    {{ $epic->status === 'closed' ? 'opacity-60' : '' }}">
                    <div class="flex items-center gap-4">

                        {{-- Color swatch --}}
                        <div class="w-3 h-10 shrink-0" style="background:{{ $epic->color }}"></div>

                        {{-- Name + progress --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 flex-wrap mb-1">
                                <a href="{{ route('epics.show', [$project, $epic]) }}"
                                   class="text-sm font-bold hover:text-purple-400 transition-colors {{ $epic->status === 'closed' ? 'line-through text-gray-500' : 'text-white' }}">
                                    {{ $epic->name }}
                                </a>
                                <span class="text-xs border px-2 py-0.5 font-mono
                                    {{ $epic->status === 'open' ? 'border-purple-600 text-purple-400' : 'border-gray-700 text-gray-600' }}">
                                    {{ strtoupper($epic->status) }}
                                </span>
                            </div>

                            @if($epic->description)
                                <p class="text-xs text-gray-600 mb-2 truncate">{{ $epic->description }}</p>
                            @endif

                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-1 bg-gray-900 border border-gray-800 max-w-xs">
                                    <div class="h-full transition-all" style="width:{{ $epicPct }}%;background:{{ $epic->color }}"></div>
                                </div>
                                <span class="text-xs text-gray-600 font-mono shrink-0">
                                    {{ $epic->done_count }}/{{ $epic->total_count }} tasks &bull; {{ $epicPct }}%
                                </span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 shrink-0">
                            <form action="{{ route('epics.update', [$project, $epic]) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $epic->status === 'open' ? 'closed' : 'open' }}">
                                <button type="submit"
                                        class="text-xs border border-gray-700 text-gray-500 px-2 py-1 hover:border-white hover:text-white transition-colors font-mono">
                                    {{ $epic->status === 'open' ? 'CLOSE' : 'REOPEN' }}
                                </button>
                            </form>

                            <a href="{{ route('epics.show', [$project, $epic]) }}"
                               class="text-xs border font-bold px-3 py-1 transition-colors font-mono"
                               style="color:{{ $epic->color }};border-color:{{ $epic->color }}">
                                VIEW &rarr;
                            </a>

                            <form action="{{ route('epics.destroy', [$project, $epic]) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Delete epic \'{{ addslashes($epic->name) }}\'? Tasks will remain but lose their epic.')"
                                        class="text-gray-700 hover:text-red-500 transition-colors text-sm leading-none px-1">✕</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
