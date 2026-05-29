{{-- ═══ CTA ═══ --}}
<section class="py-32 relative overflow-hidden" style="background:#080808;border-top:1px solid #111;">

    {{-- Green spotlight glow --}}
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
        <div style="width:700px;height:350px;
                    background:radial-gradient(ellipse at center, rgba(57,255,20,0.07) 0%, transparent 65%);
                    filter:blur(40px);">
        </div>
    </div>

    {{-- Top neon rule --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-px h-16"
         style="background:linear-gradient(to bottom,rgba(57,255,20,0.3),transparent);">
    </div>

    <div class="relative max-w-2xl mx-auto px-6 text-center reveal">

        <span class="section-label">// ready to ship?</span>

        <h2 class="text-4xl lg:text-6xl font-bold tracking-tight leading-tight my-6 text-white">
            Stop managing.<br>
            <span class="text-shimmer">Start shipping.</span>
        </h2>

        <p class="text-base mb-10" style="color:#555;">
            Free to start. No credit card. No setup complexity.<br>Cancel anytime.
        </p>

        {{-- Terminal CTA --}}
        <div class="code-block w-fit mx-auto mb-8 glow-green-sm">
            <span style="color:rgba(57,255,20,0.5);">$</span>
            <span class="text-sm" style="color:#555;">devtracker --new-account --plan=free --no-cc</span>
            <span class="cursor-blink text-neon-green font-bold">▌</span>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-4 mb-10">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-primary text-base px-8 py-4">
                    Create free account
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            @endif
            <a href="#features" class="btn-secondary text-base">Explore features</a>
        </div>

        {{-- Trust signals --}}
        <div class="flex flex-wrap items-center justify-center gap-6 text-xs font-mono" style="color:#2a2a2a;">
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                No spam
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                No credit card
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/></svg>
                Cancel anytime
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                12,000+ developers
            </span>
        </div>

    </div>

    {{-- Bottom neon rule --}}
    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-px h-16"
         style="background:linear-gradient(to top,rgba(57,255,20,0.3),transparent);">
    </div>
</section>
