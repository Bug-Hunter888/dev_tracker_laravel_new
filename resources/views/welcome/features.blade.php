{{-- ═══ FEATURES ═══ --}}
<section id="features" class="py-24" style="background:#0a0a0a;">
    <div class="max-w-6xl mx-auto px-6">

        <div class="mb-14 reveal">
            <span class="section-label">// workflow</span>
            <h2 class="text-3xl lg:text-4xl font-bold tracking-tight leading-tight text-white">
                Everything your team actually needs.
            </h2>
            <p class="mt-3 text-base max-w-lg" style="color:#666;">
                No bloat, no complexity — just the tools that make dev teams ship faster.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 reveal">
            @foreach($features as $feature)
            <div class="card p-6">
                <div class="inline-block mb-5 px-2.5 py-2 rounded-lg font-mono text-[9px] leading-tight whitespace-nowrap"
                     style="color:rgba(57,255,20,0.5);background:rgba(57,255,20,0.04);border:1px solid rgba(57,255,20,0.08);">
                    {!! $feature['icon'] !!}
                </div>
                <h3 class="text-sm font-mono font-bold text-white mb-2 tracking-wide">{{ $feature['title'] }}</h3>
                <p class="text-sm leading-relaxed" style="color:#666;">{{ $feature['description'] }}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>
