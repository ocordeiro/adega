<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adega — Vinhos Selecionados</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --burgundy:   #4a0e1f;
            --wine:       #6b1528;
            --wine-mid:   #8b1a35;
            --rose:       #c4506a;
            --gold:       #c8a96e;
            --gold-light: #e2c98a;
            --cream:      #f8f4ef;
            --warm-white: #fdfaf6;
            --charcoal:   #1a1a1a;
            --text-muted: #6b5b5b;
            --border:     #e8ddd5;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--warm-white);
            color: var(--charcoal);
            -webkit-font-smoothing: antialiased;
        }

        /* ── NAVBAR ─────────────────────────────────────────────────────── */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 0 2.5rem;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: transparent;
            transition: background .35s, backdrop-filter .35s, box-shadow .35s;
        }

        .navbar.scrolled {
            background: rgba(74, 14, 31, 0.96);
            backdrop-filter: blur(12px);
            box-shadow: 0 1px 0 rgba(255,255,255,.08);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
        }

        .navbar-logo {
            width: 36px;
            height: 36px;
            border: 1.5px solid var(--gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .navbar-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .navbar-links {
            display: flex;
            align-items: center;
            gap: 2.5rem;
            list-style: none;
        }

        .navbar-links a {
            color: rgba(255,255,255,.8);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            transition: color .2s;
        }

        .navbar-links a:hover { color: var(--gold-light); }

        .navbar-links .btn-nav {
            color: var(--gold);
            border: 1px solid rgba(200,169,110,.4);
            padding: 0.45rem 1.25rem;
            border-radius: 2px;
            transition: background .2s, color .2s;
        }

        .navbar-links .btn-nav:hover {
            background: var(--gold);
            color: var(--burgundy);
        }

        /* ── HERO ────────────────────────────────────────────────────────── */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 60% 50%, rgba(107, 21, 40, 0.35) 0%, transparent 70%),
                linear-gradient(160deg, #0d0508 0%, #1c0610 35%, #2d0a18 60%, #1a0610 100%);
        }

        /* Decorative vineyard texture via repeating pattern */
        .hero-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 80px,
                    rgba(255,255,255,.015) 80px,
                    rgba(255,255,255,.015) 81px
                ),
                repeating-linear-gradient(
                    90deg,
                    transparent,
                    transparent 80px,
                    rgba(255,255,255,.015) 80px,
                    rgba(255,255,255,.015) 81px
                );
        }

        /* Glowing orbs */
        .hero-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.25;
            pointer-events: none;
        }

        .hero-orb-1 {
            width: 500px; height: 500px;
            background: var(--wine-mid);
            top: -100px; right: 10%;
        }

        .hero-orb-2 {
            width: 300px; height: 300px;
            background: var(--gold);
            bottom: 10%; left: 5%;
            opacity: 0.1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 2rem 1.5rem;
            max-width: 860px;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 2rem;
        }

        .hero-eyebrow::before,
        .hero-eyebrow::after {
            content: '';
            display: block;
            width: 40px;
            height: 1px;
            background: var(--gold);
            opacity: 0.5;
        }

        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(3.5rem, 8vw, 7rem);
            font-weight: 300;
            line-height: 1.05;
            color: #fff;
            margin-bottom: 0.25rem;
            letter-spacing: -0.01em;
        }

        .hero-title em {
            font-style: italic;
            color: var(--gold-light);
        }

        .hero-subtitle {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.1rem, 2.5vw, 1.5rem);
            font-weight: 300;
            color: rgba(255,255,255,.5);
            margin-bottom: 3rem;
            letter-spacing: 0.05em;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.25rem;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'Inter', sans-serif;
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            text-decoration: none;
            padding: 1rem 2.25rem;
            border-radius: 2px;
            transition: all .25s;
        }

        .btn-primary {
            background: var(--gold);
            color: var(--burgundy);
        }

        .btn-primary:hover {
            background: var(--gold-light);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(200,169,110,.3);
        }

        .btn-ghost {
            border: 1px solid rgba(255,255,255,.25);
            color: rgba(255,255,255,.8);
        }

        .btn-ghost:hover {
            border-color: rgba(255,255,255,.5);
            color: #fff;
            background: rgba(255,255,255,.05);
        }

        .hero-scroll {
            position: absolute;
            bottom: 2.5rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255,255,255,.35);
            font-size: 0.65rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            z-index: 2;
        }

        .scroll-line {
            width: 1px;
            height: 48px;
            background: linear-gradient(to bottom, rgba(255,255,255,.3), transparent);
            animation: scrollPulse 2s ease-in-out infinite;
        }

        @keyframes scrollPulse {
            0%, 100% { opacity: 0.4; transform: scaleY(1); }
            50% { opacity: 1; transform: scaleY(1.1); }
        }

        /* ── STATS BANNER ─────────────────────────────────────────────────── */
        .stats-banner {
            background: var(--burgundy);
            border-top: 1px solid rgba(200,169,110,.15);
            border-bottom: 1px solid rgba(200,169,110,.15);
        }

        .stats-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
        }

        .stat-item {
            padding: 2.25rem 1rem;
            text-align: center;
            border-right: 1px solid rgba(255,255,255,.08);
        }

        .stat-item:last-child { border-right: none; }

        .stat-value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.75rem;
            font-weight: 300;
            color: var(--gold-light);
            line-height: 1;
            margin-bottom: 0.4rem;
        }

        .stat-label {
            font-size: 0.65rem;
            font-weight: 500;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255,255,255,.4);
        }

        /* ── TYPES ────────────────────────────────────────────────────────── */
        .types-section {
            padding: 7rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 3.5rem;
        }

        .section-label {
            font-size: 0.65rem;
            font-weight: 500;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 0.75rem;
        }

        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 300;
            color: var(--charcoal);
            line-height: 1.1;
        }

        .section-link {
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--wine);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 0.25rem;
            border-bottom: 1px solid var(--wine);
            white-space: nowrap;
            transition: gap .2s;
        }

        .section-link:hover { gap: 0.85rem; }

        .types-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1px;
            background: var(--border);
            border: 1px solid var(--border);
        }

        .type-card {
            background: var(--warm-white);
            padding: 2rem 1.5rem;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.85rem;
            transition: background .2s;
            text-align: center;
        }

        .type-card:hover { background: var(--cream); }

        .type-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        .type-icon-red    { background: #f9eff2; }
        .type-icon-white  { background: #fdf9ee; }
        .type-icon-rose   { background: #fdf0f3; }
        .type-icon-spark  { background: #f3f0fc; }
        .type-icon-desert { background: #fef5e4; }
        .type-icon-other  { background: #f0f6fa; }

        .type-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.05rem;
            font-weight: 400;
            color: var(--charcoal);
        }

        .type-count {
            font-size: 0.7rem;
            color: var(--text-muted);
            letter-spacing: 0.05em;
        }

        /* ── DIVIDER ─────────────────────────────────────────────────────── */
        .divider-full {
            background: var(--burgundy);
            padding: 6rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .divider-full::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 80% at 50% 50%, rgba(107,21,40,.6) 0%, transparent 70%);
        }

        .divider-full-content { position: relative; z-index: 1; }

        .divider-quote {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.5rem, 3.5vw, 2.5rem);
            font-weight: 300;
            font-style: italic;
            color: rgba(255,255,255,.85);
            max-width: 700px;
            margin: 0 auto 1.5rem;
            line-height: 1.4;
        }

        .divider-attr {
            font-size: 0.7rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gold);
        }

        /* ── WINE GRID ───────────────────────────────────────────────────── */
        .wines-section {
            padding: 7rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .wine-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 0;
            background: var(--border);
            border: 1px solid var(--border);
        }

        .wine-card {
            background: var(--warm-white);
            display: flex;
            flex-direction: column;
            text-decoration: none;
            transition: background .2s;
            position: relative;
            overflow: hidden;
        }

        .wine-card:hover { background: var(--cream); }

        .wine-card-image {
            aspect-ratio: 3/4;
            background: linear-gradient(160deg, var(--burgundy) 0%, #2a0a14 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        /* Decorative bottle silhouette via CSS */
        .wine-bottle {
            position: absolute;
            bottom: 0;
            width: 52px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .wine-bottle-neck {
            width: 14px;
            height: 60px;
            background: rgba(255,255,255,.06);
            border-radius: 7px 7px 0 0;
        }

        .wine-bottle-body {
            width: 44px;
            height: 110px;
            background: rgba(255,255,255,.06);
            border-radius: 4px 4px 8px 8px;
        }

        .wine-card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(74,14,31,.7) 0%, transparent 50%);
        }

        .wine-card-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            font-size: 0.6rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--gold);
            background: rgba(74,14,31,.7);
            backdrop-filter: blur(4px);
            padding: 0.3rem 0.6rem;
            border: 1px solid rgba(200,169,110,.3);
            border-radius: 2px;
        }

        .wine-card-vintage {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.75rem;
            font-weight: 300;
            color: rgba(255,255,255,.2);
            line-height: 1;
        }

        .wine-card-body {
            padding: 1.35rem 1.25rem 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            border-top: 1px solid var(--border);
        }

        .wine-card-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.2rem;
            font-weight: 400;
            color: var(--charcoal);
            line-height: 1.25;
            margin-bottom: 0.35rem;
        }

        .wine-card-meta {
            font-size: 0.72rem;
            color: var(--text-muted);
            letter-spacing: 0.03em;
            flex: 1;
            margin-bottom: 1rem;
        }

        .wine-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .wine-card-price {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--wine);
            line-height: 1;
        }

        .wine-card-price-label {
            font-size: 0.6rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .wine-card-arrow {
            width: 32px;
            height: 32px;
            border: 1px solid var(--border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: var(--text-muted);
            transition: all .2s;
        }

        .wine-card:hover .wine-card-arrow {
            background: var(--wine);
            border-color: var(--wine);
            color: #fff;
        }

        .no-wines {
            grid-column: 1/-1;
            text-align: center;
            padding: 5rem 2rem;
            color: var(--text-muted);
        }

        .no-wines p {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 0.5rem;
        }

        /* ── CTA ─────────────────────────────────────────────────────────── */
        .cta-section {
            margin: 0 2rem 7rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 7rem;
            background: var(--burgundy);
            border-radius: 4px;
            padding: 5rem 4rem;
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            gap: 3rem;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: rgba(107,21,40,.5);
            pointer-events: none;
        }

        .cta-label {
            font-size: 0.65rem;
            font-weight: 500;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1rem;
        }

        .cta-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.75rem, 3.5vw, 2.75rem);
            font-weight: 300;
            color: #fff;
            line-height: 1.15;
        }

        /* ── FOOTER ──────────────────────────────────────────────────────── */
        footer {
            background: var(--charcoal);
            color: rgba(255,255,255,.4);
            padding: 3rem 2rem;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .footer-brand {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.35rem;
            font-weight: 600;
            color: rgba(255,255,255,.6);
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .footer-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .footer-links a {
            font-size: 0.72rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255,255,255,.35);
            text-decoration: none;
            transition: color .2s;
        }

        .footer-links a:hover { color: var(--gold); }

        .footer-copy {
            font-size: 0.72rem;
            color: rgba(255,255,255,.2);
        }

        /* ── RESPONSIVE ──────────────────────────────────────────────────── */
        @media (max-width: 768px) {
            .navbar-links { display: none; }
            .stats-inner { grid-template-columns: repeat(2, 1fr); }
            .stat-item:nth-child(2) { border-right: none; }
            .section-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .cta-section { grid-template-columns: 1fr; padding: 3rem 2rem; }
            .footer-inner { flex-direction: column; text-align: center; }
            .footer-links { justify-content: center; flex-wrap: wrap; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
    <a href="/" class="navbar-brand">
        <div class="navbar-logo">🍷</div>
        <span class="navbar-name">Adega</span>
    </a>
    <ul class="navbar-links">
        <li><a href="/">Início</a></li>
        <li><a href="{{ route('catalogo') }}">Catálogo</a></li>
        <li><a href="{{ route('catalogo') }}" class="btn-nav">Ver Vinhos</a></li>
    </ul>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>

    <div class="hero-content">
        <div class="hero-eyebrow">Adega Selecionada</div>
        <h1 class="hero-title">
            Arte &amp;<br><em>Terroir</em>
        </h1>
        <p class="hero-subtitle">Rótulos selecionados das melhores regiões vinícolas do mundo</p>
        <div class="hero-actions">
            <a href="{{ route('catalogo') }}" class="btn btn-primary">
                Explorar Catálogo
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="#destaques" class="btn btn-ghost">Ver Destaques</a>
        </div>
    </div>

    <div class="hero-scroll">
        <div class="scroll-line"></div>
        <span>Scroll</span>
    </div>
</section>

<!-- STATS -->
<div class="stats-banner">
    <div class="stats-inner">
        <div class="stat-item">
            <div class="stat-value">{{ \App\Models\Wine::where('is_active', true)->count() ?: '—' }}</div>
            <div class="stat-label">Rótulos</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ \App\Models\Country::count() ?: '—' }}</div>
            <div class="stat-label">Países</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ \App\Models\Producer::count() ?: '—' }}</div>
            <div class="stat-label">Produtores</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ \App\Models\WineType::count() ?: '—' }}</div>
            <div class="stat-label">Categorias</div>
        </div>
    </div>
</div>

<!-- TIPOS -->
@if($types->isNotEmpty())
<section class="types-section">
    <div class="section-header">
        <div>
            <p class="section-label">Estilos</p>
            <h2 class="section-title">Explore por<br>tipo de vinho</h2>
        </div>
        <a href="{{ route('catalogo') }}" class="section-link">
            Ver catálogo completo
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>

    <div class="types-grid">
        @php
        $typeIcons = [
            'tinto'      => ['🍷', 'type-icon-red'],
            'branco'     => ['🥂', 'type-icon-white'],
            'rose'       => ['🌸', 'type-icon-rose'],
            'espumante'  => ['✨', 'type-icon-spark'],
            'sobremesa'  => ['🍯', 'type-icon-desert'],
        ];
        @endphp
        @foreach($types as $type)
        @php
            $key = collect(array_keys($typeIcons))->first(fn($k) => str_contains(strtolower($type->slug), $k));
            [$icon, $iconClass] = $typeIcons[$key] ?? ['🍾', 'type-icon-other'];
        @endphp
        <a href="{{ route('catalogo', ['tipo' => $type->slug]) }}" class="type-card">
            <div class="type-icon {{ $iconClass }}">{{ $icon }}</div>
            <div class="type-name">{{ $type->name }}</div>
            <div class="type-count">{{ $type->wines_count }} {{ $type->wines_count === 1 ? 'rótulo' : 'rótulos' }}</div>
        </a>
        @endforeach
    </div>
</section>
@endif

<!-- DIVIDER QUOTE -->
<div class="divider-full">
    <div class="divider-full-content">
        <p class="divider-quote">"O vinho é a poesia da terra — cada garrafa conta uma história de sol, solo e tradição."</p>
        <p class="divider-attr">Arte da vinicultura</p>
    </div>
</div>

<!-- DESTAQUES -->
<section class="wines-section" id="destaques">
    <div class="section-header">
        <div>
            <p class="section-label">Seleção</p>
            <h2 class="section-title">Destaques<br>da Adega</h2>
        </div>
        @if($wines->isNotEmpty())
        <a href="{{ route('catalogo') }}" class="section-link">
            Ver todos
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        @endif
    </div>

    <div class="wine-grid">
        @forelse($wines as $wine)
        <a href="{{ route('catalogo') }}" class="wine-card">
            <div class="wine-card-image">
                <div class="wine-card-overlay"></div>
                <div class="wine-bottle">
                    <div class="wine-bottle-neck"></div>
                    <div class="wine-bottle-body"></div>
                </div>
                @if($wine->wineType)
                <div class="wine-card-badge">{{ $wine->wineType->name }}</div>
                @endif
                @if($wine->vintage)
                <div class="wine-card-vintage">{{ $wine->vintage }}</div>
                @endif
            </div>
            <div class="wine-card-body">
                <div class="wine-card-name">{{ $wine->name }}</div>
                <div class="wine-card-meta">
                    @if($wine->producer){{ $wine->producer->name }}@endif
                    @if($wine->producer && $wine->country) · @endif
                    @if($wine->country){{ $wine->country->name }}@endif
                </div>
                <div class="wine-card-footer">
                    @if($wine->sale_price)
                    <div>
                        <div class="wine-card-price-label">A partir de</div>
                        <div class="wine-card-price">R$&nbsp;{{ number_format($wine->sale_price, 2, ',', '.') }}</div>
                    </div>
                    @else
                    <div></div>
                    @endif
                    <div class="wine-card-arrow">→</div>
                </div>
            </div>
        </a>
        @empty
        <div class="no-wines">
            <p>Nenhum vinho cadastrado ainda.</p>
            <small>Acesse o painel admin para começar.</small>
        </div>
        @endforelse
    </div>
</section>

<!-- CTA -->
<div style="padding: 0 2rem; max-width: 1200px; margin: 0 auto 7rem;">
    <div class="cta-section">
        <div>
            <p class="cta-label">Catálogo completo</p>
            <h2 class="cta-title">Descubra todos os rótulos<br>disponíveis na nossa adega</h2>
        </div>
        <a href="{{ route('catalogo') }}" class="btn btn-primary" style="white-space: nowrap; position: relative; z-index: 1;">
            Ver Catálogo
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</div>

<!-- FOOTER -->
<footer>
    <div class="footer-inner">
        <div class="footer-brand">Adega</div>
        <ul class="footer-links">
            <li><a href="/">Início</a></li>
            <li><a href="{{ route('catalogo') }}">Catálogo</a></li>
            <li><a href="/admin">Admin</a></li>
        </ul>
        <p class="footer-copy">&copy; {{ date('Y') }} Adega. Todos os direitos reservados.</p>
    </div>
</footer>

<script>
    // Navbar scroll effect
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 60);
    });
    // Activate on load if already scrolled
    if (window.scrollY > 60) navbar.classList.add('scrolled');
</script>
</body>
</html>
