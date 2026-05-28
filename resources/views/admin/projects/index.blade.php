<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-white text-sm transition-colors">&lt; ADMIN</a>
                <span class="text-gray-700">/</span>
                <h1 class="text-xl font-bold text-white uppercase">PROJECTS</h1>
                <span class="text-xs border border-blue-400 text-blue-400 px-2 py-0.5">{{ $projects->total() }} TOTAL</span>
            </div>

            <div class="flex items-center gap-3">
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search project name..."
                           class="bg-black border border-gray-700 text-white text-xs px-3 py-1.5 focus:border-neon-green focus:outline-none font-mono w-48">
                    <select name="status"
                            class="bg-black border border-gray-700 text-white text-xs px-2 py-1.5 focus:border-neon-green focus:outline-none font-mono">
                        <option value="">ALL STATUS</option>
                        <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>ACTIVE</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>COMPLETED</option>
                        <option value="archived"  {{ request('status') === 'archived'  ? 'selected' : '' }}>ARCHIVED</option>
                    </select>
                    <select name="team_id"
                            class="bg-black border border-gray-700 text-white text-xs px-2 py-1.5 focus:border-neon-green focus:outline-none font-mono">
                        <option value="">ALL TEAMS</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ request('team_id') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-brutal-sm">FILTER</button>
                    @if(request()->hasAny(['search','status','team_id']))
                        <a href="{{ route('admin.projects.index') }}" class="text-xs border border-gray-700 px-3 py-1.5 text-gray-500 hover:text-white hover:border-white transition-colors">CLEAR</a>
                    @endif
                </form>

                <button x-data x-on:click="$dispatch('toggle-create-project')"
                        class="text-xs border border-blue-400 text-blue-400 px-3 py-1.5 hover:bg-blue-400 hover:text-black transition-colors font-mono">
                    + NEW_PROJECT
                </button>
            </div>
        </div>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-4">

        {{-- Flash --}}
        @if(session('success'))
            <div class="border border-neon-green px-4 py-2 text-xs text-neon-green font-mono">&gt; {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="border border-red-500 px-4 py-2 text-xs text-red-500 font-mono">&gt; {{ session('error') }}</div>
        @endif

        {{-- Create Project Form --}}
        <div x-data="{ open: false }"
             x-on:toggle-create-project.window="open = !open"
             x-show="open" x-cloak
             class="border-2 border-blue-400 bg-black p-5">
            <h2 class="text-xs font-bold text-blue-400 tracking-widest mb-4">// CREATE_NEW_PROJECT</h2>
            <form action="{{ route('admin.projects.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div class="col-span-2">
                        <label class="text-xs text-gray-600 block mb-1">PROJECT NAME *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full bg-black border border-gray-700 text-white text-xs px-3 py-2 focus:border-blue-400 focus:outline-none font-mono"
                               placeholder="My project">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 block mb-1">TEAM *</label>
                        <select name="team_id" required
                                class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-blue-400 focus:outline-none font-mono">
                            <option value="">Select team...</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                            @endforeach
                        </select>
                        @error('team_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 block mb-1">OWNER *</label>
                        <select name="created_by" required
                                class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-blue-400 focus:outline-none font-mono">
                            <option value="">Select user...</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ old('created_by') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                        @error('created_by')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 block mb-1">STATUS</label>
                        <select name="status"
                                class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-blue-400 focus:outline-none font-mono">
                            <option value="active"    {{ old('status') === 'active'    ? 'selected' : '' }}>ACTIVE</option>
                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>COMPLETED</option>
                            <option value="archived"  {{ old('status') === 'archived'  ? 'selected' : '' }}>ARCHIVED</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 block mb-1">START DATE</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                               class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-blue-400 focus:outline-none font-mono">
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 block mb-1">END DATE</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}"
                               class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-blue-400 focus:outline-none font-mono">
                    </div>
                    <div class="col-span-3">
                        <label class="text-xs text-gray-600 block mb-1">DESCRIPTION</label>
                        <input type="text" name="description" value="{{ old('description') }}"
                               class="w-full bg-black border border-gray-700 text-white text-xs px-3 py-2 focus:border-blue-400 focus:outline-none font-mono"
                               placeholder="Optional description">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn-brutal-sm">CREATE</button>
                        <button type="button" @click="open = false"
                                class="text-xs border border-gray-700 px-3 py-2 text-gray-500 hover:text-white transition-colors">CANCEL</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Projects table --}}
        <div class="border-2 border-gray-800 bg-black">

            <div class="grid grid-cols-12 gap-2 px-4 py-2 border-b border-gray-800 text-xs text-gray-600 tracking-widest uppercase">
                <div class="col-span-3">PROJECT</div>
                <div class="col-span-2 hidden md:block">TEAM</div>
                <div class="col-span-2 hidden md:block">OWNER</div>
                <div class="col-span-1 hidden md:block">SPRINTS</div>
                <div class="col-span-1 hidden md:block">TASKS</div>
                <div class="col-span-1">STATUS</div>
                <div class="col-span-2 text-right">ACTIONS</div>
            </div>

            @forelse($projects as $project)
                <div x-data="{ sprintOpen: false, editOpen: false }">

                    {{-- Main row --}}
                    <div class="grid grid-cols-12 gap-2 px-4 py-3 border-b border-gray-900 items-center hover:bg-gray-950 transition-colors">

                        {{-- Project --}}
                        <div class="col-span-3 flex items-center gap-2 min-w-0">
                            <span class="w-2 h-2 rounded-full shrink-0" style="background:{{ $project->color }}"></span>
                            <p class="text-xs font-bold text-white truncate">{{ $project->name }}</p>
                        </div>

                        {{-- Team --}}
                        <div class="col-span-2 hidden md:block text-xs text-gray-600 truncate">
                            {{ $project->team?->name ?? '—' }}
                        </div>

                        {{-- Owner --}}
                        <div class="col-span-2 hidden md:block text-xs text-gray-500 truncate">
                            {{ $project->creator?->name ?? '—' }}
                        </div>

                        {{-- Sprints --}}
                        <div class="col-span-1 hidden md:block text-xs text-gray-600 font-mono">
                            {{ $project->sprints_count }}
                        </div>

                        {{-- Tasks --}}
                        <div class="col-span-1 hidden md:block text-xs text-gray-600 font-mono">
                            {{ $project->tasks_count }}
                        </div>

                        {{-- Status --}}
                        <div class="col-span-1">
                            <span class="text-xs border px-1.5 py-0.5 font-mono
                                {{ $project->status === 'active' ? 'border-neon-green text-neon-green' :
                                   ($project->status === 'completed' ? 'border-blue-400 text-blue-400' : 'border-gray-600 text-gray-600') }}">
                                {{ strtoupper($project->status) }}
                            </span>
                        </div>

                        {{-- Actions --}}
                        <div class="col-span-2 flex gap-1 justify-end flex-wrap">
                            <button @click="editOpen = !editOpen"
                                    class="text-xs border border-gray-700 px-2 py-1 text-gray-500 hover:border-white hover:text-white transition-colors font-mono">
                                EDIT
                            </button>
                            <button @click="sprintOpen = !sprintOpen"
                                    class="text-xs border border-gray-700 px-2 py-1 text-gray-500 hover:border-blue-400 hover:text-blue-400 transition-colors font-mono">
                                SPRINTS
                            </button>
                            <form action="{{ route('admin.projects.destroy', $project) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Delete project {{ addslashes($project->name) }} and all its data?')"
                                        class="text-xs border border-gray-800 px-2 py-1 text-gray-700 hover:border-red-500 hover:text-red-500 transition-colors font-mono">
                                    DEL
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Inline edit project --}}
                    <div x-show="editOpen" x-cloak class="px-5 py-4 border-b border-gray-900 bg-gray-950">
                        <form action="{{ route('admin.projects.update', $project) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                <div class="col-span-2">
                                    <label class="text-xs text-gray-600 block mb-1">NAME</label>
                                    <input type="text" name="name" value="{{ $project->name }}" required
                                           class="w-full bg-black border border-gray-700 text-white text-xs px-3 py-2 focus:border-neon-green focus:outline-none font-mono">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 block mb-1">STATUS</label>
                                    <select name="status"
                                            class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-neon-green focus:outline-none font-mono">
                                        <option value="active"    {{ $project->status === 'active'    ? 'selected' : '' }}>ACTIVE</option>
                                        <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>COMPLETED</option>
                                        <option value="archived"  {{ $project->status === 'archived'  ? 'selected' : '' }}>ARCHIVED</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 block mb-1">START</label>
                                    <input type="date" name="start_date" value="{{ $project->start_date?->format('Y-m-d') }}"
                                           class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-neon-green focus:outline-none font-mono">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 block mb-1">END</label>
                                    <input type="date" name="end_date" value="{{ $project->end_date?->format('Y-m-d') }}"
                                           class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-neon-green focus:outline-none font-mono">
                                </div>
                                <div class="col-span-2">
                                    <label class="text-xs text-gray-600 block mb-1">DESCRIPTION</label>
                                    <input type="text" name="description" value="{{ $project->description }}"
                                           class="w-full bg-black border border-gray-700 text-white text-xs px-3 py-2 focus:border-neon-green focus:outline-none font-mono">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 block mb-1">COLOR</label>
                                    <input type="color" name="color" value="{{ $project->color }}"
                                           class="w-full h-9 bg-black border border-gray-700 px-1 cursor-pointer">
                                </div>
                            </div>
                            <div class="mt-3 flex gap-2">
                                <button type="submit" class="btn-brutal-sm">SAVE</button>
                                <button type="button" @click="editOpen = false"
                                        class="text-xs border border-gray-700 px-3 py-1 text-gray-500 hover:text-white transition-colors">CANCEL</button>
                            </div>
                        </form>
                    </div>

                    {{-- Sprints panel --}}
                    <div x-show="sprintOpen" x-cloak class="border-b border-gray-900">

                        @php $projectSprints = $project->sprints()->orderBy('start_date')->get(); @endphp

                        @if($projectSprints->isEmpty())
                            <div class="px-8 py-3 text-xs text-gray-700 font-mono">NO_SPRINTS_YET</div>
                        @else
                            @foreach($projectSprints as $sprint)
                                <div x-data="{ editSprint: false }" class="border-b border-gray-900 last:border-0">
                                    <div class="flex items-center justify-between px-8 py-2">
                                        <div class="flex items-center gap-3">
                                            <span class="text-gray-800">&mdash;</span>
                                            <p class="text-xs text-gray-400 font-bold">{{ $sprint->name }}</p>
                                            <span class="text-xs border px-1 py-0.5 font-mono
                                                {{ $sprint->status === 'active' ? 'border-neon-green text-neon-green' :
                                                   ($sprint->status === 'completed' ? 'border-blue-400 text-blue-400' : 'border-gray-700 text-gray-600') }}">
                                                {{ strtoupper($sprint->status) }}
                                            </span>
                                            <span class="text-xs text-gray-700 font-mono">
                                                {{ $sprint->start_date->format('d M') }} &rarr; {{ $sprint->end_date->format('d M Y') }}
                                            </span>
                                        </div>
                                        <div class="flex gap-2">
                                            <button @click="editSprint = !editSprint"
                                                    class="text-xs border border-gray-800 px-2 py-1 text-gray-700 hover:border-white hover:text-white transition-colors font-mono">
                                                EDIT
                                            </button>
                                            <form action="{{ route('admin.sprints.destroy', $sprint) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Delete sprint {{ addslashes($sprint->name) }}?')"
                                                        class="text-xs border border-gray-800 px-2 py-1 text-gray-700 hover:border-red-500 hover:text-red-500 transition-colors font-mono">
                                                    DEL
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div x-show="editSprint" x-cloak class="px-8 pb-3 bg-gray-950">
                                        <form action="{{ route('admin.sprints.update', $sprint) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                                <div class="col-span-2">
                                                    <label class="text-xs text-gray-600 block mb-1">NAME</label>
                                                    <input type="text" name="name" value="{{ $sprint->name }}" required
                                                           class="w-full bg-black border border-gray-700 text-white text-xs px-3 py-2 focus:border-neon-green focus:outline-none font-mono">
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-600 block mb-1">STATUS</label>
                                                    <select name="status"
                                                            class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-neon-green focus:outline-none font-mono">
                                                        <option value="planning"  {{ $sprint->status === 'planning'  ? 'selected' : '' }}>PLANNING</option>
                                                        <option value="active"    {{ $sprint->status === 'active'    ? 'selected' : '' }}>ACTIVE</option>
                                                        <option value="completed" {{ $sprint->status === 'completed' ? 'selected' : '' }}>COMPLETED</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-600 block mb-1">START</label>
                                                    <input type="date" name="start_date" value="{{ $sprint->start_date->format('Y-m-d') }}" required
                                                           class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-neon-green focus:outline-none font-mono">
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-600 block mb-1">END</label>
                                                    <input type="date" name="end_date" value="{{ $sprint->end_date->format('Y-m-d') }}" required
                                                           class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-neon-green focus:outline-none font-mono">
                                                </div>
                                            </div>
                                            <div class="mt-2 flex gap-2">
                                                <button type="submit" class="btn-brutal-sm">SAVE</button>
                                                <button type="button" @click="editSprint = false"
                                                        class="text-xs border border-gray-700 px-3 py-1 text-gray-500 hover:text-white transition-colors">CANCEL</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        {{-- Add sprint --}}
                        <div x-data="{ addSprint: false }" class="border-t border-gray-900">
                            <button @click="addSprint = !addSprint"
                                    class="w-full px-8 py-2 text-left text-xs text-gray-700 hover:text-blue-400 transition-colors font-mono">
                                + ADD_SPRINT
                            </button>
                            <div x-show="addSprint" x-cloak class="px-8 pb-3 bg-gray-950">
                                <form action="{{ route('admin.sprints.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                        <div class="col-span-2">
                                            <label class="text-xs text-gray-600 block mb-1">NAME *</label>
                                            <input type="text" name="name" required placeholder="Sprint name"
                                                   class="w-full bg-black border border-gray-700 text-white text-xs px-3 py-2 focus:border-blue-400 focus:outline-none font-mono">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-600 block mb-1">STATUS</label>
                                            <select name="status"
                                                    class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-blue-400 focus:outline-none font-mono">
                                                <option value="planning">PLANNING</option>
                                                <option value="active">ACTIVE</option>
                                                <option value="completed">COMPLETED</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-600 block mb-1">START *</label>
                                            <input type="date" name="start_date" required
                                                   class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-blue-400 focus:outline-none font-mono">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-600 block mb-1">END *</label>
                                            <input type="date" name="end_date" required
                                                   class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-blue-400 focus:outline-none font-mono">
                                        </div>
                                    </div>
                                    <div class="mt-2 flex gap-2">
                                        <button type="submit" class="btn-brutal-sm">CREATE_SPRINT</button>
                                        <button type="button" @click="addSprint = false"
                                                class="text-xs border border-gray-700 px-3 py-1 text-gray-500 hover:text-white transition-colors">CANCEL</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="px-4 py-12 text-center">
                    <p class="text-xs text-gray-700">NO_PROJECTS_FOUND</p>
                </div>
            @endforelse
        </div>

        @if($projects->hasPages())
            <div class="mt-4">{{ $projects->links() }}</div>
        @endif

    </div>
</x-app-layout>
