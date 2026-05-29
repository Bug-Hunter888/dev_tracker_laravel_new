{{-- ═══ NAV ═══ --}}
<nav class="nav-surface fixed top-0 left-0 right-0 z-50">
    <div class="max-w-6xl mx-auto px-6 h-14 flex items-center justify-between">

        {{-- Logo --}}
        <a href="/" class="flex items-center gap-2.5 group">
            <div class="w-5 h-5 border border-neon-green flex items-center justify-center" style="background:rgba(57,255,20,0.08);">
                <span class="w-1.5 h-1.5 rounded-full bg-neon-green"></span>
            </div>
            <span class="font-mono text-sm font-bold text-white group-hover:text-neon-green transition-colors duration-150 tracking-tight">
                ./DevTracker_
            </span>
        </a>

        {{-- Desktop links --}}
        <div class="hidden md:flex items-center gap-8">
            <a href="#features"  class="text-xs font-mono text-gray-600 hover:text-white transition-colors tracking-wide">Features</a>
            <a href="#preview"   class="text-xs font-mono text-gray-600 hover:text-white transition-colors tracking-wide">Preview</a>
            <a href="#how"       class="text-xs font-mono text-gray-600 hover:text-white transition-colors tracking-wide">How it works</a>
            <a href="#pricing"   class="text-xs font-mono text-gray-600 hover:text-white transition-colors tracking-wide">Pricing</a>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            @auth
                <span class="hidden md:flex items-center gap-2 text-xs font-mono" style="color:#444;">
                    <span class="w-1.5 h-1.5 rounded-full bg-neon-green animate-pulse"></span>
                    Hello, <span class="text-neon-green font-bold ml-1">{{ auth()->user()->name }}</span>
                </span>
                <a href="{{ url('/dashboard') }}" class="btn-primary py-2 px-4 text-xs">
                    Go to app →
                </a>
            @else
                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                       class="hidden md:block text-xs font-mono text-gray-600 hover:text-white transition-colors tracking-wide">
                        Log in
                    </a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary py-2 px-4 text-xs">
                        Get started →
                    </a>
                @endif
            @endauth
            <button id="nav-toggle"
                    class="md:hidden p-1.5 text-gray-500 hover:text-white transition-colors"
                    aria-label="Open menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Bottom neon line --}}
    <div class="neon-rule"></div>

    {{-- Mobile menu --}}
    <div id="mobile-menu" class="hidden md:hidden border-t" style="border-color:#161616;background:rgba(8,8,8,0.98);">
        <div class="max-w-6xl mx-auto px-6 py-5 flex flex-col gap-4">
            <a href="#features"  class="text-sm font-mono text-gray-500 hover:text-white transition-colors">Features</a>
            <a href="#preview"   class="text-sm font-mono text-gray-500 hover:text-white transition-colors">Preview</a>
            <a href="#how"       class="text-sm font-mono text-gray-500 hover:text-white transition-colors">How it works</a>
            <a href="#pricing"   class="text-sm font-mono text-gray-500 hover:text-white transition-colors">Pricing</a>
            <div class="border-t pt-4 flex flex-col gap-3" style="border-color:#1e1e1e;">
                @auth
                    <span class="text-xs font-mono" style="color:#444;">
                        Hello, <span class="text-neon-green font-bold">{{ auth()->user()->name }}</span>
                    </span>
                    <a href="{{ url('/dashboard') }}" class="btn-primary text-sm justify-center text-center">Go to app →</a>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="text-sm font-mono text-gray-500 hover:text-white transition-colors">Log in</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary text-sm justify-center text-center">Get started →</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</nav>
