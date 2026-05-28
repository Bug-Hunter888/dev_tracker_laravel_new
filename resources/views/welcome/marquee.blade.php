{{-- ═══ MARQUEE ═══ --}}
<div class="py-3 overflow-hidden marquee-mask"
     style="background:#0d0d0d;border-top:1px solid #161616;border-bottom:1px solid #161616;">
    <div class="marquee-track">
        @foreach($techStack as $tech)
        <span class="inline-flex items-center gap-2 px-6 text-xs font-mono tracking-widest uppercase whitespace-nowrap"
              style="color:#2e2e2e;">
            <span class="w-1 h-1 rounded-full shrink-0" style="background:rgba(57,255,20,0.25);"></span>
            {{ $tech }}
        </span>
        @endforeach
    </div>
</div>
