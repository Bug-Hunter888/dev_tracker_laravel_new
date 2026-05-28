{{-- ═══ FOOTER ═══ --}}
<footer style="background:#080808;border-top:1px solid #161616;">
    <div class="max-w-6xl mx-auto px-6 pt-14 pb-8">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-10 mb-12">

            {{-- Brand --}}
            <div class="col-span-2 md:col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-neon-green"></span>
                    <span class="font-mono text-sm font-bold text-white">./DevTracker_</span>
                </div>
                <p class="text-xs leading-relaxed mb-5" style="color:#444;">
                    Project management for teams who ship. Built on Laravel + Tailwind CSS.
                </p>

                {{-- Status badge --}}
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-mono mb-5"
                     style="background:#111;border:1px solid #1e1e1e;color:rgba(57,255,20,0.45);">
                    <span class="w-1.5 h-1.5 rounded-full bg-neon-green animate-pulse"></span>
                    SYSTEM: OPERATIONAL
                </div>

                {{-- Social icons --}}
                <div class="flex items-center gap-3">
                    <a href="#" class="transition-colors hover:text-neon-green" style="color:#2a2a2a;" aria-label="GitHub">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61-.546-1.385-1.335-1.755-1.335-1.755-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 21.795 24 17.295 24 12c0-6.63-5.37-12-12-12"/>
                        </svg>
                    </a>
                    <a href="#" class="transition-colors hover:text-neon-green" style="color:#2a2a2a;" aria-label="Discord">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/>
                        </svg>
                    </a>
                    <a href="#" class="transition-colors hover:text-neon-green" style="color:#2a2a2a;" aria-label="Twitter/X">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Product --}}
            <div>
                <p class="text-xs font-mono font-bold uppercase tracking-widest mb-5" style="color:#2a2a2a;">Product</p>
                <ul class="space-y-3">
                    <li><a href="#features" class="text-xs font-mono transition-colors hover:text-white" style="color:#3a3a3a;">Features</a></li>
                    <li><a href="#preview"  class="text-xs font-mono transition-colors hover:text-white" style="color:#3a3a3a;">Preview</a></li>
                    <li><a href="#pricing"  class="text-xs font-mono transition-colors hover:text-white" style="color:#3a3a3a;">Pricing</a></li>
                    <li><a href="#"         class="text-xs font-mono transition-colors hover:text-white" style="color:#3a3a3a;">Changelog</a></li>
                    <li><a href="#"         class="text-xs font-mono transition-colors hover:text-white" style="color:#3a3a3a;">Docs</a></li>
                </ul>
            </div>

            {{-- Account --}}
            <div>
                <p class="text-xs font-mono font-bold uppercase tracking-widest mb-5" style="color:#2a2a2a;">Account</p>
                <ul class="space-y-3">
                    @if (Route::has('login'))
                        <li><a href="{{ route('login') }}" class="text-xs font-mono transition-colors hover:text-white" style="color:#3a3a3a;">Login</a></li>
                    @endif
                    @if (Route::has('register'))
                        <li><a href="{{ route('register') }}" class="text-xs font-mono transition-colors hover:text-white" style="color:#3a3a3a;">Register</a></li>
                    @endif
                    @auth
                        <li><a href="{{ url('/dashboard') }}" class="text-xs font-mono transition-colors hover:text-white" style="color:#3a3a3a;">Dashboard</a></li>
                    @endauth
                    <li><a href="#" class="text-xs font-mono transition-colors hover:text-white" style="color:#3a3a3a;">Settings</a></li>
                </ul>
            </div>

            {{-- Legal --}}
            <div>
                <p class="text-xs font-mono font-bold uppercase tracking-widest mb-5" style="color:#2a2a2a;">Legal</p>
                <ul class="space-y-3">
                    <li><a href="#" class="text-xs font-mono transition-colors hover:text-white" style="color:#3a3a3a;">Privacy Policy</a></li>
                    <li><a href="#" class="text-xs font-mono transition-colors hover:text-white" style="color:#3a3a3a;">Terms of Service</a></li>
                    <li><a href="#" class="text-xs font-mono transition-colors hover:text-white" style="color:#3a3a3a;">Cookie Policy</a></li>
                </ul>
            </div>

        </div>

        {{-- Bottom bar --}}
        <div class="border-t pt-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-3"
             style="border-color:#161616;">
            <p class="text-xs font-mono" style="color:#2a2a2a;">&copy; {{ date('Y') }} DevTracker. All rights reserved.</p>
            <p class="text-xs font-mono" style="color:#2a2a2a;">
                Built with <span class="text-neon-green opacity-40">Laravel</span> + <span class="text-neon-green opacity-40">Tailwind CSS</span>
            </p>
        </div>

    </div>
</footer>
