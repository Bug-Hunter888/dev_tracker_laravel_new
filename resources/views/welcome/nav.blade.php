{{-- ═══ NAV ═══ --}}
<nav class="nav-surface fixed top-0 left-0 right-0 z-50">
    <div class="max-w-6xl mx-auto px-6 h-14 flex items-center justify-between">

        {{-- Logo --}}
        <a href="/" class="flex items-center gap-2 group">
            <span class="w-1.5 h-1.5 rounded-full bg-neon-green"></span>
            <span class="font-mono text-sm font-bold text-white group-hover:text-neon-green transition-colors duration-150">
                ./DevTracker_
            </span>
        </a>

        {{-- Desktop links --}}
        <div class="hidden md:flex items-center gap-8">
            <a href="#features" class="text-xs font-mono text-gray-500 hover:text-white transition-colors">Features</a>
            <a href="#preview"  class="text-xs font-mono text-gray-500 hover:text-white transition-colors">Preview</a>
            <a href="#pricing"  class="text-xs font-mono text-gray-500 hover:text-white transition-colors">Pricing</a>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            @if (Route::has('login'))
                <a href="{{ route('login') }}"
                   class="hidden md:block text-xs font-mono text-gray-500 hover:text-white transition-colors">
                    Login
                </a>
            @endif
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-primary py-2 px-4 text-xs">
                    Get started
                </a>
            @endif
            <button id="nav-toggle"
                    class="md:hidden p-1.5 text-gray-500 hover:text-white transition-colors"
                    aria-label="Open menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div id="mobile-menu" class="hidden md:hidden border-t" style="border-color:#161616;background:rgba(10,10,10,0.97);">
        <div class="max-w-6xl mx-auto px-6 py-5 flex flex-col gap-4">
            <a href="#features" class="text-sm font-mono text-gray-400 hover:text-white transition-colors">Features</a>
            <a href="#preview"  class="text-sm font-mono text-gray-400 hover:text-white transition-colors">Preview</a>
            <a href="#pricing"  class="text-sm font-mono text-gray-400 hover:text-white transition-colors">Pricing</a>
            <div class="border-t pt-4 flex flex-col gap-3" style="border-color:#1e1e1e;">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="text-sm font-mono text-gray-500 hover:text-white transition-colors">Login</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary text-sm justify-center text-center">Get started</a>
                @endif
            </div>
        </div>
    </div>
</nav>
