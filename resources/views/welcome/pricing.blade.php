{{-- ═══ PRICING ═══ --}}
<section id="pricing" class="py-24" style="background:#0a0a0a;border-top:1px solid #161616;">
    <div class="max-w-5xl mx-auto px-6">

        <div class="mb-14 text-center reveal">
            <span class="section-label">// pricing</span>
            <h2 class="text-3xl lg:text-4xl font-bold tracking-tight text-white">
                Honest pricing. No surprises.
            </h2>
            <p class="mt-3 text-base" style="color:#666;">Free to start. Upgrade when your team is ready.</p>
        </div>

        {{-- Billing toggle --}}
        <div class="flex items-center justify-center gap-4 mb-10 reveal">
            <span class="text-sm font-mono" style="color:#555;">Monthly</span>
            <button id="billing-toggle"
                    class="relative w-12 h-6 rounded-full cursor-pointer"
                    style="background:#111;border:1px solid #2a2a2a;"
                    aria-label="Toggle billing period">
                <span id="billing-dot"
                      class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-neon-green transition-transform duration-200 ease-out"></span>
            </button>
            <span class="text-sm font-mono" style="color:#555;">
                Annual <span style="color:rgba(57,255,20,0.6);">-20%</span>
            </span>
        </div>

        {{-- Plans --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 reveal">
            @foreach($pricing as $plan)
            <div class="{{ $plan['featured'] ? 'card-featured' : 'card' }} p-7 flex flex-col">

                @if($plan['badge'])
                <div class="inline-flex items-center mb-5 px-2.5 py-1 rounded-full text-xs font-mono font-bold uppercase tracking-wider w-fit"
                     style="background:rgba(57,255,20,0.08);color:#39FF14;border:1px solid rgba(57,255,20,0.18);">
                    {{ $plan['badge'] }}
                </div>
                @endif

                <p class="text-xs font-mono tracking-widest uppercase mb-1"
                   style="color:{{ $plan['featured'] ? 'rgba(57,255,20,0.5)' : '#2a2a2a' }};">Tier {{ $plan['tier'] }}</p>
                <h3 class="text-lg font-bold font-mono text-white mb-5 uppercase">{{ $plan['name'] }}</h3>

                {{-- Price --}}
                <div class="flex items-end gap-1.5 mb-7">
                    @if(in_array($plan['name'], ['PRO', 'TEAM']))
                        @php $priceKey = strtolower($plan['name']); @endphp
                        <span class="text-4xl font-bold font-mono {{ $plan['featured'] ? 'text-neon-green' : 'text-white' }}"
                              data-price="{{ $priceKey }}">{{ $plan['price'] }}</span>
                        <span class="text-sm font-mono mb-1" style="color:#444;"
                              data-period="{{ $plan['period'] }}">{{ $plan['period'] }}</span>
                    @else
                        <span class="text-4xl font-bold font-mono text-white">{{ $plan['price'] }}</span>
                        <span class="text-sm font-mono mb-1" style="color:#444;">{{ $plan['period'] }}</span>
                    @endif
                </div>

                {{-- Features --}}
                <ul class="space-y-2.5 flex-1 mb-8">
                    @foreach($plan['features'] as $feature)
                    <li class="flex items-start gap-2 text-xs">
                        <span class="mt-0.5 shrink-0 font-bold font-mono"
                              style="color:{{ $feature['included'] ? '#39FF14' : '#252525' }};">
                            {{ $feature['included'] ? '+' : '—' }}
                        </span>
                        <span style="color:{{ $feature['included'] ? '#666' : '#2a2a2a' }};">{{ $feature['text'] }}</span>
                    </li>
                    @endforeach
                </ul>

                {{-- CTA --}}
                @if (Route::has('register'))
                    @if($plan['cta_type'] === 'primary')
                        <a href="{{ route('register') }}" class="btn-primary text-center justify-center text-sm">
                            {{ $plan['cta'] }}
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-secondary text-center justify-center text-sm">
                            {{ $plan['cta'] }}
                        </a>
                    @endif
                @endif

            </div>
            @endforeach
        </div>

        <p class="text-xs font-mono text-center mt-8" style="color:#2e2e2e;">
            All plans include a 14-day PRO trial. No credit card required.
        </p>

    </div>
</section>
