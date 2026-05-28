<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="team-id" content="{{ auth()->user()?->currentTeam?->id }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    <meta name="user-name" content="{{ auth()->user()?->name }}">
    <title>{{ config('app.name', 'DevTracker') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fira-code:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="bg-pitch-black text-white font-mono antialiased min-h-screen flex flex-col">

    <x-banner />

    <!-- Brutalist Nav -->
    <nav class="border-b-4 border-white bg-black sticky top-0 z-50" x-data="{ mobileOpen: false }">
        <div class="flex justify-between items-center px-6 py-4">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-neon-green tracking-tighter">
                    ./DevTracker_
                </a>
                @if(auth()->user()?->currentTeam)
                    <span class="text-xs text-gray-500 border border-gray-700 px-2 py-1 hidden md:inline">
                        {{ auth()->user()->currentTeam->name }}
                    </span>
                @endif
            </div>

            <div class="flex items-center gap-4">
                @unless(auth()->user()->is_admin)
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-neon-green transition-colors hidden md:inline">
                    BOARDS
                </a>

                <a href="{{ route('analytics') }}" class="text-sm text-gray-400 hover:text-neon-green transition-colors hidden md:inline">
                    ANALYTICS
                </a>

                <a href="{{ route('labels.index') }}" class="text-sm text-gray-400 hover:text-neon-green transition-colors hidden md:inline">
                    LABELS
                </a>
                @endunless

                {{-- Search --}}
                <form method="GET" action="{{ route('search') }}" class="hidden md:flex items-center">
                    <div class="flex items-center border border-gray-800 hover:border-gray-600 focus-within:border-neon-green transition-colors">
                        <span class="text-neon-green font-mono text-xs pl-2 opacity-50">/</span>
                        <input id="global-search"
                               type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="search..."
                               autocomplete="off"
                               class="bg-black text-gray-400 font-mono text-xs px-2 py-1 w-32 focus:outline-none focus:text-white placeholder-gray-700 focus:w-48 transition-all duration-200">
                    </div>
                </form>

                @if(auth()->user()?->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-red-500 hover:text-red-400 transition-colors hidden md:inline font-bold border border-red-900 px-2 py-0.5 hover:border-red-500">
                        [ADMIN]
                    </a>
                @endif

                <a href="{{ route('help') }}" class="text-sm text-gray-400 hover:text-yellow-400 transition-colors hidden md:inline font-bold border border-gray-800 px-2 py-0.5 hover:border-yellow-400">
                    [?]
                </a>

                <span class="text-gray-700 hidden md:inline">|</span>

                {{-- Notification Bell --}}
                <div x-data="notifications()" class="relative" x-init="init()">
                    <button @click="open = !open; if(open) markAllRead()"
                            class="relative text-gray-400 hover:text-white transition-colors text-sm font-mono">
                        [ALERTS]
                        <span x-show="unread > 0" x-cloak
                              class="absolute -top-2 -right-3 bg-red-500 text-white text-xs w-4 h-4 flex items-center justify-center font-bold"
                              x-text="unread > 9 ? '9+' : unread"></span>
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false"
                         class="absolute right-0 mt-2 w-80 bg-black border-2 border-white shadow-hard z-50 max-h-72 overflow-y-auto">
                        <div class="px-4 py-2 border-b border-gray-800 text-xs text-gray-500 uppercase tracking-widest flex justify-between">
                            <span>NOTIFICATIONS</span>
                        </div>
                        <template x-if="alerts.length === 0">
                            <p class="px-4 py-6 text-xs text-gray-700 text-center">No notifications yet.</p>
                        </template>
                        <template x-for="(a, i) in alerts" :key="i">
                            <div class="px-4 py-3 border-b border-gray-900 hover:bg-gray-900 transition-colors"
                                 :class="!a.read ? 'border-l-2 border-l-neon-green' : ''">
                                <p class="text-xs text-gray-300" x-text="a.message"></p>
                                <p class="text-xs text-gray-700 mt-0.5" x-text="a.time"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <span class="text-gray-700 hidden md:inline">|</span>

                {{-- User Dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="flex items-center gap-2 text-sm text-gray-300 hover:text-white transition-colors">
                        <span class="font-bold text-neon-green max-w-[80px] truncate inline-block">{{ auth()->user()?->name }}</span>
                        <span class="text-xs text-gray-500">▼</span>
                    </button>

                    <div x-show="open" x-cloak @click.away="open = false"
                         class="absolute right-0 mt-2 w-52 bg-black border-2 border-white shadow-hard z-50">
                        <a href="{{ route('profile.show') }}"
                           class="block px-4 py-3 text-sm text-gray-300 hover:text-neon-green hover:bg-gray-900 border-b border-gray-800 transition-colors">
                            > PROFILE
                        </a>
                        @if(Laravel\Jetstream\Jetstream::hasTeamFeatures() && auth()->user()->currentTeam)
                            <a href="{{ route('teams.show', auth()->user()->currentTeam) }}"
                               class="block px-4 py-3 text-sm text-gray-300 hover:text-neon-green hover:bg-gray-900 border-b border-gray-800 transition-colors">
                                > TEAM_SETTINGS
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-3 text-sm text-red-500 hover:bg-red-950 transition-colors">
                                > LOGOUT
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Hamburger (mobile only) --}}
                <button @click="mobileOpen = !mobileOpen"
                        class="md:hidden ml-3 text-gray-400 hover:text-white transition-colors font-mono text-xl leading-none p-1">
                    <span x-show="!mobileOpen">☰</span>
                    <span x-show="mobileOpen" x-cloak>✕</span>
                </button>
            </div>
        </div>

        {{-- Mobile nav drawer --}}
        <div x-show="mobileOpen" x-cloak
             class="md:hidden border-t-2 border-gray-800 bg-black">
            <div class="px-6 py-4 space-y-1 font-mono text-sm">

                {{-- Mobile search --}}
                <form method="GET" action="{{ route('search') }}"
                      class="flex items-center border border-gray-800 focus-within:border-neon-green transition-colors mb-4">
                    <span class="text-neon-green text-xs pl-2 opacity-50">/</span>
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="search..."
                           autocomplete="off"
                           class="bg-black text-gray-400 text-xs px-2 py-2 flex-1 focus:outline-none focus:text-white placeholder-gray-700">
                </form>

                @unless(auth()->user()->is_admin)
                <a href="{{ route('dashboard') }}"
                   class="block text-gray-400 hover:text-neon-green py-2.5 border-b border-gray-900 transition-colors">
                    > BOARDS
                </a>
                <a href="{{ route('analytics') }}"
                   class="block text-gray-400 hover:text-neon-green py-2.5 border-b border-gray-900 transition-colors">
                    > ANALYTICS
                </a>
                <a href="{{ route('labels.index') }}"
                   class="block text-gray-400 hover:text-neon-green py-2.5 border-b border-gray-900 transition-colors">
                    > LABELS
                </a>
                @endunless
                <a href="{{ route('help') }}"
                   class="block text-gray-400 hover:text-yellow-400 py-2.5 border-b border-gray-900 transition-colors">
                    > [?] HELP
                </a>

                @if(auth()->user()?->is_admin)
                <a href="{{ route('admin.dashboard') }}"
                   class="block text-red-500 hover:text-red-400 py-2.5 border-b border-gray-900 transition-colors font-bold">
                    > [ADMIN]
                </a>
                @endif

                <a href="{{ route('profile.show') }}"
                   class="block text-gray-400 hover:text-neon-green py-2.5 border-b border-gray-900 transition-colors">
                    > PROFILE
                </a>
                @if(Laravel\Jetstream\Jetstream::hasTeamFeatures() && auth()->user()->currentTeam)
                <a href="{{ route('teams.show', auth()->user()->currentTeam) }}"
                   class="block text-gray-400 hover:text-neon-green py-2.5 border-b border-gray-900 transition-colors">
                    > TEAM_SETTINGS
                </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left text-red-500 hover:text-red-400 py-2.5 transition-colors">
                        > LOGOUT
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="border-b-2 border-neon-green bg-black px-6 py-3 text-neon-green text-sm font-mono flex justify-between items-center">
            <span>> {{ session('success') }}</span>
            <button @click="show = false" class="text-gray-600 hover:text-white text-xs">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="border-b-2 border-red-500 bg-black px-6 py-3 text-red-500 text-sm font-mono flex justify-between items-center">
            <span>> ERROR: {{ session('error') }}</span>
            <button @click="show = false" class="text-gray-600 hover:text-white text-xs">✕</button>
        </div>
    @endif

    @if (isset($header))
        <div class="border-b-4 border-gray-800 bg-dark-gray px-8 py-4">
            {{ $header }}
        </div>
    @endif

    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- Live Team Chat Panel --}}
    @auth
    <div x-data="chat()" x-init="init()" class="fixed bottom-0 right-4 z-50 font-mono">

        {{-- Collapsed toggle --}}
        <div x-show="!open"
             @click="open = true; unread = 0; $nextTick(() => scrollToBottom())"
             class="cursor-pointer bg-black border-2 border-white px-4 py-2 text-xs font-bold flex items-center gap-3 hover:border-neon-green hover:text-neon-green transition-colors">
            <span>[TEAM_CHAT]</span>
            <span x-show="unread > 0" x-cloak
                  class="bg-neon-green text-black text-xs px-1.5 font-black"
                  x-text="unread"></span>
        </div>

        {{-- Expanded panel --}}
        <div x-show="open" x-cloak
             class="bg-black border-2 border-white w-80 flex flex-col"
             style="height: 420px;">

            {{-- Header --}}
            <div class="flex justify-between items-center px-4 py-2 border-b-2 border-white bg-black">
                <span class="text-xs font-bold text-neon-green">TEAM_CHAT //</span>
                <button @click="open = false" class="text-gray-500 hover:text-white text-xs transition-colors">[ — ]</button>
            </div>

            {{-- Messages --}}
            <div id="chat-messages" class="flex-1 overflow-y-auto px-3 py-3 space-y-3">
                <template x-if="messages.length === 0">
                    <p class="text-xs text-gray-700 text-center pt-8">No messages yet. Say something!</p>
                </template>
                <template x-for="msg in messages" :key="msg.id ?? Math.random()">
                    <div :class="msg.user_id == currentUserId ? 'text-right' : 'text-left'">
                        <span class="text-xs font-bold"
                              :class="msg.user_id == currentUserId ? 'text-neon-green' : 'text-gray-400'"
                              x-text="msg.user_id == currentUserId ? 'YOU' : msg.user_name.toUpperCase()"></span>
                        <div class="mt-0.5 inline-block max-w-xs text-left"
                             :class="msg.user_id == currentUserId
                                ? 'bg-neon-green text-black'
                                : 'bg-gray-900 border border-gray-700 text-gray-200'">
                            <p class="text-xs px-2 py-1.5 leading-relaxed break-words" x-text="msg.content"></p>
                        </div>
                        <p class="text-xs text-gray-800 mt-0.5" x-text="msg.created_at"></p>
                    </div>
                </template>
            </div>

            {{-- Input --}}
            <div class="border-t-2 border-gray-800 p-2">
                <form @submit.prevent="sendMessage" class="flex gap-2">
                    <input x-model="newMessage"
                           type="text"
                           placeholder="> TYPE_MESSAGE..."
                           maxlength="1000"
                           class="flex-1 bg-black border border-gray-700 text-white text-xs px-2 py-1.5 focus:border-neon-green focus:outline-none font-mono min-w-0">
                    <button type="submit"
                            :disabled="!newMessage.trim() || sending"
                            class="text-xs border border-gray-700 px-2 py-1.5 text-neon-green hover:bg-neon-green hover:text-black hover:border-neon-green transition-colors font-bold disabled:opacity-30">
                        SEND
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endauth

    @stack('modals')
    @livewireScripts
    @stack('scripts')

    <script>
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            document.getElementById('global-search')?.focus();
        }
    });

    function notifications() {
        return {
            open: false,
            unread: 0,
            alerts: [],

            async init() {
                await this.loadFromDb();

                const teamId = document.querySelector('meta[name="team-id"]')?.content;
                if (!teamId || !window.Echo) return;
                window.Echo.private('team.' + teamId)
                    .listen('.task.moved', (e) => {
                        const label = { todo: 'TO_DO', doing: 'IN_PROGRESS', done: 'SHIPPED' };
                        this.alerts.unshift({
                            message: `${e.moved_by} moved "${e.title}" → ${label[e.new_status] ?? e.new_status}`,
                            time: 'just now',
                            read: false,
                        });
                        if (this.alerts.length > 20) this.alerts.pop();
                        if (!this.open) this.unread++;
                    });
            },

            async loadFromDb() {
                try {
                    const res = await fetch('/notifications', { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) return;
                    const data = await res.json();
                    this.alerts = data;
                    this.unread = data.filter(n => !n.read).length;
                } catch {}
            },

            async markAllRead() {
                try {
                    await fetch('/notifications/read-all', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    });
                    this.alerts = this.alerts.map(a => ({ ...a, read: true }));
                    this.unread = 0;
                } catch {}
            },
        };
    }

    function chat() {
        return {
            open: false,
            messages: [],
            newMessage: '',
            unread: 0,
            sending: false,
            currentUserId: document.querySelector('meta[name="user-id"]')?.content,

            init() {
                this.loadMessages();
                const teamId = document.querySelector('meta[name="team-id"]')?.content;
                if (!teamId || !window.Echo) return;
                window.Echo.private('team.' + teamId)
                    .listen('.message.sent', (e) => {
                        this.messages.push(e);
                        if (!this.open) this.unread++;
                        this.$nextTick(() => this.scrollToBottom());
                    });
            },

            async loadMessages() {
                try {
                    const res = await fetch('/messages', {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (res.ok) this.messages = await res.json();
                } catch {}
            },

            async sendMessage() {
                const content = this.newMessage.trim();
                if (!content || this.sending) return;
                this.newMessage = '';
                this.sending   = true;

                const optimistic = {
                    id: null,
                    content,
                    user_id: this.currentUserId,
                    user_name: document.querySelector('meta[name="user-name"]')?.content ?? 'You',
                    created_at: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                };
                this.messages.push(optimistic);
                this.$nextTick(() => this.scrollToBottom());

                try {
                    const res = await fetch('/messages', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ content }),
                    });
                    if (res.ok) {
                        const saved = await res.json();
                        optimistic.id = saved.id;
                    }
                } catch {}

                this.sending = false;
            },

            scrollToBottom() {
                const el = document.getElementById('chat-messages');
                if (el) el.scrollTop = el.scrollHeight;
            },
        };
    }
    </script>
</body>
</html>
