{{-- ═══ TESTIMONIALS ═══ --}}
<section class="py-24" style="background:#0d0d0d;border-top:1px solid #161616;">
    <div class="max-w-6xl mx-auto px-6">

        <div class="mb-14 reveal">
            <span class="section-label">// social proof</span>
            <h2 class="text-3xl lg:text-4xl font-bold tracking-tight text-white">
                Teams that actually ship.
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 reveal">
            @foreach($testimonials as $t)
            <div class="card p-6 flex flex-col">

                {{-- Stars --}}
                <div class="flex gap-1 mb-5">
                    @for($i = 0; $i < $t['stars']; $i++)
                    <span class="text-sm" style="color:rgba(57,255,20,0.55);">★</span>
                    @endfor
                </div>

                {{-- Quote --}}
                <p class="text-sm leading-relaxed flex-1 mb-6" style="color:#666;">{{ $t['quote'] }}</p>

                {{-- Author --}}
                <div class="flex items-center gap-3 pt-5 border-t" style="border-color:#1e1e1e;">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-mono font-bold shrink-0"
                         style="{{ $t['accent']
                            ? 'background:rgba(57,255,20,0.1);border:1px solid rgba(57,255,20,0.25);color:#39FF14;'
                            : 'background:#1a1a1a;border:1px solid #2a2a2a;color:#555;' }}">
                        {{ $t['initial'] }}
                    </div>
                    <div>
                        <p class="text-xs font-bold text-white font-mono uppercase tracking-wide">{{ $t['name'] }}</p>
                        <p class="text-xs font-mono mt-0.5" style="color:#444;">{{ $t['role'] }}</p>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</section>
