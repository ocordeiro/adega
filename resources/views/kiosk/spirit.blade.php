<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>{{ $spirit->name }} — Adega Sommelier</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --gold:    #c8a96e;
            --gold-lt: #e2c98a;
            --cream:   #f5f0eb;
            --muted:   rgba(245,240,235,.45);
            --base:    #0d0d0f;
            --surface: rgba(255,255,255,.04);
            --border:  rgba(255,255,255,.08);
            --slide-dur: 380ms;
            --ease-out: cubic-bezier(.25,.46,.45,.94);
            --ease-in:  cubic-bezier(.55,.06,.68,.19);
        }

        html, body {
            width: 100%; height: 100%; overflow: hidden;
            background: var(--base);
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            user-select: none; -webkit-user-select: none; -webkit-touch-callout: none;
        }

        /* ── CLOSE BUTTON ─────────────────────────────── */
        .btn-close {
            position: fixed; top: 1.25rem; right: 1.5rem; z-index: 50;
            display: flex; align-items: center; justify-content: center;
            width: 2.4rem; height: 2.4rem;
            background: rgba(13,13,15,.75); border: 1px solid var(--border);
            border-radius: 100px; color: rgba(245,240,235,.55);
            text-decoration: none;
            backdrop-filter: blur(12px); transition: background .2s, color .2s;
            touch-action: manipulation;
        }
        .btn-close:active { background: rgba(255,255,255,.06); color: var(--cream); }

        /* ── DOTS ─────────────────────────────────────── */
        .dots {
            position: fixed; z-index: 40;
            display: flex; align-items: center; gap: .55rem;
            bottom: 1.75rem; left: 50%; transform: translateX(-50%);
        }
        .dot {
            width: 6px; height: 6px; border-radius: 100px;
            background: rgba(255,255,255,.2);
            transition: all .3s var(--ease-out);
            cursor: pointer; touch-action: manipulation; flex-shrink: 0;
        }
        .dot.active { width: 22px; background: var(--gold); }

        /* ── TRACK ────────────────────────────────────── */
        .track-wrap { width: 100vw; height: 100vh; overflow: visible; cursor: grab; touch-action: pan-y; }
        .track-wrap:active { cursor: grabbing; }
        .track { display: flex; flex-direction: row; width: 200vw; height: 100%; will-change: transform; }
        .slide {
            width: 100vw; height: 100vh; flex-shrink: 0;
            overflow-y: auto; overflow-x: hidden;
            -webkit-overflow-scrolling: touch; overscroll-behavior: contain;
        }

        /* ── Shared ───────────────────────────────────── */
        .sub-label {
            font-size: .64rem; letter-spacing: .2em; text-transform: uppercase;
            color: var(--muted); margin-bottom: .6rem;
        }
        .section-header { padding: 4.5rem clamp(1.5rem, 4vw, 2.5rem) 1.75rem; text-align: center; max-width: 900px; margin-inline: auto; width: 100%; }
        @media (orientation: portrait) and (max-width: 599px) { .section-header { padding: 4rem 1rem 1.25rem; } }
        .section-label {
            font-size: .67rem; letter-spacing: .22em; text-transform: uppercase;
            color: var(--gold); opacity: .7; margin-bottom: .5rem;
        }
        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.5rem, 3vw, 2.4rem); font-weight: 300; color: var(--cream);
        }
        .section-sub { margin-top: .4rem; font-size: .84rem; font-weight: 300; color: var(--muted); }
        .divider { height: 1px; background: var(--border); }

        /* ════════════════════════════════════════════════
           SLIDE 1 — DESTILADO
        ════════════════════════════════════════════════ */
        .slide-spirit {
            background:
                radial-gradient(ellipse 55% 40% at 65% 35%, rgba(200,169,110,.06) 0%, transparent 60%),
                var(--base);
        }

        .spirit-slide-inner { min-height: 100vh; display: flex; flex-direction: column; }
        .spirit-slide-inner > .spirit-layout { max-width: 1600px; width: 100%; align-self: center; }

        .spirit-layout {
            flex: 1;
            display: grid;
            grid-template-areas:
                "photo title"
                "photo meta"
                "photo extras"
                "photo drinks";
            grid-template-columns: clamp(220px, 28vw, 340px) 1fr;
            grid-template-rows: auto auto auto 1fr;
            column-gap: 2.5rem;
            row-gap: 1rem;
            padding: 4.5rem clamp(1.5rem, 3vw, 3rem) 2rem;
            align-content: start;
        }
        .spirit-photo-col { grid-area: photo;  align-self: start; display: flex; flex-direction: column; align-items: center; gap: .75rem; min-width: 0; }
        .spirit-title      { grid-area: title;  display: flex; flex-direction: column; gap: .55rem; min-width: 0; }
        .spirit-meta-box   { grid-area: meta;   min-width: 0; }
        .spirit-extras     { grid-area: extras; display: flex; flex-direction: column; gap: .8rem; min-width: 0; }
        .spirit-drinks     { grid-area: drinks; display: flex; flex-direction: column; gap: .75rem; padding-bottom: .5rem; min-width: 0; overflow-x: clip; }

        /* Wide landscape: extras + drinks side by side */
        @media (orientation: landscape) and (min-width: 1024px) {
            .spirit-layout {
                grid-template-areas:
                    "photo title  title"
                    "photo meta   meta"
                    "photo extras drinks";
                grid-template-columns: clamp(220px, 28vw, 340px) 1fr 1fr;
                grid-template-rows: auto auto 1fr;
            }
        }

        @media (orientation: portrait) and (max-width: 599px) {
            .spirit-layout {
                grid-template-areas:
                    "photo title"
                    "photo meta"
                    "extras extras"
                    "drinks drinks";
                grid-template-columns: 30% 1fr;
                grid-template-rows: auto auto auto auto;
                column-gap: .85rem;
                row-gap: .7rem;
                padding: 3rem 1rem 1rem;
                align-content: start;
            }
            .spirit-photo-col { align-self: stretch; grid-row: 1 / 3; overflow: hidden; }
            .spirit-meta-box  { align-self: start; }
            .spirit-drinks    { gap: .55rem; }
        }

        @media (orientation: portrait) and (min-width: 600px) {
            .spirit-layout {
                grid-template-columns: clamp(200px, 32vw, 300px) 1fr;
                column-gap: 2rem;
                padding: 4rem clamp(1.5rem, 3vw, 2.5rem) 2rem;
                flex: 0;
            }
        }

        @media (orientation: portrait) and (max-width: 374px) {
            .spirit-layout {
                grid-template-columns: 28% 1fr;
                column-gap: .7rem;
                padding: 2.75rem .75rem .75rem;
            }
        }

        /* ── Photo ──────────────────────────────────── */
        .spirit-photo-frame { position: relative; width: 100%; }
        @media (orientation: portrait) and (max-width: 599px) {
            .spirit-photo-col { display: flex; flex-direction: column; }
            .spirit-photo-frame { flex: 1; min-height: 0; }
            .spirit-photo { height: 100% !important; width: 100% !important; object-fit: cover !important; object-position: top !important; aspect-ratio: unset !important; }
            .spirit-photo-placeholder { height: 100%; aspect-ratio: unset; }
        }

        .spirit-photo-shadow {
            position: absolute; inset: 0; border-radius: 12px;
            box-shadow: 0 0 50px rgba(200,169,110,.12), 0 20px 44px rgba(0,0,0,.7);
        }
        .spirit-photo { position: relative; z-index: 1; width: 100%; border-radius: 12px; object-fit: cover; display: block; }
        .spirit-photo-placeholder {
            position: relative; z-index: 1; width: 100%; aspect-ratio: 1/2;
            background: linear-gradient(160deg, #1e1e22, #141418);
            border-radius: 12px; border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
        }

        /* ── Title ───────────────────────────────────── */
        .spirit-badge {
            display: inline-flex; align-items: center; gap: .35rem;
            background: rgba(200,169,110,.1); border: 1px solid rgba(200,169,110,.22);
            border-radius: 100px; padding: .25rem .8rem;
            font-size: .67rem; font-weight: 500; letter-spacing: .13em; text-transform: uppercase;
            color: var(--gold); width: fit-content;
        }
        .spirit-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.6rem, 3.3vw, 3rem);
            line-height: 1.1; color: var(--cream); font-weight: 400;
        }
        @media (orientation: portrait) and (max-width: 599px) { .spirit-name { font-size: clamp(1.15rem, 4.5vw, 1.7rem); } }

        /* ── Meta ────────────────────────────────────── */
        .spirit-meta {
            display: flex; flex-direction: column; gap: .38rem;
            padding: .8rem 1rem;
            background: var(--surface); border-radius: 12px; border: 1px solid var(--border);
        }
        @media (orientation: portrait) and (max-width: 599px) { .spirit-meta { padding: .45rem .6rem; gap: .2rem; } }
        .meta-row { display: flex; justify-content: space-between; align-items: center; font-size: .83rem; }
        @media (orientation: portrait) and (max-width: 599px) { .meta-row { font-size: .68rem; } }
        .meta-label { color: var(--muted); font-weight: 300; letter-spacing: .03em; white-space: nowrap; }
        .meta-value { color: var(--cream); font-weight: 500; text-align: right; max-width: 60%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* ── Extras ──────────────────────────────────── */
        .spirit-desc {
            font-size: .88rem; font-weight: 300; line-height: 1.7;
            color: rgba(245,240,235,.62); font-style: italic;
        }
        @media (orientation: portrait) and (max-width: 599px) {
            .spirit-desc { font-size: .75rem; }
        }

        /* Occasion chip */
        .occ-chip {
            display: inline-flex; align-items: center; gap: .32rem;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 100px; padding: .34rem .8rem;
            font-size: .78rem; color: var(--cream); white-space: nowrap;
        }
        .occ-chip .oi { font-size: .88rem; line-height: 1; }
        @media (orientation: portrait) {
            .occ-chip { padding: .28rem .6rem; font-size: .7rem; }
            .occ-chip .oi { font-size: .78rem; }
        }
        .occ-chips-row { display: flex; flex-wrap: wrap; gap: .42rem; }

        /* Occasion cards (slide 2) */
        .occ-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(150px, 42vw), 1fr));
            gap: .85rem;
        }
        @media (orientation: portrait) and (max-width: 599px) {
            .occ-grid { grid-template-columns: repeat(auto-fit, minmax(min(140px, 44vw), 1fr)); gap: .7rem; }
        }
        .occ-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 14px; padding: 1rem 1rem .9rem;
            display: flex; flex-direction: column; gap: .45rem;
        }
        .occ-card-icon { font-size: 1.75rem; line-height: 1; }
        .occ-card-name { font-size: .88rem; font-weight: 600; color: var(--cream); }
        .occ-card-desc { font-size: .75rem; font-weight: 300; color: var(--muted); line-height: 1.5; }

        /* ── Drink mini preview ──────────────────────── */
        .drink-mini-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100px, 28%), 1fr));
            gap: .5rem;
        }
        @media (orientation: portrait) and (max-width: 599px) {
            .drink-mini-grid { grid-template-columns: repeat(auto-fit, minmax(75px, 1fr)); gap: .45rem; }
        }
        .drink-mini { background: var(--surface); border: 1px solid var(--border); border-radius: 11px; overflow: hidden; }
        .drink-mini-img { width: 100%; aspect-ratio: 16/9; object-fit: cover; display: block; }
        .drink-mini-ph  { width: 100%; aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,.03); font-size: 1.4rem; }
        .drink-mini-body { padding: .4rem .5rem; }
        .drink-mini-name { font-size: .72rem; font-weight: 600; color: var(--cream); line-height: 1.2; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .drink-mini-diff { font-size: .58rem; color: var(--gold); letter-spacing: .05em; text-transform: uppercase; margin-top: .1rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* Legal notice */
        .legal-notice {
            padding: .6rem 1.5rem; text-align: center;
            font-size: .6rem; color: rgba(245,240,235,.2); letter-spacing: .04em;
            border-top: 1px solid rgba(255,255,255,.04);
        }

        /* ════════════════════════════════════════════════
           SLIDE 2 — DRINKS
        ════════════════════════════════════════════════ */
        .slide-drinks {
            background:
                radial-gradient(ellipse 60% 35% at 50% 0%, rgba(120,80,200,.05) 0%, transparent 55%),
                var(--base);
        }

        .drinks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(260px, 42vw), 1fr));
            gap: 1.2rem; padding: 0 clamp(1rem, 3vw, 2.5rem) 5rem;
            max-width: 1400px; width: 100%; margin-inline: auto;
        }
        @media (orientation: portrait) { .drinks-grid { grid-template-columns: repeat(auto-fit, minmax(min(260px, 85vw), 1fr)); padding: 0 clamp(1rem, 3vw, 1.5rem) 5rem; } }

        .drink-card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
        .drink-img  { width: 100%; aspect-ratio: 16/9; object-fit: cover; display: block; }
        .drink-ph   { width: 100%; aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,.03); font-size: 2.4rem; }
        .drink-body { padding: 1rem 1.2rem; }
        .drink-tags { display: flex; gap: .4rem; flex-wrap: wrap; margin-bottom: .6rem; }
        .drink-tag  { font-size: .67rem; letter-spacing: .08em; text-transform: uppercase; padding: .2rem .65rem; border-radius: 100px; background: var(--surface); border: 1px solid var(--border); }
        .drink-tag.difficulty { color: rgba(245,240,235,.5); }
        .drink-tag.time       { color: var(--gold); }
        .drink-name { font-family: 'Cormorant Garamond', serif; font-size: clamp(1.15rem,2.4vw,1.55rem); font-weight: 400; color: var(--cream); line-height: 1.2; margin-bottom: .4rem; }
        .drink-desc { font-size: .84rem; font-weight: 300; color: var(--muted); line-height: 1.6; margin-bottom: .8rem; }

        /* Ingredients list */
        .drink-ingredients {
            margin-bottom: .8rem; padding: .65rem .85rem;
            background: rgba(200,169,110,.04); border: 1px solid rgba(200,169,110,.12);
            border-radius: 10px;
        }
        .drink-ingredients-title {
            font-size: .67rem; letter-spacing: .15em; text-transform: uppercase;
            color: var(--gold); opacity: .7; margin-bottom: .4rem;
        }
        .drink-ingredient {
            display: flex; justify-content: space-between; align-items: baseline;
            font-size: .8rem; color: var(--cream); padding: .18rem 0;
            border-bottom: 1px solid rgba(255,255,255,.04);
        }
        .drink-ingredient:last-child { border-bottom: none; }
        .drink-ingredient-name { font-weight: 400; }
        .drink-ingredient-qty  { font-weight: 300; color: var(--muted); font-size: .75rem; white-space: nowrap; }

        .drink-steps { padding-top: .85rem; font-size: .84rem; font-weight: 300; line-height: 1.75; color: rgba(245,240,235,.52); white-space: pre-line; border-top: 1px solid var(--border); }

        .empty-state { text-align: center; padding: 3rem 2rem; color: var(--muted); font-size: .95rem; }
    </style>
</head>
<body>

<a href="/" class="btn-close" title="Fechar">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
</a>

<div class="dots" id="dots">
    <div class="dot active" data-slide="0"></div>
    <div class="dot" data-slide="1"></div>
</div>

<div class="track-wrap"><div class="track" id="track">

{{-- ═══════ SLIDE 1: DESTILADO ═══════ --}}
<div class="slide slide-spirit"><div class="spirit-slide-inner">
<div class="spirit-layout">

    {{-- Foto --}}
    <div class="spirit-photo-col">
        @php $photo = $spirit->getFirstMedia('photos'); @endphp
        <div class="spirit-photo-frame">
            <div class="spirit-photo-shadow"></div>
            @if($photo)
                <img class="spirit-photo"
                     src="{{ $photo->hasGeneratedConversion('card') ? $photo->getUrl('card') : $photo->getUrl() }}"
                     alt="{{ $spirit->name }}" draggable="false"
                     style="aspect-ratio:2/3;object-fit:cover;width:100%;">
            @else
                <div class="spirit-photo-placeholder">
                    <svg width="48" height="64" viewBox="0 0 48 64" fill="none" opacity=".28">
                        <rect x="18" y="2" width="12" height="10" rx="2" fill="#c8a96e"/>
                        <path d="M14 16 Q10 24 10 34 L10 54 Q10 60 16 62 L32 62 Q38 60 38 54 L38 34 Q38 24 34 16 Z" fill="#c8a96e"/>
                    </svg>
                </div>
            @endif
        </div>
    </div>

    {{-- Título: badge + nome --}}
    <div class="spirit-title">
        @if($spirit->spiritType)
        <div class="spirit-badge">
            <svg width="7" height="7" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="5"/></svg>
            {{ $spirit->spiritType->name }}
        </div>
        @endif
        <h1 class="spirit-name">{{ $spirit->name }}</h1>
    </div>

    {{-- Meta: produtor, país, teor --}}
    <div class="spirit-meta-box">
        <div class="spirit-meta">
            @if($spirit->producer)
            <div class="meta-row">
                <span class="meta-label">Produtor</span>
                <span class="meta-value">{{ $spirit->producer->name }}</span>
            </div>
            @endif
            @if($spirit->country)
            <div class="meta-row">
                <span class="meta-label">Origem</span>
                <span class="meta-value">{{ $spirit->country->name }}</span>
            </div>
            @endif
            @if($spirit->alcohol_content)
            <div class="meta-row">
                <span class="meta-label">Teor alcoólico</span>
                <span class="meta-value">{{ number_format($spirit->alcohol_content, 1) }}%</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Extras: descrição --}}
    <div class="spirit-extras">
        @if($spirit->description)<p class="spirit-desc">{{ $spirit->description }}</p>@endif
    </div>

    {{-- Ocasiões + Drinks preview --}}
    <div class="spirit-drinks">
        @if($spirit->occasions->count() || $drinkRecipes->count())
        <div class="divider"></div>
        @endif

        @if($spirit->occasions->count())
        <div>
            <p class="sub-label">Momentos ideais</p>
            <div class="occ-chips-row">
                @foreach($spirit->occasions as $occ)
                <div class="occ-chip"><span class="oi">{{ $occ->icon }}</span>{{ $occ->name }}</div>
                @endforeach
            </div>
        </div>
        @endif

        @if($drinkRecipes->count())
        <div>
            <p class="sub-label">Drinks que combinam</p>
            <div class="drink-mini-grid">
                @foreach($drinkRecipes as $drink)
                <div class="drink-mini">
                    @php $dImg = $drink->getFirstMedia('photo'); @endphp
                    @if($dImg)
                        <img class="drink-mini-img" draggable="false"
                             src="{{ $dImg->hasGeneratedConversion('thumb') ? $dImg->getUrl('thumb') : $dImg->getUrl() }}"
                             alt="{{ $drink->name }}">
                    @else
                        <div class="drink-mini-ph">🍸</div>
                    @endif
                    <div class="drink-mini-body">
                        <p class="drink-mini-name">{{ $drink->name }}</p>
                        <p class="drink-mini-diff">{{ $drink->difficulty }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</div>{{-- /spirit-layout --}}
<div class="legal-notice">
    🔞 Venda proibida para menores de 18 anos &nbsp;·&nbsp; Beba com moderação
</div>
</div></div>{{-- /inner /slide --}}

{{-- ═══════ SLIDE 2: DRINKS ═══════ --}}
<div class="slide slide-drinks">
    <div class="section-header">
        <p class="section-label">Drinks</p>
        <h2 class="section-title">O que preparar com {{ $spirit->name }}?</h2>
        <p class="section-sub">Receitas de coquetéis e drinks</p>
    </div>

    @if($spirit->occasions->count())
    <div style="padding: 0 clamp(1rem, 3vw, 2.5rem) 2rem; max-width: 1400px; width: 100%; margin-inline: auto;">
        <p class="sub-label">Momentos ideais</p>
        <div class="occ-grid">
            @foreach($spirit->occasions as $occ)
            <div class="occ-card">
                <div class="occ-card-icon">{{ $occ->icon }}</div>
                <div class="occ-card-name">{{ $occ->name }}</div>
                @if($occ->description)
                <div class="occ-card-desc">{{ $occ->description }}</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($drinkRecipes->count())
    <div style="padding: 0 clamp(1rem, 3vw, 2.5rem) .75rem; max-width: 1400px; width: 100%; margin-inline: auto;">
        <p class="sub-label">Receitas de drinks</p>
    </div>
    <div class="drinks-grid">
        @foreach($drinkRecipes as $drink)
        <div class="drink-card">
            @php $dImg = $drink->getFirstMedia('photo'); @endphp
            @if($dImg)
                <img class="drink-img" draggable="false"
                     src="{{ $dImg->hasGeneratedConversion('card') ? $dImg->getUrl('card') : $dImg->getUrl() }}"
                     alt="{{ $drink->name }}">
            @else
                <div class="drink-ph">🍸</div>
            @endif
            <div class="drink-body">
                <div class="drink-tags">
                    <span class="drink-tag difficulty">{{ ucfirst($drink->difficulty) }}</span>
                    @if($drink->prep_time)<span class="drink-tag time">{{ $drink->prep_time }} min</span>@endif
                </div>
                <h3 class="drink-name">{{ $drink->name }}</h3>
                @if($drink->description)<p class="drink-desc">{{ $drink->description }}</p>@endif

                {{-- Lista de ingredientes --}}
                @if($drink->ingredients->count())
                <div class="drink-ingredients">
                    <p class="drink-ingredients-title">Ingredientes</p>
                    @foreach($drink->ingredients as $ing)
                    <div class="drink-ingredient">
                        <span class="drink-ingredient-name">{{ $ing->name }}</span>
                        <span class="drink-ingredient-qty">
                            @if($ing->quantity){{ $ing->quantity }}@endif
                            @if($ing->unit) {{ $ing->unit }}@endif
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif

                @if($drink->instructions)
                <div class="drink-steps">{{ $drink->instructions }}</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <p style="font-size:2rem;margin-bottom:.7rem">🍸</p>
        <p>Receitas de drinks ainda não cadastradas.</p>
    </div>
    @endif
</div>

</div></div>{{-- /track /track-wrap --}}

<script>
(function () {
    const TOTAL        = 2;
    const INACTIVITY   = 60000;
    const PEEK_DELAY   = 5000;
    const PEEK_REPEAT  = 30000;
    const PEEK_PX      = 72;
    const PEEK_OUT_MS  = 420;
    const PEEK_BACK_MS = 300;

    let current = 0, peeked = false;
    let inactTimer, peekTimer;

    const track = document.getElementById('track');
    const dots  = document.querySelectorAll('.dot');
    const CSS_DUR  = getComputedStyle(document.documentElement).getPropertyValue('--slide-dur').trim() || '380ms';
    const EASE_OUT = getComputedStyle(document.documentElement).getPropertyValue('--ease-out').trim() || 'ease';

    function applyTransform(idx, extraPx = 0) {
        track.style.transform = `translateX(calc(${-idx * 100}vw - ${extraPx}px))`;
    }

    function goTo(idx) {
        if (idx < 0 || idx >= TOTAL) return;
        current = idx;
        track.style.transition = `transform ${CSS_DUR} ${EASE_OUT}`;
        applyTransform(current);
        dots.forEach((d, i) => d.classList.toggle('active', i === idx));
        resetInactivity();
    }

    function peek() {
        if (peeked || dragging || current >= TOTAL - 1) return;
        track.style.transition = `transform ${PEEK_OUT_MS}ms var(--ease-out)`;
        applyTransform(current, PEEK_PX);
        setTimeout(() => {
            track.style.transition = `transform ${PEEK_BACK_MS}ms var(--ease-in)`;
            applyTransform(current);
        }, PEEK_OUT_MS + 60);
    }

    function schedulePeek(delay) {
        clearTimeout(peekTimer);
        if (peeked) return;
        peekTimer = setTimeout(() => { peek(); schedulePeek(PEEK_REPEAT); }, delay);
    }

    dots.forEach(d => d.addEventListener('click', () => { peeked = true; goTo(+d.dataset.slide); }));

    /* ── drag ────────────────────────────────── */
    let dragStartX = 0, dragStartY = 0, dragging = false, dragLocked = false;
    const DRAG_THRESHOLD = 8, SNAP_THRESHOLD = 50;

    document.addEventListener('selectstart', e => { if (dragLocked) e.preventDefault(); });
    document.addEventListener('dragstart',   e => e.preventDefault());

    document.addEventListener('pointerdown', e => {
        if (e.pointerType === 'touch') return;
        if (e.target.closest('.dot,.btn-close')) return;
        dragging = true; dragLocked = false;
        dragStartX = e.clientX; dragStartY = e.clientY;
        track.style.transition = 'none';
        track.setPointerCapture(e.pointerId);
        resetInactivity();
    });

    document.addEventListener('pointermove', e => {
        if (e.pointerType === 'touch') return;
        if (!dragging) return;
        const dx = Math.abs(e.clientX - dragStartX), dy = Math.abs(e.clientY - dragStartY);
        if (!dragLocked) {
            if (dx < DRAG_THRESHOLD && dy < DRAG_THRESHOLD) return;
            if (dy > dx) { dragging = false; return; }
            dragLocked = true;
        }
        e.preventDefault();
        const off = current * window.innerWidth - (e.clientX - dragStartX);
        const clamped = Math.max(-60, Math.min((TOTAL - 1) * window.innerWidth + 60, off));
        track.style.transform = `translateX(${-clamped}px)`;
    });

    document.addEventListener('pointerup', e => {
        if (e.pointerType === 'touch') return;
        if (!dragging || !dragLocked) { dragging = false; return; }
        dragging = false;
        const delta = e.clientX - dragStartX;
        track.style.transition = `transform ${CSS_DUR} ${EASE_OUT}`;
        if (Math.abs(delta) > SNAP_THRESHOLD) {
            peeked = true; clearTimeout(peekTimer);
            goTo(delta < 0 ? current + 1 : current - 1);
        } else {
            applyTransform(current);
        }
    });

    document.addEventListener('pointercancel', e => {
        if (e.pointerType === 'touch') return;
        if (!dragging) return;
        dragging = false;
        track.style.transition = `transform ${CSS_DUR} ${EASE_OUT}`;
        applyTransform(current);
    });

    /* ── touch fallback ── */
    let touchStartX = 0, touchStartY = 0, touchLocked = false, touchDragging = false;

    document.addEventListener('touchstart', e => {
        if (e.target.closest('.dot,.btn-close')) return;
        const t = e.touches[0];
        touchStartX = t.clientX; touchStartY = t.clientY;
        touchDragging = true; touchLocked = false;
        track.style.transition = 'none';
        resetInactivity();
    }, { passive: true });

    document.addEventListener('touchmove', e => {
        if (!touchDragging) return;
        const t = e.touches[0];
        const dx = Math.abs(t.clientX - touchStartX), dy = Math.abs(t.clientY - touchStartY);
        if (!touchLocked) {
            if (dx < DRAG_THRESHOLD && dy < DRAG_THRESHOLD) return;
            if (dy > dx) { touchDragging = false; return; }
            touchLocked = true;
        }
        e.preventDefault();
        const off = current * window.innerWidth - (t.clientX - touchStartX);
        const clamped = Math.max(-60, Math.min((TOTAL - 1) * window.innerWidth + 60, off));
        track.style.transform = `translateX(${-clamped}px)`;
    }, { passive: false });

    document.addEventListener('touchend', e => {
        if (!touchDragging || !touchLocked) { touchDragging = false; return; }
        touchDragging = false;
        const t = e.changedTouches[0];
        const delta = t.clientX - touchStartX;
        track.style.transition = `transform ${CSS_DUR} ${EASE_OUT}`;
        if (Math.abs(delta) > SNAP_THRESHOLD) {
            peeked = true; clearTimeout(peekTimer);
            goTo(delta < 0 ? current + 1 : current - 1);
        } else {
            applyTransform(current);
        }
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'ArrowRight') { peeked = true; goTo(current + 1); }
        if (e.key === 'ArrowLeft')  { peeked = true; goTo(current - 1); }
    });

    document.querySelector('.btn-close').addEventListener('touchend', e => {
        e.preventDefault();
        window.location.href = '/';
    });

    function resetInactivity() {
        clearTimeout(inactTimer);
        inactTimer = setTimeout(() => window.location.href = '/', INACTIVITY);
    }
    document.addEventListener('pointerdown', resetInactivity);

    goTo(0);
    schedulePeek(PEEK_DELAY);
})();


</script>
</body>
</html>
