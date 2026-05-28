{{-- ═══ HERO ═══ --}}
<section class="min-h-screen flex items-center pt-14" style="background:#0a0a0a;">
    <div class="max-w-7xl mx-auto px-6 py-24 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- Left: copy --}}
            <div>
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 mb-8 px-3 py-1.5 rounded-full text-xs font-mono"
                     style="background:#111;border:1px solid #1e1e1e;color:rgba(57,255,20,0.65);">
                    <span class="w-1.5 h-1.5 rounded-full bg-neon-green animate-pulse"></span>
                    v1.0 — Now live
                </div>

                {{-- Headline --}}
                <h1 class="font-bold leading-tight tracking-tight mb-6"
                    style="font-size:clamp(2.5rem,5vw,4rem);line-height:1.06;">
                    <span class="text-white">Ship faster.</span><br>
                    <span class="text-white">Break <span class="text-neon-green">less.</span></span><br>
                    <span style="color:#444;">Stay sane.</span>
                </h1>

                {{-- Sub --}}
                <p class="text-base leading-relaxed max-w-md mb-10" style="color:#666;">
                    Kanban boards, real-time collaboration, workflow automations, and analytics — built for developer teams.
                </p>

                {{-- CTAs --}}
                <div class="flex flex-wrap gap-4 mb-10">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary">Start for free →</a>
                    @endif
                    <a href="#preview" class="btn-secondary">See preview</a>
                </div>

                {{-- Social proof --}}
                <div class="flex items-center gap-3">
                    <div class="flex -space-x-2.5">
                        @foreach(['M','T','S','R','A'] as $letter)
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-mono font-bold"
                             style="{{ $loop->first
                                ? 'background:rgba(57,255,20,0.12);border:2px solid rgba(57,255,20,0.28);color:#39FF14;'
                                : 'background:#1a1a1a;border:2px solid #0a0a0a;color:#555;' }}">
                            {{ $letter }}
                        </div>
                        @endforeach
                    </div>
                    <p class="text-xs font-mono" style="color:#555;">
                        <span class="text-white font-semibold">12,000+</span> developers trust DevTracker
                    </p>
                </div>
            </div>

            {{-- Right: kanban mockup --}}
            <div class="hidden lg:block">
                <div class="card overflow-hidden" style="box-shadow:0 20px 60px rgba(0,0,0,0.5);">

                    {{-- Window chrome --}}
                    <div class="flex items-center gap-2 px-4 py-3 border-b" style="border-color:#1e1e1e;">
                        <div class="w-2.5 h-2.5 rounded-full" style="background:rgba(239,68,68,0.5);"></div>
                        <div class="w-2.5 h-2.5 rounded-full" style="background:rgba(234,179,8,0.5);"></div>
                        <div class="w-2.5 h-2.5 rounded-full" style="background:rgba(57,255,20,0.5);"></div>
                        <span class="ml-3 text-xs font-mono" style="color:#3a3a3a;">devtracker / sprint-5</span>
                        <span class="ml-auto text-xs font-mono flex items-center gap-1.5" style="color:rgba(57,255,20,0.55);">
                            <span class="w-1.5 h-1.5 rounded-full bg-neon-green animate-pulse"></span>live
                        </span>
                    </div>

                    {{-- Columns --}}
                    <div class="grid grid-cols-3 divide-x" style="background:#0e0e0e;border-color:#1a1a1a;">

                        {{-- Todo --}}
                        <div class="p-3" style="border-color:#1a1a1a;">
                            <p class="text-xs font-mono uppercase tracking-wider mb-3 flex justify-between" style="color:#3a3a3a;">
                                Todo <span>3</span>
                            </p>
                            <div class="space-y-1.5">
                                <div class="rounded-lg p-2.5 border" style="background:#111;border-color:#1e1e1e;">
                                    <p class="text-xs mb-1.5 leading-snug" style="color:#888;">Fix OAuth callback</p>
                                    <span class="text-xs font-mono px-1.5 py-0.5 rounded" style="color:#f87171;background:rgba(248,113,113,0.1);">critical</span>
                                </div>
                                <div class="rounded-lg p-2.5 border" style="background:#111;border-color:#1e1e1e;">
                                    <p class="text-xs mb-1.5 leading-snug" style="color:#888;">Write API docs</p>
                                    <span class="text-xs font-mono px-1.5 py-0.5 rounded" style="color:#555;background:rgba(255,255,255,0.04);">low</span>
                                </div>
                                <div class="rounded-lg p-2.5 border" style="background:#111;border-color:#1e1e1e;">
                                    <p class="text-xs mb-1.5 leading-snug" style="color:#888;">Add dark mode</p>
                                    <span class="text-xs font-mono px-1.5 py-0.5 rounded" style="color:#eab308;background:rgba(234,179,8,0.1);">normal</span>
                                </div>
                            </div>
                        </div>

                        {{-- In Progress --}}
                        <div class="p-3" style="border-color:#1a1a1a;">
                            <p class="text-xs font-mono uppercase tracking-wider mb-3 flex justify-between" style="color:rgba(234,179,8,0.5);">
                                Doing <span style="color:#3a3a3a;">2</span>
                            </p>
                            <div class="space-y-1.5">
                                <div class="rounded-lg p-2.5 border" style="background:#111;border-color:#1e1e1e;border-left:2px solid rgba(234,179,8,0.4);">
                                    <p class="text-xs mb-1.5 leading-snug" style="color:#888;">Build kanban UI</p>
                                    <span class="text-xs font-mono px-1.5 py-0.5 rounded" style="color:#f87171;background:rgba(248,113,113,0.1);">critical</span>
                                </div>
                                <div class="rounded-lg p-2.5 border" style="background:#111;border-color:#1e1e1e;border-left:2px solid rgba(234,179,8,0.4);">
                                    <p class="text-xs mb-1.5 leading-snug" style="color:#888;">Setup WebSockets</p>
                                    <span class="text-xs font-mono px-1.5 py-0.5 rounded" style="color:#eab308;background:rgba(234,179,8,0.1);">normal</span>
                                </div>
                            </div>
                        </div>

                        {{-- Shipped --}}
                        <div class="p-3">
                            <p class="text-xs font-mono uppercase tracking-wider mb-3 flex justify-between" style="color:rgba(57,255,20,0.45);">
                                Shipped <span style="color:#3a3a3a;">5</span>
                            </p>
                            <div class="space-y-1.5">
                                @foreach(['DB migrations','User auth + roles','Design system'] as $done)
                                <div class="rounded-lg p-2.5 border" style="background:#111;border-color:#1e1e1e;opacity:.45;">
                                    <p class="text-xs line-through mb-1 leading-snug" style="color:#555;">{{ $done }}</p>
                                    <span class="text-xs font-mono" style="color:rgba(57,255,20,0.45);">✓ done</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
