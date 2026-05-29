{{-- ═══ STATS ═══ --}}
<section class="py-20" style="background:#0a0a0a;">
    <div class="max-w-6xl mx-auto px-6">
        <div class="reveal" style="border:1px solid #1a1a1a;border-radius:12px;overflow:hidden;background:#0d0d0d;">
            {{-- Top neon accent --}}
            <div style="height:2px;background:linear-gradient(90deg,transparent 0%,rgba(57,255,20,0.5) 50%,transparent 100%);"></div>
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-y md:divide-y-0" style="border-color:#1a1a1a;">
                @foreach($stats as $stat)
                <div class="text-center py-12 px-6 group hover:bg-neon-green/5 transition-colors duration-300">
                    @if(($stat['count'] ?? null) !== null)
                        <div class="text-4xl lg:text-5xl font-bold font-mono text-neon-green mb-2 text-glow"
                             data-count="{{ $stat['count'] }}"
                             data-suffix="{{ $stat['suffix'] ?? '' }}">{{ $stat['value'] }}</div>
                    @else
                        <div class="text-4xl lg:text-5xl font-bold font-mono text-neon-green mb-2 text-glow">{{ $stat['value'] }}</div>
                    @endif
                    <p class="text-xs font-mono tracking-widest uppercase" style="color:#333;">{{ $stat['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
