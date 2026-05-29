{{-- ═══ HOW IT WORKS ═══ --}}
<section id="how" class="py-28" style="background:#0a0a0a;border-top:1px solid #111;">
    <div class="max-w-3xl mx-auto px-6">

        <div class="mb-14 reveal">
            <span class="section-label">// get started</span>
            <h2 class="text-3xl lg:text-4xl font-bold tracking-tight text-white">
                Up and running in three steps.
            </h2>
        </div>

        <div class="space-y-6 reveal">
            @foreach($steps as $step)
            <div class="relative flex gap-6">

                @if(!$loop->last)
                <div class="timeline-line"></div>
                @endif

                {{-- Step badge --}}
                <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center text-xs font-mono font-bold"
                     style="background:#111;border:1px solid #1e1e1e;color:rgba(57,255,20,0.55);">
                    {{ $step['number'] }}
                </div>

                {{-- Content --}}
                <div class="flex-1 pb-10">
                    <h3 class="text-sm font-mono font-bold text-white mb-2 tracking-wide">{{ $step['title'] }}</h3>
                    <p class="text-sm leading-relaxed mb-4" style="color:#666;">{{ $step['description'] }}</p>
                    <div class="code-block">
                        <span style="color:rgba(57,255,20,0.4);">$</span>
                        <span class="text-sm" style="color:#666;">{{ $step['code'] }}</span>
                        @if($step['cursor'])
                            <span class="cursor-blink text-neon-green font-bold">▌</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
