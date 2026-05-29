{{-- ═══ HERO ═══ --}}
<section class="relative min-h-screen flex items-center pt-14 overflow-hidden" style="background:#080808;">

    {{-- Dot grid background --}}
    <div class="absolute inset-0 bg-dot-grid" style="opacity:0.5;"></div>

    {{-- Top-left green radial glow --}}
    <div class="absolute pointer-events-none"
         style="top:-10%;left:-5%;width:700px;height:600px;
                background:radial-gradient(ellipse at center, rgba(57,255,20,0.07) 0%, transparent 65%);
                filter:blur(30px);">
    </div>

    {{-- Bottom-right subtle glow --}}
    <div class="absolute pointer-events-none"
         style="bottom:-10%;right:-5%;width:500px;height:400px;
                background:radial-gradient(ellipse at center, rgba(57,255,20,0.04) 0%, transparent 65%);
                filter:blur(40px);">
    </div>

    {{-- Top gradient fade --}}
    <div class="absolute top-0 left-0 right-0 h-px" style="background:linear-gradient(90deg,transparent,rgba(57,255,20,0.2),transparent);"></div>

    <div class="relative max-w-7xl mx-auto px-6 py-24 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- Left: copy --}}
            <div>
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 mb-8 px-3 py-1.5 text-xs font-mono"
                     style="background:#0d0d0d;border:1px solid rgba(57,255,20,0.18);color:rgba(57,255,20,0.75);border-radius:4px;">
                    <span class="w-1.5 h-1.5 rounded-full bg-neon-green animate-pulse"></span>
                    v1.0 — Now live &nbsp;·&nbsp; Free to start
                </div>

                {{-- Headline --}}
                <h1 class="font-bold tracking-tight mb-5"
                    style="font-size:clamp(2.8rem,5.5vw,4.75rem);line-height:1.03;">
                    <span class="block text-white">The dev team's</span>
                    <span class="block text-shimmer">command center.<span class="cursor-blink" style="-webkit-text-fill-color:#39FF14;color:#39FF14;">_</span></span>
                </h1>

                {{-- Sub --}}
                <p class="text-base leading-relaxed max-w-lg mb-3" style="color:#555;">
                    Kanban boards, sprint planning, real-time collaboration,<br>
                    workflow automations &amp; analytics — all in one place.
                </p>

                {{-- Terminal chip --}}
                <div class="code-block mb-10 w-fit">
                    <span style="color:rgba(57,255,20,0.4);">$</span>
                    <span class="text-sm" style="color:#4a4a4a;">devtracker --create-team --plan=free</span>
                    <span class="cursor-blink text-neon-green font-bold">▌</span>
                </div>

                {{-- CTAs --}}
                <div class="flex flex-wrap gap-4 mb-12">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary">
                            Start for free
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    @endif
                    <a href="#preview" class="btn-secondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z"/>
                        </svg>
                        See it in action
                    </a>
                </div>

                {{-- Social proof --}}
                <div class="flex items-center gap-4">
                    <div class="flex -space-x-2">
                        @foreach(['M','T','S','R','A'] as $letter)
                        <div class="w-8 h-8 flex items-center justify-center text-xs font-mono font-bold"
                             style="border-radius:4px;
                                    {{ $loop->first
                                        ? 'background:rgba(57,255,20,0.12);border:2px solid rgba(57,255,20,0.3);color:#39FF14;'
                                        : 'background:#181818;border:2px solid #080808;color:#555;' }}">
                            {{ $letter }}
                        </div>
                        @endforeach
                    </div>
                    <div>
                        <p class="text-xs font-mono" style="color:#555;">
                            <span class="text-white font-semibold">12,000+</span> developers trust DevTracker
                        </p>
                        <div class="flex items-center gap-0.5 mt-0.5">
                            @for($i=0;$i<5;$i++)
                            <svg class="w-3 h-3" fill="rgba(57,255,20,0.5)" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                            <span class="text-xs font-mono ml-1" style="color:#333;">4.9 / 5</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: kanban mockup --}}
            <div class="hidden lg:block float-anim">
                <div class="overflow-hidden glow-green-md"
                     style="border-radius:12px;border:1px solid #1e1e1e;box-shadow:0 0 0 1px rgba(57,255,20,0.06),0 32px 80px rgba(0,0,0,0.7),0 0 80px rgba(57,255,20,0.07);">

                    {{-- Window chrome --}}
                    <div class="flex items-center gap-2 px-4 py-3 border-b" style="background:#0d0d0d;border-color:#1a1a1a;">
                        <div class="w-2.5 h-2.5 rounded-full" style="background:rgba(239,68,68,0.55);"></div>
                        <div class="w-2.5 h-2.5 rounded-full" style="background:rgba(234,179,8,0.55);"></div>
                        <div class="w-2.5 h-2.5 rounded-full" style="background:rgba(57,255,20,0.55);"></div>
                        <span class="ml-3 text-xs font-mono" style="color:#2e2e2e;">devtracker / sprint-5 / v2-launch</span>
                        <span class="ml-auto text-xs font-mono flex items-center gap-1.5" style="color:rgba(57,255,20,0.6);">
                            <span class="w-1.5 h-1.5 rounded-full bg-neon-green animate-pulse"></span>live · 3 online
                        </span>
                    </div>

                    {{-- Tab bar --}}
                    <div class="flex items-center gap-0 border-b text-xs font-mono" style="background:#0a0a0a;border-color:#181818;">
                        <span class="px-4 py-2 border-b-2 border-neon-green" style="color:#39FF14;">Board</span>
                        <span class="px-4 py-2" style="color:#2a2a2a;">Backlog</span>
                        <span class="px-4 py-2" style="color:#2a2a2a;">Analytics</span>
                        <span class="px-4 py-2" style="color:#2a2a2a;">Timeline</span>
                    </div>

                    {{-- Columns --}}
                    <div class="grid grid-cols-3 divide-x" style="background:#0c0c0c;border-color:#181818;">

                        {{-- Todo --}}
                        <div class="p-3" style="border-color:#181818;">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs font-mono uppercase tracking-wider" style="color:#3a3a3a;">Todo</p>
                                <span class="text-xs font-mono px-1.5 py-0.5" style="background:#161616;color:#2a2a2a;">3</span>
                            </div>
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
                        <div class="p-3" style="border-color:#181818;">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs font-mono uppercase tracking-wider" style="color:rgba(234,179,8,0.55);">Doing</p>
                                <span class="text-xs font-mono px-1.5 py-0.5" style="background:#161616;color:#2a2a2a;">2</span>
                            </div>
                            <div class="space-y-1.5">
                                <div class="rounded-lg p-2.5 border" style="background:#111;border-color:#1e1e1e;border-left:2px solid rgba(234,179,8,0.4);">
                                    <p class="text-xs mb-1.5 leading-snug" style="color:#888;">Build kanban UI</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-mono px-1.5 py-0.5 rounded" style="color:#f87171;background:rgba(248,113,113,0.1);">critical</span>
                                        <span class="text-xs font-mono" style="color:rgba(57,255,20,0.5);">K</span>
                                    </div>
                                </div>
                                <div class="rounded-lg p-2.5 border" style="background:#111;border-color:#1e1e1e;border-left:2px solid rgba(234,179,8,0.4);">
                                    <p class="text-xs mb-1.5 leading-snug" style="color:#888;">Setup WebSockets</p>
                                    <span class="text-xs font-mono px-1.5 py-0.5 rounded" style="color:#eab308;background:rgba(234,179,8,0.1);">normal</span>
                                </div>
                            </div>
                        </div>

                        {{-- Shipped --}}
                        <div class="p-3">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs font-mono uppercase tracking-wider" style="color:rgba(57,255,20,0.5);">Shipped</p>
                                <span class="text-xs font-mono px-1.5 py-0.5" style="background:rgba(57,255,20,0.06);color:rgba(57,255,20,0.4);">5</span>
                            </div>
                            <div class="space-y-1.5">
                                @foreach(['DB migrations','User auth + roles','Design system'] as $done)
                                <div class="rounded-lg p-2.5 border" style="background:#0d0d0d;border-color:#181818;opacity:.5;">
                                    <p class="text-xs line-through mb-1 leading-snug" style="color:#444;">{{ $done }}</p>
                                    <span class="text-xs font-mono" style="color:rgba(57,255,20,0.45);">✓ shipped</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>

                    {{-- Bottom status bar --}}
                    <div class="flex items-center justify-between px-4 py-2 border-t text-xs font-mono" style="background:#080808;border-color:#161616;color:#2a2a2a;">
                        <span>Sprint 5 · 4 days left · 10 tasks</span>
                        <span style="color:rgba(57,255,20,0.4);">● 70% complete</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Scroll hint --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 hidden md:flex flex-col items-center gap-2" style="color:#2a2a2a;">
            <span class="text-xs font-mono tracking-widest">scroll</span>
            <svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>
</section>
