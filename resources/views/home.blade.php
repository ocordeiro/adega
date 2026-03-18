<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adega — Vinhos Selecionados</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --wine: #7b1f3a;
            --wine-dark: #5a1629;
            --wine-light: #a83254;
            --gold: #c9a84c;
            --cream: #faf7f2;
            --text: #2d1a1a;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Georgia', serif;
            background: var(--cream);
            color: var(--text);
        }

        /* NAV */
        nav {
            background: var(--wine-dark);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            color: var(--gold);
            font-size: 1.75rem;
            font-weight: bold;
            letter-spacing: 0.05em;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            list-style: none;
        }

        .nav-links a {
            color: #e8d5c0;
            text-decoration: none;
            font-size: 0.95rem;
            transition: color .2s;
        }

        .nav-links a:hover { color: var(--gold); }

        /* HERO */
        .hero {
            background: linear-gradient(135deg, var(--wine-dark) 0%, var(--wine) 60%, var(--wine-light) 100%);
            color: #fff;
            text-align: center;
            padding: 6rem 2rem 5rem;
        }

        .hero-eyebrow {
            font-size: 0.85rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1rem;
        }

        .hero h1 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            line-height: 1.15;
            margin-bottom: 1.25rem;
        }

        .hero p {
            font-size: 1.15rem;
            color: #e8d5c0;
            max-width: 540px;
            margin: 0 auto 2.5rem;
        }

        .btn {
            display: inline-block;
            padding: 0.85rem 2.25rem;
            border-radius: 3px;
            font-size: 0.95rem;
            text-decoration: none;
            letter-spacing: 0.05em;
            transition: all .2s;
        }

        .btn-gold {
            background: var(--gold);
            color: var(--wine-dark);
            font-weight: bold;
        }

        .btn-gold:hover { background: #d4b96a; }

        .btn-outline {
            border: 2px solid var(--gold);
            color: var(--gold);
            margin-left: 1rem;
        }

        .btn-outline:hover { background: var(--gold); color: var(--wine-dark); }

        /* SECTION */
        .section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }

        .section-title {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .section-title h2 {
            font-size: 2rem;
            color: var(--wine);
            margin-bottom: 0.5rem;
        }

        .section-title p {
            color: #7a5c5c;
            font-size: 1rem;
        }

        /* TYPES STRIP */
        .types-strip {
            background: var(--wine);
            padding: 2.5rem 2rem;
        }

        .types-strip .section-title h2 { color: var(--gold); }
        .types-strip .section-title p { color: #e8d5c0; }

        .types-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            max-width: 900px;
            margin: 0 auto;
        }

        .type-chip {
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(201,168,76,.4);
            color: #e8d5c0;
            padding: 0.6rem 1.5rem;
            border-radius: 2rem;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all .2s;
        }

        .type-chip:hover {
            background: var(--gold);
            color: var(--wine-dark);
            border-color: var(--gold);
        }

        .type-chip .count {
            font-size: 0.75rem;
            opacity: 0.75;
            margin-left: 0.35rem;
        }

        /* WINE GRID */
        .wine-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 1.75rem;
        }

        .wine-card {
            background: #fff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(123,31,58,.08);
            transition: transform .2s, box-shadow .2s;
            display: flex;
            flex-direction: column;
        }

        .wine-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(123,31,58,.15);
        }

        .wine-card-img {
            height: 200px;
            background: linear-gradient(160deg, var(--wine) 0%, var(--wine-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
        }

        .wine-card-body {
            padding: 1.1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .wine-badge {
            display: inline-block;
            background: #f3e8ef;
            color: var(--wine);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.2rem 0.6rem;
            border-radius: 2rem;
            margin-bottom: 0.5rem;
        }

        .wine-name {
            font-size: 1.05rem;
            font-weight: bold;
            color: var(--text);
            margin-bottom: 0.3rem;
            line-height: 1.3;
        }

        .wine-meta {
            font-size: 0.82rem;
            color: #9a7070;
            margin-bottom: auto;
        }

        .wine-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--wine);
            margin-top: 0.85rem;
        }

        .wine-vintage {
            font-size: 0.8rem;
            color: #9a7070;
        }

        .no-wines {
            grid-column: 1/-1;
            text-align: center;
            color: #9a7070;
            font-size: 1.1rem;
            padding: 3rem 0;
        }

        /* FOOTER */
        footer {
            background: var(--wine-dark);
            color: #e8d5c0;
            text-align: center;
            padding: 2.5rem 2rem;
            font-size: 0.9rem;
        }

        footer a {
            color: var(--gold);
            text-decoration: none;
        }

        footer a:hover { text-decoration: underline; }

        @media (max-width: 640px) {
            .btn-outline { margin: 1rem 0 0; }
        }
    </style>
</head>
<body>

<nav>
    <a href="/" class="nav-brand">🍷 Adega</a>
    <ul class="nav-links">
        <li><a href="/">Início</a></li>
        <li><a href="{{ route('catalogo') }}">Catálogo</a></li>
        <li><a href="/admin" target="_blank">Admin</a></li>
    </ul>
</nav>

<!-- HERO -->
<section class="hero">
    <p class="hero-eyebrow">Bem-vindo à nossa adega</p>
    <h1>Vinhos selecionados<br>para cada ocasião</h1>
    <p>Explore nosso catálogo com rótulos nacionais e importados, com harmonização e informações detalhadas.</p>
    <a href="{{ route('catalogo') }}" class="btn btn-gold">Ver Catálogo Completo</a>
    <a href="/admin" class="btn btn-outline">Área Admin</a>
</section>

<!-- TIPOS -->
@if($types->isNotEmpty())
<section class="types-strip">
    <div class="section-title">
        <h2>Explore por tipo</h2>
        <p>Encontre o vinho certo para cada momento</p>
    </div>
    <div class="types-grid">
        @foreach($types as $type)
        <a href="{{ route('catalogo', ['tipo' => $type->slug]) }}" class="type-chip">
            {{ $type->name }}<span class="count">({{ $type->wines_count }})</span>
        </a>
        @endforeach
    </div>
</section>
@endif

<!-- DESTAQUES -->
<div class="section">
    <div class="section-title">
        <h2>Destaques da Adega</h2>
        <p>Rótulos selecionados, prontos para sua mesa</p>
    </div>

    <div class="wine-grid">
        @forelse($wines as $wine)
        <div class="wine-card">
            <div class="wine-card-img">🍷</div>
            <div class="wine-card-body">
                @if($wine->wineType)
                <span class="wine-badge">{{ $wine->wineType->name }}</span>
                @endif
                <div class="wine-name">{{ $wine->name }}</div>
                <div class="wine-meta">
                    @if($wine->producer) {{ $wine->producer->name }} · @endif
                    @if($wine->country) {{ $wine->country->name }} @endif
                </div>
                <div class="wine-price">
                    R$ {{ number_format($wine->sale_price, 2, ',', '.') }}
                    @if($wine->vintage)
                    <span class="wine-vintage">· Safra {{ $wine->vintage }}</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="no-wines">Nenhum vinho cadastrado ainda.</div>
        @endforelse
    </div>

    @if($wines->isNotEmpty())
    <div style="text-align:center; margin-top: 2.5rem;">
        <a href="{{ route('catalogo') }}" class="btn btn-gold">Ver todos os vinhos</a>
    </div>
    @endif
</div>

<footer>
    <p>&copy; {{ date('Y') }} Adega. Todos os direitos reservados. · <a href="/admin">Painel Admin</a></p>
</footer>

</body>
</html>
