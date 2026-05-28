<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h1 class="text-xl font-bold text-white uppercase">SUPER_ADMIN</h1>
                <span class="text-xs border border-red-500 text-red-500 px-2 py-0.5">RESTRICTED</span>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <a href="{{ route('admin.users.index') }}"
                   class="border border-gray-700 px-3 py-1.5 text-gray-400 hover:border-white hover:text-white transition-colors">
                    USERS
                </a>
                <a href="{{ route('admin.projects.index') }}"
                   class="border border-gray-700 px-3 py-1.5 text-gray-400 hover:border-white hover:text-white transition-colors">
                    PROJECTS
                </a>
                <a href="{{ route('admin.teams.index') }}"
                   class="border border-gray-700 px-3 py-1.5 text-gray-400 hover:border-white hover:text-white transition-colors">
                    TEAMS
                </a>
                <a href="{{ route('admin.tasks.index') }}"
                   class="border border-gray-700 px-3 py-1.5 text-gray-400 hover:border-white hover:text-white transition-colors">
                    TASKS
                </a>
                <a href="{{ route('admin.collaborators.index') }}"
                   class="border border-gray-700 px-3 py-1.5 text-gray-400 hover:border-white hover:text-white transition-colors">
                    COLLABORATORS
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-8">

        {{-- Stats Grid --}}
        <livewire:admin-stats />

        {{-- Quick Links --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <a href="{{ route('admin.users.index') }}"
               class="border-2 border-gray-700 bg-black p-6 hover:border-neon-green hover:bg-dark-gray transition-colors group">
                <div class="text-2xl font-bold text-neon-green mb-2 group-hover:text-white transition-colors">[USR]</div>
                <h3 class="text-sm font-bold uppercase mb-1">MANAGE_USERS</h3>
                <p class="text-xs text-gray-600">View all users, disable accounts, manage roles, delete data.</p>
                <p class="text-xs text-neon-green mt-3">{{ $totalUsers }} users &rarr;</p>
            </a>

            <a href="{{ route('admin.projects.index') }}"
               class="border-2 border-gray-700 bg-black p-6 hover:border-blue-400 hover:bg-dark-gray transition-colors group">
                <div class="text-2xl font-bold text-blue-400 mb-2 group-hover:text-white transition-colors">[PRJ]</div>
                <h3 class="text-sm font-bold uppercase mb-1">MANAGE_PROJECTS</h3>
                <p class="text-xs text-gray-600">Create, edit, delete projects and sprints across all teams.</p>
                <p class="text-xs text-blue-400 mt-3">{{ $totalProjects }} projects · {{ $totalSprints }} sprints &rarr;</p>
            </a>

            <a href="{{ route('admin.teams.index') }}"
               class="border-2 border-gray-700 bg-black p-6 hover:border-yellow-500 hover:bg-dark-gray transition-colors group">
                <div class="text-2xl font-bold text-yellow-500 mb-2 group-hover:text-white transition-colors">[TM]</div>
                <h3 class="text-sm font-bold uppercase mb-1">MANAGE_TEAMS</h3>
                <p class="text-xs text-gray-600">View all teams, member counts, change plans, delete teams.</p>
                <p class="text-xs text-yellow-500 mt-3">{{ $totalTeams }} teams &rarr;</p>
            </a>

            <a href="{{ route('admin.tasks.index') }}"
               class="border-2 border-gray-700 bg-black p-6 hover:border-white hover:bg-dark-gray transition-colors group">
                <div class="text-2xl font-bold text-gray-400 mb-2 group-hover:text-white transition-colors">[TSK]</div>
                <h3 class="text-sm font-bold uppercase mb-1">MANAGE_TASKS</h3>
                <p class="text-xs text-gray-600">Search, filter, edit status/priority, and delete any task.</p>
                <p class="text-xs text-gray-400 mt-3">{{ $totalTasks }} tasks &rarr;</p>
            </a>

        </div>

        {{-- Recent Users --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xs font-bold text-gray-600 tracking-widest">RECENTLY_JOINED</h2>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-gray-700 hover:text-white transition-colors">VIEW ALL &rarr;</a>
            </div>
            <div class="border-2 border-gray-800 bg-black">
                @foreach($recentUsers as $user)
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-900 last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 bg-gray-800 border border-gray-700 flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">{{ $user->name }}</p>
                                <p class="text-xs text-gray-600">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            @if($user->is_admin)
                                <span class="text-xs border border-neon-green text-neon-green px-2 py-0.5">ADMIN</span>
                            @endif
                            @if($user->is_banned)
                                <span class="text-xs border border-red-500 text-red-500 px-2 py-0.5">DISABLED</span>
                            @endif
                            <span class="text-xs text-gray-700">{{ $user->created_at->diffForHumans() }}</span>
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="text-xs border border-gray-800 px-2 py-1 text-gray-600 hover:border-white hover:text-white transition-colors">
                                VIEW
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="border-2 border-red-900 bg-black p-4">
            <p class="text-xs text-red-700 font-bold tracking-widest mb-1">// DANGER_ZONE</p>
            <p class="text-xs text-gray-700">
                Actions here are irreversible. Deleting a user removes all their owned projects, sprints, and tasks.
                Deleting a team removes all projects and tasks within it. Disabling a user immediately terminates their session.
            </p>
        </div>

    </div>
</x-app-layout>
