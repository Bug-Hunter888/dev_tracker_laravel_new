{{-- ═══ TESTIMONIALS ═══ --}}
<section class="py-28" style="background:#080808;border-top:1px solid #111;">
    <div class="max-w-6xl mx-auto px-6">

        <div class="mb-16 text-center reveal">
            <span class="section-label">// social proof</span>
            <h2 class="text-3xl lg:text-4xl font-bold tracking-tight text-white mt-4">
                Teams that actually ship.
            </h2>
            <p class="mt-4 text-sm font-mono" style="color:#333;">
                Join 12,000+ developers already using DevTracker
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 reveal">
            @foreach($testimonials as $t)
            <div class="card p-6 flex flex-col group">

                {{-- Stars --}}
                <div class="flex gap-1 mb-5">
                    @for($i = 0; $i < $t['stars']; $i++)
                    <svg class="w-3.5 h-3.5" fill="rgba(57,255,20,0.6)" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>

                {{-- Opening quote mark --}}
                <p class="text-4xl font-serif leading-none mb-2" style="color:rgba(57,255,20,0.1);">"</p>

                {{-- Quote --}}
                <p class="text-sm leading-relaxed flex-1 mb-6" style="color:#666;">{{ $t['quote'] }}</p>

                {{-- Author --}}
                <div class="flex items-center gap-3 pt-5 border-t" style="border-color:#1e1e1e;">
                    <div class="w-9 h-9 flex items-center justify-center text-sm font-mono font-bold shrink-0"
                         style="border-radius:4px;
                                {{ $t['accent']
                                   ? 'background:rgba(57,255,20,0.08);border:1px solid rgba(57,255,20,0.2);color:#39FF14;'
                                   : 'background:#181818;border:1px solid #222;color:#444;' }}">
                        {{ $t['initial'] }}
                    </div>
                    <div>
                        <p class="text-xs font-bold text-white font-mono uppercase tracking-wide">{{ $t['name'] }}</p>
                        <p class="text-xs font-mono mt-0.5" style="color:#333;">{{ $t['role'] }}</p>
                    </div>
                    <svg class="w-4 h-4 ml-auto opacity-0 group-hover:opacity-100 transition-opacity" style="color:rgba(57,255,20,0.4);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>

            </div>
            @endforeach
        </div>

    </div>
</section>
