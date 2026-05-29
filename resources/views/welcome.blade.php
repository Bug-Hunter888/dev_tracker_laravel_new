<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DevTracker — Project Management for Dev Teams</title>
    <meta name="description" content="Kanban boards, real-time collaboration, workflow automations, and analytics — built for developer teams that ship.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fira-code:400,500,700;figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ── Animations ─────────────────────────────── */
        @keyframes marquee   { from{transform:translateX(0)} to{transform:translateX(-50%)} }
        @keyframes blink     { 0%,100%{opacity:1} 50%{opacity:0} }
        @keyframes float     { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        @keyframes shimmer   { 0%{background-position:-200% center} 100%{background-position:200% center} }
        @keyframes scanline  { 0%{transform:translateY(-100%)} 100%{transform:translateY(100vh)} }
        @keyframes fadeInUp  { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
        @keyframes pulseGlow { 0%,100%{opacity:0.6} 50%{opacity:1} }

        .marquee-track { animation:marquee 28s linear infinite; white-space:nowrap; display:flex; }
        .marquee-track:hover { animation-play-state:paused; }
        .marquee-mask {
            -webkit-mask-image:linear-gradient(90deg,transparent,#000 8%,#000 92%,transparent);
            mask-image:linear-gradient(90deg,transparent,#000 8%,#000 92%,transparent);
        }
        .cursor-blink { animation:blink 1s step-end infinite; }
        .float-anim   { animation:float 5s ease-in-out infinite; }

        /* ── Scroll reveal ──────────────────────────── */
        .reveal { opacity:0; transform:translateY(20px); transition:opacity .6s ease, transform .6s ease; }
        .reveal.visible { opacity:1; transform:translateY(0); }
        .reveal-delay-1 { transition-delay:.08s; }
        .reveal-delay-2 { transition-delay:.16s; }
        .reveal-delay-3 { transition-delay:.24s; }
        .reveal-delay-4 { transition-delay:.32s; }
        .reveal-delay-5 { transition-delay:.40s; }
        .reveal-delay-6 { transition-delay:.48s; }

        /* ── Grid background ────────────────────────── */
        .bg-dot-grid {
            background-image: radial-gradient(circle, rgba(57,255,20,0.06) 1px, transparent 1px);
            background-size: 28px 28px;
        }

        /* ── Glow helpers ───────────────────────────── */
        .glow-green-sm  { box-shadow:0 0 20px rgba(57,255,20,0.12); }
        .glow-green-md  { box-shadow:0 0 0 1px rgba(57,255,20,0.08), 0 32px 80px rgba(0,0,0,0.6), 0 0 60px rgba(57,255,20,0.1); }
        .text-glow      { text-shadow:0 0 30px rgba(57,255,20,0.45); }

        /* ── Shimmer text ───────────────────────────── */
        .text-shimmer {
            background:linear-gradient(90deg,#39FF14 20%,#a8ff6f 50%,#39FF14 80%);
            background-size:200% auto;
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
            background-clip:text;
            animation:shimmer 5s linear infinite;
        }

        /* ── Nav ────────────────────────────────────── */
        .nav-surface {
            background:rgba(8,8,8,0.92);
            border-bottom:1px solid #161616;
            backdrop-filter:blur(20px);
            -webkit-backdrop-filter:blur(20px);
        }

        /* ── Cards ──────────────────────────────────── */
        .card {
            background:#111;
            border:1px solid #1e1e1e;
            border-radius:12px;
            transition:border-color .25s ease, box-shadow .25s ease;
        }
        .card:hover { border-color:rgba(57,255,20,0.15); box-shadow:0 0 30px rgba(57,255,20,0.04); }

        .card-featured {
            background:rgba(57,255,20,0.03);
            border:1px solid rgba(57,255,20,0.2);
            border-radius:12px;
        }

        /* ── Buttons ────────────────────────────────── */
        .btn-primary {
            display:inline-flex; align-items:center; gap:.5rem;
            background:#39FF14; color:#000;
            padding:.8rem 1.75rem;
            border-radius:6px;
            font-size:.875rem;
            font-family:'Fira Code',monospace;
            font-weight:700;
            letter-spacing:.03em;
            transition:all .15s ease;
            box-shadow:0 0 20px rgba(57,255,20,0.25);
        }
        .btn-primary:hover {
            background:#4fff2a;
            box-shadow:0 0 30px rgba(57,255,20,0.4);
            transform:translateY(-2px);
        }

        .btn-secondary {
            display:inline-flex; align-items:center; gap:.5rem;
            border:1px solid #252525; color:#777;
            padding:.8rem 1.75rem;
            border-radius:6px;
            font-size:.875rem;
            font-family:'Fira Code',monospace;
            transition:all .15s ease;
        }
        .btn-secondary:hover { border-color:#3a3a3a; color:#bbb; transform:translateY(-2px); }

        /* ── Section label ──────────────────────────── */
        .section-label {
            display:inline-block;
            font-size:.68rem;
            font-family:'Fira Code',monospace;
            font-weight:500;
            letter-spacing:.14em;
            text-transform:uppercase;
            color:rgba(57,255,20,0.55);
            margin-bottom:1.1rem;
            padding:.25rem .75rem;
            border:1px solid rgba(57,255,20,0.12);
            background:rgba(57,255,20,0.04);
            border-radius:4px;
        }

        /* ── Timeline ───────────────────────────────── */
        .timeline-line {
            position:absolute; left:1.25rem; top:3rem; bottom:-3rem;
            width:1px;
            background:linear-gradient(to bottom,rgba(57,255,20,0.15),transparent);
        }

        /* ── Code block ─────────────────────────────── */
        .code-block {
            display:inline-flex; align-items:center; gap:.75rem;
            background:#080808;
            border:1px solid #1a1a1a;
            border-radius:6px;
            padding:.6rem 1.25rem;
            font-family:'Fira Code',monospace;
        }

        /* ── Feature card number ────────────────────── */
        .feature-num {
            font-family:'Fira Code',monospace;
            font-size:.65rem;
            color:#1e1e1e;
            letter-spacing:.08em;
            font-weight:700;
        }

        /* ── Horizontal rule ────────────────────────── */
        .neon-rule {
            height:1px;
            background:linear-gradient(90deg,transparent,rgba(57,255,20,0.25),transparent);
        }
    </style>
</head>
<body class="antialiased" style="background:#0a0a0a;color:#d8d8d8;font-family:'Figtree',system-ui,sans-serif;">

@include('welcome.nav')
@include('welcome.hero')
@include('welcome.marquee')
@include('welcome.stats')
@include('welcome.features')
@include('welcome.preview')
@include('welcome.how_it_works')
@include('welcome.testimonials')
@include('welcome.pricing')
@include('welcome.cta')
@include('welcome.footer')

<script>
    // Mobile nav
    const navToggle  = document.getElementById('nav-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    if (navToggle && mobileMenu) {
        navToggle.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
        mobileMenu.querySelectorAll('a').forEach(a =>
            a.addEventListener('click', () => mobileMenu.classList.add('hidden'))
        );
    }

    // Scroll reveal
    const revealObs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('visible'); revealObs.unobserve(e.target); }
        });
    }, { threshold: 0.08 });
    document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

    // Stat counters
    const statObs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            const el = e.target, target = parseInt(el.dataset.count, 10),
                  suffix = el.dataset.suffix || '', dur = 1600, t0 = performance.now();
            const tick = now => {
                const p = Math.min((now - t0) / dur, 1), ease = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.floor(ease * target).toLocaleString() + suffix;
                if (p < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
            statObs.unobserve(el);
        });
    }, { threshold: 0.5 });
    document.querySelectorAll('[data-count]').forEach(el => statObs.observe(el));

    // Billing toggle
    let annual = false;
    function toggleBilling() {
        annual = !annual;
        const dot = document.getElementById('billing-dot');
        if (dot) dot.style.transform = annual ? 'translateX(24px)' : 'translateX(0)';
        document.querySelectorAll('[data-price]').forEach(el => {
            const map = { pro: ['$9','$7'], team: ['$29','$23'] };
            if (map[el.dataset.price]) el.textContent = map[el.dataset.price][annual ? 1 : 0];
        });
        document.querySelectorAll('[data-period]').forEach(el => {
            el.textContent = annual ? '/mo · billed annually' : el.dataset.period;
        });
    }
    const bt = document.getElementById('billing-toggle');
    if (bt) bt.addEventListener('click', toggleBilling);
</script>

</body>
</html>
