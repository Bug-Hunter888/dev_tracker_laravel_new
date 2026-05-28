<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-white text-sm transition-colors">&lt; USERS</a>
                <span class="text-gray-700">/</span>
                <h1 class="text-xl font-bold text-white uppercase">{{ $user->name }}</h1>
                @if($user->is_admin)
                    <span class="text-xs border border-neon-green text-neon-green px-2 py-0.5">ADMIN</span>
                @endif
                @if($user->is_banned)
                    <span class="text-xs border border-red-500 text-red-500 px-2 py-0.5">DISABLED</span>
                @endif
            </div>

            {{-- Quick actions --}}
            @if($user->id !== auth()->id())
                <div class="flex gap-2">
                    <form action="{{ route('admin.users.ban', $user) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="text-xs border px-3 py-1.5 transition-colors font-mono
                                {{ $user->is_banned
                                    ? 'border-neon-green text-neon-green hover:bg-neon-green hover:text-black'
                                    : 'border-yellow-700 text-yellow-700 hover:border-yellow-500 hover:text-yellow-500' }}">
                            {{ $user->is_banned ? 'ENABLE_ACCOUNT' : 'DISABLE_ACCOUNT' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.users.resetPassword', $user) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="text-xs border border-gray-700 px-3 py-1.5 text-gray-500 hover:border-white hover:text-white transition-colors font-mono">
                            RESET_PWD
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        {{-- Flash --}}
        @if(session('success'))
            <div class="border border-neon-green px-4 py-2 text-xs text-neon-green font-mono">&gt; {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="border border-red-500 px-4 py-2 text-xs text-red-500 font-mono">&gt; {{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left: User info + edit --}}
            <div class="lg:col-span-1 space-y-4">

                {{-- User card --}}
                <div class="border-2 border-gray-800 bg-black p-5">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-gray-800 border border-gray-700 flex items-center justify-center text-lg font-bold">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">{{ $user->name }}</p>
                            <p class="text-xs text-gray-600">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-xs font-mono">
                        <div class="flex justify-between">
                            <span class="text-gray-600">REGISTERED</span>
                            <span class="text-gray-400">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">LAST_LOGIN</span>
                            <span class="text-gray-400">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'NEVER' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">ROLE</span>
                            <span class="{{ $user->is_admin ? 'text-neon-green' : 'text-gray-400' }}">
                                {{ $user->is_admin ? 'ADMIN' : 'USER' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">STATUS</span>
                            <span class="{{ $user->is_banned ? 'text-red-500' : 'text-neon-green' }}">
                                {{ $user->is_banned ? 'DISABLED' : 'ACTIVE' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Task stats --}}
                <div class="border-2 border-gray-800 bg-black p-5">
                    <p class="text-xs text-gray-600 tracking-widest mb-3">TASK_STATS</p>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div>
                            <p class="text-2xl font-bold text-white">{{ $taskStats['created'] }}</p>
                            <p class="text-xs text-gray-700 mt-1">CREATED</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-blue-400">{{ $taskStats['assigned'] }}</p>
                            <p class="text-xs text-gray-700 mt-1">ASSIGNED</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-neon-green">{{ $taskStats['done'] }}</p>
                            <p class="text-xs text-gray-700 mt-1">DONE</p>
                        </div>
                    </div>
                </div>

                {{-- Edit user form --}}
                @if($user->id !== auth()->id())
                    <div x-data="{ open: false }" class="border-2 border-gray-800 bg-black">
                        <button @click="open = !open"
                                class="w-full px-5 py-3 text-left text-xs font-bold text-gray-500 hover:text-white tracking-widest flex items-center justify-between transition-colors">
                            <span>// EDIT_USER</span>
                            <span x-text="open ? '▲' : '▼'" class="text-gray-700"></span>
                        </button>

                        <div x-show="open" x-cloak class="px-5 pb-5 border-t border-gray-900">
                            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-3 mt-4">
                                @csrf @method('PATCH')
                                <div>
                                    <label class="text-xs text-gray-600 block mb-1">NAME</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                           class="w-full bg-black border border-gray-700 text-white text-xs px-3 py-2 focus:border-neon-green focus:outline-none font-mono">
                                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600 block mb-1">EMAIL</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                           class="w-full bg-black border border-gray-700 text-white text-xs px-3 py-2 focus:border-neon-green focus:outline-none font-mono">
                                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_admin" value="1"
                                               {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                                               class="accent-neon-green">
                                        <span class="text-xs text-gray-400 font-mono">IS_ADMIN</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_banned" value="1"
                                               {{ old('is_banned', $user->is_banned) ? 'checked' : '' }}
                                               class="accent-red-500">
                                        <span class="text-xs text-gray-400 font-mono">IS_DISABLED</span>
                                    </label>
                                </div>
                                <button type="submit" class="btn-brutal-sm w-full">SAVE_CHANGES</button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Danger zone --}}
                @if($user->id !== auth()->id() && !$user->is_admin)
                    <div class="border-2 border-red-900 bg-black p-4 space-y-2">
                        <p class="text-xs text-red-700 font-bold tracking-widest">// DANGER_ZONE</p>

                        <form action="{{ route('admin.users.deleteData', $user) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Delete ALL projects, sprints and tasks for {{ addslashes($user->name) }}? User account is kept. This is irreversible.')"
                                    class="w-full text-xs border border-red-900 px-3 py-2 text-red-700 hover:border-red-500 hover:text-red-500 transition-colors font-mono text-left">
                                DELETE_USER_DATA (keep account)
                            </button>
                        </form>

                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Permanently delete {{ addslashes($user->name) }} AND all their data? This cannot be undone.')"
                                    class="w-full text-xs border border-red-900 px-3 py-2 text-red-500 hover:border-red-400 hover:bg-red-950 transition-colors font-mono text-left">
                                DELETE_USER_ACCOUNT (irreversible)
                            </button>
                        </form>
                    </div>
                @endif

            </div>

            {{-- Right: Teams + Projects & Sprints + Assigned Tasks --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Teams --}}
                <div>
                    <h2 class="text-xs font-bold text-gray-600 tracking-widest mb-3">TEAM_MEMBERSHIPS</h2>
                    @if($teams->isEmpty())
                        <div class="border-2 border-gray-800 bg-black px-6 py-6 text-center">
                            <p class="text-xs text-gray-700">NO_TEAMS_FOUND</p>
                        </div>
                    @else
                        <div class="space-y-2">
                            @foreach($teams as $team)
                                <div class="border border-gray-800 bg-black px-4 py-3 flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-bold text-white">{{ $team->name }}</p>
                                        <p class="text-xs text-gray-600 mt-0.5">Owner: {{ $team->owner->name }}</p>
                                    </div>
                                    @if($team->user_id === $user->id)
                                        <span class="text-xs border border-neon-green text-neon-green px-2 py-0.5 font-mono">OWNER</span>
                                    @else
                                        <span class="text-xs border border-gray-700 text-gray-500 px-2 py-0.5 font-mono">MEMBER</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Assigned Tasks --}}
                @if($assignedTasks->isNotEmpty())
                <div>
                    <h2 class="text-xs font-bold text-gray-600 tracking-widest mb-3">RECENTLY_ASSIGNED_TASKS</h2>
                    <div class="border-2 border-gray-800 bg-black divide-y divide-gray-900">
                        @foreach($assignedTasks as $task)
                            <div class="px-4 py-3 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs text-white truncate font-bold">{{ $task->title }}</p>
                                    <p class="text-xs text-gray-600 mt-0.5">
                                        {{ $task->project?->name }}
                                        @if($task->sprint) · {{ $task->sprint->name }} @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="text-xs border px-1.5 py-0.5 font-mono
                                        {{ $task->status === 'done' ? 'border-neon-green text-neon-green' :
                                           ($task->status === 'doing' ? 'border-blue-400 text-blue-400' : 'border-gray-700 text-gray-600') }}">
                                        {{ strtoupper($task->status) }}
                                    </span>
                                    <span class="text-xs text-gray-700 font-mono capitalize">{{ $task->priority }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Created Projects & Sprints --}}
                <div>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-xs font-bold text-gray-600 tracking-widest">CREATED_PROJECTS</h2>
                    <span class="text-xs text-gray-700 font-mono">{{ $createdProjects->count() }} project(s)</span>
                </div>

                @if($createdProjects->isEmpty())
                    <div class="border-2 border-gray-800 bg-black px-6 py-10 text-center">
                        <p class="text-xs text-gray-700">NO_PROJECTS_FOUND</p>
                        <p class="text-xs text-gray-800 mt-1">This user has not created any projects.</p>
                    </div>
                @else
                    @foreach($createdProjects as $project)
                        <div x-data="{ sprintOpen: false, editOpen: false }" class="border-2 border-gray-800 bg-black">

                            {{-- Project header --}}
                            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-900">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full shrink-0" style="background:{{ $project->color }}"></span>
                                    <div>
                                        <p class="text-sm font-bold text-white">{{ $project->name }}</p>
                                        @if($project->team)
                                            <p class="text-xs text-gray-600">{{ $project->team->name }}</p>
                                        @endif
                                    </div>
                                    <span class="text-xs border px-1.5 py-0.5 font-mono ml-2
                                        {{ $project->status === 'active' ? 'border-neon-green text-neon-green' :
                                           ($project->status === 'completed' ? 'border-blue-400 text-blue-400' : 'border-gray-600 text-gray-600') }}">
                                        {{ strtoupper($project->status) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs text-gray-700 font-mono">{{ $project->sprints_count }} sprints · {{ $project->tasks_count }} tasks</span>
                                    <button @click="editOpen = !editOpen"
                                            class="text-xs border border-gray-700 px-2 py-1 text-gray-600 hover:border-white hover:text-white transition-colors font-mono">
                                        EDIT
                                    </button>
                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Delete project {{ addslashes($project->name) }} and all its sprints/tasks?')"
                                                class="text-xs border border-gray-800 px-2 py-1 text-gray-700 hover:border-red-500 hover:text-red-500 transition-colors font-mono">
                                            DEL
                                        </button>
                                    </form>
                                    <button @click="sprintOpen = !sprintOpen"
                                            class="text-xs text-gray-700 hover:text-white transition-colors font-mono"
                                            x-text="sprintOpen ? '▲' : '▼ SPRINTS'">
                                    </button>
                                </div>
                            </div>

                            {{-- Inline edit project --}}
                            <div x-show="editOpen" x-cloak class="px-5 py-4 border-b border-gray-900 bg-gray-950">
                                <form action="{{ route('admin.projects.update', $project) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
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
                                            <label class="text-xs text-gray-600 block mb-1">COLOR</label>
                                            <input type="color" name="color" value="{{ $project->color }}"
                                                   class="w-full h-9 bg-black border border-gray-700 px-1 cursor-pointer">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-600 block mb-1">START DATE</label>
                                            <input type="date" name="start_date" value="{{ $project->start_date?->format('Y-m-d') }}"
                                                   class="w-full bg-black border border-gray-700 text-white text-xs px-3 py-2 focus:border-neon-green focus:outline-none font-mono">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-600 block mb-1">END DATE</label>
                                            <input type="date" name="end_date" value="{{ $project->end_date?->format('Y-m-d') }}"
                                                   class="w-full bg-black border border-gray-700 text-white text-xs px-3 py-2 focus:border-neon-green focus:outline-none font-mono">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="text-xs text-gray-600 block mb-1">DESCRIPTION</label>
                                            <input type="text" name="description" value="{{ $project->description }}"
                                                   class="w-full bg-black border border-gray-700 text-white text-xs px-3 py-2 focus:border-neon-green focus:outline-none font-mono">
                                        </div>
                                    </div>
                                    <div class="flex gap-2 mt-3">
                                        <button type="submit" class="btn-brutal-sm">SAVE</button>
                                        <button type="button" @click="editOpen = false"
                                                class="text-xs border border-gray-700 px-3 py-1 text-gray-500 hover:text-white transition-colors">CANCEL</button>
                                    </div>
                                </form>
                            </div>

                            {{-- Sprints list --}}
                            <div x-show="sprintOpen" x-cloak>

                                @if($project->sprints->isEmpty())
                                    <div class="px-5 py-4 text-xs text-gray-700 font-mono border-b border-gray-900">NO_SPRINTS_YET</div>
                                @else
                                    @foreach($project->sprints as $sprint)
                                        <div x-data="{ editSprint: false }" class="border-b border-gray-900">
                                            <div class="flex items-center justify-between px-5 py-3">
                                                <div class="flex items-center gap-3">
                                                    <span class="text-gray-700 font-mono text-xs">&mdash;</span>
                                                    <p class="text-xs font-bold text-gray-300">{{ $sprint->name }}</p>
                                                    <span class="text-xs border px-1.5 py-0.5 font-mono
                                                        {{ $sprint->status === 'active' ? 'border-neon-green text-neon-green' :
                                                           ($sprint->status === 'completed' ? 'border-blue-400 text-blue-400' : 'border-gray-700 text-gray-600') }}">
                                                        {{ strtoupper($sprint->status) }}
                                                    </span>
                                                    <span class="text-xs text-gray-700 font-mono">
                                                        {{ $sprint->start_date?->format('d M') }} &rarr; {{ $sprint->end_date?->format('d M Y') }}
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

                                            {{-- Edit sprint inline --}}
                                            <div x-show="editSprint" x-cloak class="px-5 pb-4 bg-gray-950">
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
                                                            <input type="date" name="start_date" value="{{ $sprint->start_date?->format('Y-m-d') }}" required
                                                                   class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-neon-green focus:outline-none font-mono">
                                                        </div>
                                                        <div>
                                                            <label class="text-xs text-gray-600 block mb-1">END</label>
                                                            <input type="date" name="end_date" value="{{ $sprint->end_date?->format('Y-m-d') }}" required
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

                                {{-- Create sprint --}}
                                <div x-data="{ addSprint: false }" class="border-t border-gray-900">
                                    <button @click="addSprint = !addSprint"
                                            class="w-full px-5 py-3 text-left text-xs text-gray-700 hover:text-neon-green transition-colors font-mono">
                                        + ADD_SPRINT
                                    </button>
                                    <div x-show="addSprint" x-cloak class="px-5 pb-4 bg-gray-950">
                                        <form action="{{ route('admin.sprints.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                                <div class="col-span-2">
                                                    <label class="text-xs text-gray-600 block mb-1">NAME *</label>
                                                    <input type="text" name="name" required
                                                           placeholder="Sprint name"
                                                           class="w-full bg-black border border-gray-700 text-white text-xs px-3 py-2 focus:border-neon-green focus:outline-none font-mono">
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-600 block mb-1">STATUS</label>
                                                    <select name="status"
                                                            class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-neon-green focus:outline-none font-mono">
                                                        <option value="planning">PLANNING</option>
                                                        <option value="active">ACTIVE</option>
                                                        <option value="completed">COMPLETED</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-600 block mb-1">START *</label>
                                                    <input type="date" name="start_date" required
                                                           class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-neon-green focus:outline-none font-mono">
                                                </div>
                                                <div>
                                                    <label class="text-xs text-gray-600 block mb-1">END *</label>
                                                    <input type="date" name="end_date" required
                                                           class="w-full bg-black border border-gray-700 text-white text-xs px-2 py-2 focus:border-neon-green focus:outline-none font-mono">
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
                    @endforeach
                @endif
                </div>{{-- end CREATED_PROJECTS --}}

            </div>
        </div>
    </div>
</x-app-layout>
