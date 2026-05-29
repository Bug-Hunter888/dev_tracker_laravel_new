{{-- ═══ MARQUEE ═══ --}}
<div class="overflow-hidden marquee-mask" style="background:#060606;border-top:1px solid #111;border-bottom:1px solid #111;padding:10px 0;">
    <div class="marquee-track">
        @foreach($techStack as $tech)
        <span class="inline-flex items-center gap-2.5 px-7 text-xs font-mono tracking-widest uppercase whitespace-nowrap"
              style="color:#252525;">
            <span class="w-1 h-1 rounded-full shrink-0" style="background:rgba(57,255,20,0.3);"></span>
            {{ $tech }}
        </span>
        @endforeach
        {{-- Duplicate for seamless loop --}}
        @foreach($techStack as $tech)
        <span class="inline-flex items-center gap-2.5 px-7 text-xs font-mono tracking-widest uppercase whitespace-nowrap"
              style="color:#252525;">
            <span class="w-1 h-1 rounded-full shrink-0" style="background:rgba(57,255,20,0.3);"></span>
            {{ $tech }}
        </span>
        @endforeach
    </div>
</div>
