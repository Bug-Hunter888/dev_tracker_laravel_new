{{-- ═══ FEATURES ═══ --}}
<section id="features" class="py-28" style="background:#0a0a0a;border-top:1px solid #111;">
    <div class="max-w-6xl mx-auto px-6">

        <div class="mb-16 reveal">
            <span class="section-label">// workflow</span>
            <h2 class="text-3xl lg:text-4xl font-bold tracking-tight leading-tight text-white mt-4">
                Everything your team<br>actually needs.
            </h2>
            <p class="mt-4 text-base max-w-lg" style="color:#555;">
                No bloat, no complexity — just the tools that make dev teams ship faster.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($features as $feature)
            <div class="card p-6 reveal reveal-delay-{{ $loop->iteration }} group cursor-default">

                {{-- Header row --}}
                <div class="flex items-start justify-between mb-5">
                    <div class="inline-flex items-center justify-center px-3 py-2.5 font-mono text-[10px] leading-tight whitespace-nowrap"
                         style="color:rgba(57,255,20,0.55);background:rgba(57,255,20,0.04);border:1px solid rgba(57,255,20,0.1);border-radius:6px;">
                        {!! $feature['icon'] !!}
                    </div>
                    <span class="feature-num group-hover:text-neon-green/30 transition-colors">
                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                <h3 class="text-sm font-mono font-bold text-white mb-2.5 tracking-wide group-hover:text-neon-green transition-colors">{{ $feature['title'] }}</h3>
                <p class="text-sm leading-relaxed" style="color:#555;">{{ $feature['description'] }}</p>

                {{-- Bottom accent on hover --}}
                <div class="mt-5 h-px bg-neon-green/0 group-hover:bg-neon-green/20 transition-all duration-300"></div>
            </div>
            @endforeach
        </div>

    </div>
</section>
