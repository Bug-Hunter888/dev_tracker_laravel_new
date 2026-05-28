{{-- ═══ STATS ═══ --}}
<section class="py-16" style="background:#0a0a0a;border-bottom:1px solid #161616;">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-px reveal" style="background:#161616;border:1px solid #161616;border-radius:12px;overflow:hidden;">
            @foreach($stats as $stat)
            <div class="text-center py-10 px-6" style="background:#0a0a0a;">
                @if(($stat['count'] ?? null) !== null)
                    <div class="text-3xl lg:text-4xl font-bold font-mono text-neon-green mb-2"
                         data-count="{{ $stat['count'] }}"
                         data-suffix="{{ $stat['suffix'] ?? '' }}">{{ $stat['value'] }}</div>
                @else
                    <div class="text-3xl lg:text-4xl font-bold font-mono text-neon-green mb-2">{{ $stat['value'] }}</div>
                @endif
                <p class="text-xs font-mono tracking-widest uppercase" style="color:#333;">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
