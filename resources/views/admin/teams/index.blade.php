<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-white text-sm transition-colors">&lt; ADMIN</a>
                <span class="text-gray-700">/</span>
                <h1 class="text-xl font-bold text-white uppercase">TEAMS</h1>
                <span class="text-xs border border-yellow-500 text-yellow-500 px-2 py-0.5">{{ $teams->total() }} TOTAL</span>
            </div>

            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search team name..."
                       class="bg-black border border-gray-700 text-white text-xs px-3 py-1.5 focus:border-neon-green focus:outline-none font-mono w-56">
                <button type="submit" class="btn-brutal-sm">SEARCH</button>
                @if(request('search'))
                    <a href="{{ route('admin.teams.index') }}" class="text-xs border border-gray-700 px-3 py-1.5 text-gray-500 hover:text-white hover:border-white transition-colors">CLEAR</a>
                @endif
            </form>
        </div>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto">

        <div class="border-2 border-gray-800 bg-black">

            {{-- Table Header --}}
            <div class="grid grid-cols-12 gap-4 px-4 py-2 border-b border-gray-800 text-xs text-gray-600 tracking-widest uppercase">
                <div class="col-span-3">TEAM</div>
                <div class="col-span-2 hidden md:block">OWNER</div>
                <div class="col-span-1 hidden md:block">MBR</div>
                <div class="col-span-1 hidden md:block">PROJ</div>
                <div class="col-span-2 hidden md:block">PLAN</div>
                <div class="col-span-3 text-right">ACTIONS</div>
            </div>

            @forelse($teams as $team)
                <div class="grid grid-cols-12 gap-4 px-4 py-3 border-b border-gray-900 last:border-0 items-center">

                    {{-- Team --}}
                    <div class="col-span-3">
                        <p class="text-xs font-bold text-white">{{ $team->name }}</p>
                        @if($team->personal_team)
                            <span class="text-xs text-gray-700">personal</span>
                        @endif
                        <p class="text-xs text-gray-700">{{ $team->created_at->format('d M Y') }}</p>
                    </div>

                    {{-- Owner --}}
                    <div class="col-span-2 hidden md:block">
                        @if($team->owner)
                            <p class="text-xs text-white truncate">{{ $team->owner->name }}</p>
                            <p class="text-xs text-gray-600 truncate">{{ $team->owner->email }}</p>
                        @else
                            <span class="text-xs text-gray-700">—</span>
                        @endif
                    </div>

                    {{-- Members --}}
                    <div class="col-span-1 hidden md:block">
                        <span class="text-xs text-gray-400">{{ $team->users_count }}</span>
                    </div>

                    {{-- Projects --}}
                    <div class="col-span-1 hidden md:block">
                        <span class="text-xs text-gray-400">{{ $team->projects_count }}</span>
                    </div>

                    {{-- Plan --}}
                    <div class="col-span-2 hidden md:block">
                        @php
                            $planColor = match($team->plan) {
                                'pro'  => 'border-neon-green text-neon-green',
                                'team' => 'border-yellow-500 text-yellow-500',
                                default => 'border-gray-700 text-gray-500',
                            };
                        @endphp
                        <span class="text-xs border px-2 py-0.5 font-bold {{ $planColor }}">
                            {{ strtoupper($team->plan) }}
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="col-span-3 flex gap-2 justify-end items-center flex-wrap">

                        {{-- Change Plan --}}
                        <form action="{{ route('admin.teams.plan', $team) }}" method="POST" class="flex gap-1 items-center">
                            @csrf @method('PATCH')
                            <select name="plan"
                                    class="bg-black border border-gray-700 text-white text-xs px-1.5 py-1 focus:border-neon-green focus:outline-none font-mono">
                                <option value="free"  {{ $team->plan === 'free'  ? 'selected' : '' }}>FREE</option>
                                <option value="pro"   {{ $team->plan === 'pro'   ? 'selected' : '' }}>PRO</option>
                                <option value="team"  {{ $team->plan === 'team'  ? 'selected' : '' }}>TEAM</option>
                            </select>
                            <button type="submit"
                                    class="text-xs border border-gray-700 px-2 py-1 text-gray-400 hover:border-neon-green hover:text-neon-green transition-colors font-mono">
                                SET
                            </button>
                        </form>

                        <form action="{{ route('admin.teams.destroy', $team) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Delete team \'{{ addslashes($team->name) }}\' and ALL its projects/tasks? This cannot be undone.')"
                                    class="text-xs border border-gray-800 px-2 py-1 text-gray-700 hover:border-red-500 hover:text-red-500 transition-colors font-mono">
                                DEL
                            </button>
                        </form>
                    </div>

                </div>
            @empty
                <div class="px-4 py-12 text-center">
                    <p class="text-xs text-gray-700">NO_TEAMS_FOUND</p>
                </div>
            @endforelse
        </div>

        @if($teams->hasPages())
            <div class="mt-4">
                {{ $teams->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
