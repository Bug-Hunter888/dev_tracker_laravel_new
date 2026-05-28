{{-- ═══ CTA ═══ --}}
<section class="py-28" style="background:#0d0d0d;border-top:1px solid #161616;">
    <div class="max-w-2xl mx-auto px-6 text-center reveal">

        <span class="section-label">// ready?</span>

        <h2 class="text-3xl lg:text-5xl font-bold tracking-tight leading-tight mb-5 text-white">
            Stop managing.<br>
            <span class="text-neon-green">Start shipping.</span>
        </h2>

        <p class="text-base mb-8" style="color:#666;">
            Free to start. No credit card. No setup complexity. Cancel anytime.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-primary text-base px-8 py-4">
                    Create free account →
                </a>
            @endif
            <a href="#features" class="btn-secondary text-base">See features</a>
        </div>

        <p class="text-xs font-mono mt-8" style="color:#2a2a2a;">
            Trusted by 12,000+ developers · No spam · Cancel anytime
        </p>

    </div>
</section>
