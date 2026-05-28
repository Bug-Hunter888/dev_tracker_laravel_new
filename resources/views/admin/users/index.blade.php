<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-white text-sm transition-colors">&lt; ADMIN</a>
                <span class="text-gray-700">/</span>
                <h1 class="text-xl font-bold text-white uppercase">USERS</h1>
                <span class="text-xs border border-red-500 text-red-500 px-2 py-0.5">{{ $users->total() }} TOTAL</span>
            </div>

            <div class="flex items-center gap-3">
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search name / email..."
                           class="bg-black border border-gray-700 text-white text-xs px-3 py-1.5 focus:border-neon-green focus:outline-none font-mono w-56">
                    <select name="filter"
                            class="bg-black border border-gray-700 text-white text-xs px-2 py-1.5 focus:border-neon-green focus:outline-none font-mono">
                        <option value="">ALL</option>
                        <option value="admin"    {{ request('filter') === 'admin'    ? 'selected' : '' }}>ADMINS</option>
                        <option value="disabled" {{ request('filter') === 'disabled' ? 'selected' : '' }}>DISABLED</option>
                    </select>
                    <button type="submit" class="btn-brutal-sm">SEARCH</button>
                    @if(request()->hasAny(['search','filter']))
                        <a href="{{ route('admin.users.index') }}" class="text-xs border border-gray-700 px-3 py-1.5 text-gray-500 hover:text-white hover:border-white transition-colors">CLEAR</a>
                    @endif
                </form>

                <button x-data x-on:click="$dispatch('toggle-create-user')"
                        class="text-xs border border-neon-green text-neon-green px-3 py-1.5 hover:bg-neon-green hover:text-black transition-colors font-mono">
                    + NEW_USER
                </button>
            </div>
        </div>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-4">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="border border-neon-green px-4 py-2 text-xs text-neon-green font-mono">
                &gt; {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="border border-red-500 px-4 py-2 text-xs text-red-500 font-mono">
                &gt; {{ session('error') }}
            </div>
        @endif

        {{-- Create User Form --}}
        <div x-data="{ open: {{ $errors->any() ? 'true' : 'false' }} }"
             x-on:toggle-create-user.window="open = !open"
             x-show="open" x-cloak
             class="border-2 border-neon-green bg-black p-5">
            <h2 class="text-xs font-bold text-neon-green tracking-widest mb-4">// CREATE_NEW_USER</h2>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 block mb-1">NAME *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full bg-black border border-gray-700 text-white text-xs px-3 py-2 focus:border-neon-green focus:outline-none font-mono"
                               placeholder="Full name">
                        @error('name')<p class="text-red-500 text-xs mt-1">&gt; {{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 block mb-1">EMAIL *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full bg-black border border-gray-700 text-white text-xs px-3 py-2 focus:border-neon-green focus:outline-none font-mono"
                               placeholder="user@example.com">
                        @error('email')<p class="text-red-500 text-xs mt-1">&gt; {{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 block mb-1">PASSWORD *</label>
                        <input type="password" name="password" required
                               class="w-full bg-black border border-gray-700 text-white text-xs px-3 py-2 focus:border-neon-green focus:outline-none font-mono"
                               placeholder="Min 8 characters">
                        @error('password')<p class="text-red-500 text-xs mt-1">&gt; {{ $message }}</p>@enderror
                    </div>
                    <div class="flex flex-col justify-between">
                        <label class="flex items-center gap-2 cursor-pointer mt-1">
                            <input type="checkbox" name="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}
                                   class="accent-neon-green">
                            <span class="text-xs text-gray-400 font-mono">GRANT_ADMIN</span>
                        </label>
                        <div class="flex gap-2 mt-3">
                            <button type="submit" class="btn-brutal-sm">CREATE_USER</button>
                            <button type="button" @click="open = false"
                                    class="text-xs border border-gray-700 px-3 py-1 text-gray-500 hover:text-white transition-colors">
                                CANCEL
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Users table --}}
        <div class="border-2 border-gray-800 bg-black">

            <div class="grid grid-cols-12 gap-2 px-4 py-2 border-b border-gray-800 text-xs text-gray-600 tracking-widest uppercase">
                <div class="col-span-3">USER</div>
                <div class="col-span-2 hidden md:block">REGISTERED</div>
                <div class="col-span-2 hidden md:block">LAST_LOGIN</div>
                <div class="col-span-1 hidden md:block">PROJ</div>
                <div class="col-span-2">STATUS</div>
                <div class="col-span-2 text-right">ACTIONS</div>
            </div>

            @forelse($users as $user)
                <div x-data="{ open: false }" class="{{ $user->is_banned ? 'opacity-60' : '' }}">

                    {{-- Main row --}}
                    <div class="grid grid-cols-12 gap-2 px-4 py-3 border-b border-gray-900 items-center hover:bg-gray-950 transition-colors cursor-pointer"
                         @click="open = !open">

                        {{-- User info --}}
                        <div class="col-span-3 flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 bg-gray-800 border border-gray-700 flex items-center justify-center text-xs font-bold shrink-0">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-white truncate">{{ $user->name }}</p>
                                <p class="text-xs text-gray-600 truncate">{{ $user->email }}</p>
                            </div>
                        </div>

                        {{-- Registered --}}
                        <div class="col-span-2 hidden md:block text-xs text-gray-600 font-mono">
                            {{ $user->created_at->format('d M Y') }}
                        </div>

                        {{-- Last login --}}
                        <div class="col-span-2 hidden md:block text-xs font-mono">
                            @if($user->last_login_at)
                                <span class="text-gray-500">{{ $user->last_login_at->diffForHumans() }}</span>
                            @else
                                <span class="text-gray-800">NEVER</span>
                            @endif
                        </div>

                        {{-- Projects count --}}
                        <div class="col-span-1 hidden md:block text-xs text-gray-600 font-mono">
                            {{ $user->created_projects_count }}
                        </div>

                        {{-- Status --}}
                        <div class="col-span-2 flex flex-wrap gap-1">
                            @if($user->is_admin)
                                <span class="text-xs border border-neon-green text-neon-green px-1.5 py-0.5">ADMIN</span>
                            @endif
                            @if($user->is_banned)
                                <span class="text-xs border border-red-500 text-red-500 px-1.5 py-0.5">DISABLED</span>
                            @endif
                            @if(!$user->is_admin && !$user->is_banned)
                                <span class="text-xs text-gray-700">ACTIVE</span>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="col-span-2 flex gap-1 justify-end flex-wrap" @click.stop>

                            <a href="{{ route('admin.users.show', $user) }}"
                               class="text-xs border border-gray-700 px-2 py-1 text-gray-500 hover:border-white hover:text-white transition-colors font-mono">
                                VIEW
                            </a>

                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.ban', $user) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="text-xs border px-2 py-1 transition-colors font-mono
                                            {{ $user->is_banned
                                                ? 'border-neon-green text-neon-green hover:bg-neon-green hover:text-black'
                                                : 'border-yellow-700 text-yellow-700 hover:border-yellow-500 hover:text-yellow-500' }}">
                                        {{ $user->is_banned ? 'ENABLE' : 'DISABLE' }}
                                    </button>
                                </form>
                            @endif

                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.makeAdmin', $user) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="text-xs border px-2 py-1 transition-colors font-mono
                                            {{ $user->is_admin
                                                ? 'border-gray-600 text-gray-600 hover:border-red-500 hover:text-red-500'
                                                : 'border-gray-700 text-gray-600 hover:border-neon-green hover:text-neon-green' }}"
                                            title="{{ $user->is_admin ? 'Remove admin' : 'Make admin' }}">
                                        {{ $user->is_admin ? 'RMADM' : 'MKADM' }}
                                    </button>
                                </form>
                            @endif

                            @if($user->id !== auth()->id() && !$user->is_admin)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Delete {{ addslashes($user->name) }} and all their data? This is irreversible.')"
                                            class="text-xs border border-gray-800 px-2 py-1 text-gray-700 hover:border-red-500 hover:text-red-500 transition-colors font-mono">
                                        DEL
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>

                    {{-- Expandable: user's projects --}}
                    <div x-show="open" x-cloak
                         class="border-b border-gray-900 bg-gray-950 px-6 py-4">

                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs text-gray-600 tracking-widest font-mono">// PROJECTS_BY_{{ strtoupper($user->name) }}</p>
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="text-xs text-gray-700 hover:text-white transition-colors">FULL PROFILE &rarr;</a>
                        </div>

                        @php $userProjects = $user->createdProjects()->with('sprints')->withCount(['tasks','sprints'])->latest()->get(); @endphp

                        @if($userProjects->isEmpty())
                            <p class="text-xs text-gray-800 font-mono">NO_PROJECTS_FOUND</p>
                        @else
                            <div class="space-y-2">
                                @foreach($userProjects as $project)
                                    <div class="border border-gray-800 p-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <span class="w-2 h-2 rounded-full shrink-0" style="background:{{ $project->color }}"></span>
                                                <p class="text-xs font-bold text-white">{{ $project->name }}</p>
                                                <span class="text-xs border px-1.5 py-0.5 font-mono
                                                    {{ $project->status === 'active' ? 'border-neon-green text-neon-green' :
                                                       ($project->status === 'completed' ? 'border-blue-400 text-blue-400' : 'border-gray-600 text-gray-600') }}">
                                                    {{ strtoupper($project->status) }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-4 text-xs text-gray-600 font-mono">
                                                <span>{{ $project->sprints_count }} sprints</span>
                                                <span>{{ $project->tasks_count }} tasks</span>
                                                <span>{{ $project->created_at->format('d M Y') }}</span>
                                            </div>
                                        </div>

                                        @if($project->sprints->isNotEmpty())
                                            <div class="mt-2 pl-5 space-y-1">
                                                @foreach($project->sprints as $sprint)
                                                    <div class="flex items-center gap-3 text-xs text-gray-700 font-mono">
                                                        <span class="text-gray-800">&mdash;</span>
                                                        <span class="text-gray-500">{{ $sprint->name }}</span>
                                                        <span class="border px-1 py-0.5
                                                            {{ $sprint->status === 'active' ? 'border-neon-green text-neon-green' :
                                                               ($sprint->status === 'completed' ? 'border-blue-400 text-blue-400' : 'border-gray-700 text-gray-600') }}">
                                                            {{ strtoupper($sprint->status) }}
                                                        </span>
                                                        <span>{{ $sprint->start_date->format('d M') }} &rarr; {{ $sprint->end_date->format('d M Y') }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            @empty
                <div class="px-4 py-12 text-center">
                    <p class="text-xs text-gray-700">NO_USERS_FOUND</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
