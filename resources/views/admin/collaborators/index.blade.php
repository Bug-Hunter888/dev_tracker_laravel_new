<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h1 class="text-xl font-bold text-white uppercase">COLLABORATORS</h1>
                <span class="text-xs border border-red-500 text-red-500 px-2 py-0.5">ADMIN</span>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <a href="{{ route('admin.dashboard') }}"
                   class="border border-gray-700 px-3 py-1.5 text-gray-400 hover:border-white hover:text-white transition-colors">
                    DASHBOARD
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="border border-gray-700 px-3 py-1.5 text-gray-400 hover:border-white hover:text-white transition-colors">
                    USERS
                </a>
                <a href="{{ route('admin.teams.index') }}"
                   class="border border-gray-700 px-3 py-1.5 text-gray-400 hover:border-white hover:text-white transition-colors">
                    TEAMS
                </a>
                <a href="{{ route('admin.collaborators.index') }}"
                   class="border border-neon-green px-3 py-1.5 text-neon-green">
                    COLLABORATORS
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-8">

        @if(session('success'))
            <div class="border border-neon-green px-4 py-2 text-xs text-neon-green font-mono">
                &gt; {{ session('success') }}
            </div>
        @endif

        {{-- ── Active Teams & Members ─────────────────────────────────────── --}}
        <div>
            <p class="text-xs text-gray-600 font-mono tracking-widest mb-3">// ACTIVE_WORKSPACES</p>
            <div class="space-y-4">
                @forelse($teams as $team)
                    <div class="border border-gray-800 bg-black p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-bold text-white font-mono">{{ $team->name }}</p>
                                <p class="text-xs text-gray-600 font-mono">
                                    OWNER: {{ $team->owner->name }} &bull;
                                    {{ $team->users->count() }} MEMBER(S) &bull;
                                    PLAN: {{ strtoupper($team->plan ?? 'free') }}
                                </p>
                            </div>
                            <span class="text-xs border border-neon-green text-neon-green px-2 py-0.5 font-mono">ACTIVE</span>
                        </div>

                        {{-- Members table --}}
                        <table class="w-full text-xs font-mono">
                            <thead>
                                <tr class="text-gray-700 border-b border-gray-900">
                                    <th class="text-left py-1.5">USER</th>
                                    <th class="text-left py-1.5">EMAIL</th>
                                    <th class="text-left py-1.5">ROLE</th>
                                    <th class="text-left py-1.5">JOINED</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Owner row --}}
                                <tr class="border-b border-gray-900/50">
                                    <td class="py-2 text-white">{{ $team->owner->name }}</td>
                                    <td class="py-2 text-gray-500">{{ $team->owner->email }}</td>
                                    <td class="py-2">
                                        <span class="border border-yellow-600 text-yellow-600 px-1.5 py-0.5">OWNER</span>
                                    </td>
                                    <td class="py-2 text-gray-600">{{ $team->created_at->format('d M Y') }}</td>
                                </tr>
                                {{-- Other members --}}
                                @foreach($team->users as $member)
                                    @php $role = $member->membership->role ?? 'editor'; @endphp
                                    <tr class="border-b border-gray-900/50">
                                        <td class="py-2 text-white">{{ $member->name }}</td>
                                        <td class="py-2 text-gray-500">{{ $member->email }}</td>
                                        <td class="py-2">
                                            @if($role === 'admin')
                                                <span class="border border-red-700 text-red-500 px-1.5 py-0.5">ADMIN</span>
                                            @elseif($role === 'viewer')
                                                <span class="border border-gray-600 text-gray-500 px-1.5 py-0.5">VIEWER</span>
                                            @else
                                                <span class="border border-blue-700 text-blue-400 px-1.5 py-0.5">MEMBER</span>
                                            @endif
                                        </td>
                                        <td class="py-2 text-gray-600">{{ $member->membership->created_at->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @empty
                    <div class="border border-gray-900 p-6 text-center">
                        <p class="text-xs text-gray-700 font-mono">NO_WORKSPACES_FOUND</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ── Pending Invitations ───────────────────────────────────────── --}}
        <div>
            <p class="text-xs text-gray-600 font-mono tracking-widest mb-3">// PENDING_INVITATIONS</p>
            @if($pendingInvites->isNotEmpty())
                <div class="border border-gray-800 bg-black">
                    <table class="w-full text-xs font-mono">
                        <thead>
                            <tr class="text-gray-700 border-b border-gray-800 px-5">
                                <th class="text-left px-5 py-2">EMAIL</th>
                                <th class="text-left px-5 py-2">WORKSPACE</th>
                                <th class="text-left px-5 py-2">ROLE</th>
                                <th class="text-left px-5 py-2">EXPIRES</th>
                                <th class="text-left px-5 py-2">SENT</th>
                                <th class="px-5 py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingInvites as $invite)
                                <tr class="border-b border-gray-900/50 hover:bg-gray-950">
                                    <td class="px-5 py-2.5 text-white">{{ $invite->email }}</td>
                                    <td class="px-5 py-2.5 text-gray-400">{{ $invite->team->name }}</td>
                                    <td class="px-5 py-2.5">
                                        <span class="border border-blue-700 text-blue-400 px-1.5 py-0.5">
                                            {{ strtoupper($invite->role ?? 'member') }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-2.5 text-gray-500">
                                        {{ $invite->expires_at ? $invite->expires_at->format('d M Y') : '—' }}
                                    </td>
                                    <td class="px-5 py-2.5 text-gray-600">{{ $invite->created_at->diffForHumans() }}</td>
                                    <td class="px-5 py-2.5">
                                        <form action="{{ route('admin.collaborators.revoke', $invite) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-700 hover:text-red-500 transition-colors tracking-widest"
                                                    onclick="return confirm('Revoke this invitation?')">
                                                REVOKE
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="border border-gray-900 p-4 text-center">
                    <p class="text-xs text-gray-700 font-mono">NO_PENDING_INVITATIONS</p>
                </div>
            @endif
        </div>

        {{-- ── Expired Invitations ───────────────────────────────────────── --}}
        @if($expiredInvites->isNotEmpty())
        <div>
            <p class="text-xs text-gray-600 font-mono tracking-widest mb-3">// EXPIRED_INVITATIONS</p>
            <div class="border border-gray-800 bg-black">
                <table class="w-full text-xs font-mono">
                    <thead>
                        <tr class="text-gray-700 border-b border-gray-800">
                            <th class="text-left px-5 py-2">EMAIL</th>
                            <th class="text-left px-5 py-2">WORKSPACE</th>
                            <th class="text-left px-5 py-2">EXPIRED</th>
                            <th class="px-5 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expiredInvites as $invite)
                            <tr class="border-b border-gray-900/50">
                                <td class="px-5 py-2.5 text-gray-500">{{ $invite->email }}</td>
                                <td class="px-5 py-2.5 text-gray-600">{{ $invite->team->name }}</td>
                                <td class="px-5 py-2.5 text-gray-600">
                                    {{ $invite->expires_at ? $invite->expires_at->format('d M Y') : '—' }}
                                </td>
                                <td class="px-5 py-2.5">
                                    <form action="{{ route('admin.collaborators.revoke', $invite) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-700 hover:text-red-500 transition-colors tracking-widest">
                                            REMOVE
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
