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
        @keyframes marquee { from{transform:translateX(0)} to{transform:translateX(-50%)} }
        @keyframes blink   { 0%,100%{opacity:1} 50%{opacity:0} }

        .marquee-track { animation:marquee 32s linear infinite; white-space:nowrap; display:flex; }
        .marquee-track:hover { animation-play-state:paused; }
        .marquee-mask {
            -webkit-mask-image:linear-gradient(90deg,transparent,#000 8%,#000 92%,transparent);
            mask-image:linear-gradient(90deg,transparent,#000 8%,#000 92%,transparent);
        }
        .cursor-blink { animation:blink 1s step-end infinite; }

        /* ── Scroll reveal ──────────────────────────── */
        .reveal { opacity:0; transform:translateY(16px); transition:opacity .5s ease, transform .5s ease; }
        .reveal.visible { opacity:1; transform:translateY(0); }

        /* ── Nav ────────────────────────────────────── */
        .nav-surface {
            background:rgba(10,10,10,0.94);
            border-bottom:1px solid #161616;
            backdrop-filter:blur(16px);
            -webkit-backdrop-filter:blur(16px);
        }

        /* ── Cards ──────────────────────────────────── */
        .card {
            background:#111;
            border:1px solid #1e1e1e;
            border-radius:12px;
            transition:border-color .2s ease;
        }
        .card:hover { border-color:#2e2e2e; }

        .card-featured {
            background:rgba(57,255,20,0.03);
            border:1px solid rgba(57,255,20,0.2);
            border-radius:12px;
        }

        /* ── Buttons ────────────────────────────────── */
        .btn-primary {
            display:inline-flex; align-items:center;
            background:#39FF14; color:#000;
            padding:.75rem 1.75rem;
            border-radius:8px;
            font-size:.875rem;
            font-family:'Fira Code',monospace;
            font-weight:700;
            letter-spacing:.02em;
            transition:opacity .15s ease, transform .15s ease;
        }
        .btn-primary:hover { opacity:.85; transform:translateY(-1px); }

        .btn-secondary {
            display:inline-flex; align-items:center; gap:.5rem;
            border:1px solid #222; color:#888;
            padding:.75rem 1.75rem;
            border-radius:8px;
            font-size:.875rem;
            font-family:'Fira Code',monospace;
            transition:border-color .15s, color .15s, transform .15s;
        }
        .btn-secondary:hover { border-color:#444; color:#ccc; transform:translateY(-1px); }

        /* ── Section label ──────────────────────────── */
        .section-label {
            display:block;
            font-size:.7rem;
            font-family:'Fira Code',monospace;
            font-weight:500;
            letter-spacing:.12em;
            text-transform:uppercase;
            color:rgba(57,255,20,0.6);
            margin-bottom:1rem;
        }

        /* ── Timeline ───────────────────────────────── */
        .timeline-line {
            position:absolute; left:1.25rem; top:3rem; bottom:-3rem;
            width:1px;
            background:linear-gradient(to bottom,#1e1e1e,transparent);
        }

        /* ── Code block ─────────────────────────────── */
        .code-block {
            display:inline-flex; align-items:center; gap:.75rem;
            background:#0d0d0d;
            border:1px solid #1e1e1e;
            border-radius:8px;
            padding:.625rem 1.25rem;
            font-family:'Fira Code',monospace;
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
