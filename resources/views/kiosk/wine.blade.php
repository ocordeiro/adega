<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>{{ $wine->name }} — Adega Sommelier</title>
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
        .track { display: flex; flex-direction: row; width: 300vw; height: 100%; will-change: transform; }
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
        @media (orientation: portrait) { .sub-label { margin-bottom: .35rem; font-size: .6rem; } }
        .section-header { padding: 4.5rem 2.5rem 1.75rem; text-align: center; }
        @media (orientation: portrait) and (max-width: 599px) { .section-header { padding: 4rem 1.5rem 1.25rem; } }
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

        /* Occasion chip (small, for slide 1 preview) */
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

        /* Horizontal filmstrip */
        .strip {
            display: flex; overflow-x: auto; overflow-y: hidden;
            scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch;
            gap: .55rem; scrollbar-width: none;
            mask-image: linear-gradient(to right, black calc(100% - 1.5rem), transparent);
            -webkit-mask-image: linear-gradient(to right, black calc(100% - 1.5rem), transparent);
        }
        .strip::-webkit-scrollbar { display: none; }
        .strip > * { flex: 0 0 auto; scroll-snap-align: start; }

        /* ════════════════════════════════════════════════
           SLIDE 1 — VINHO + preview harmonizações
           Grid areas (landscape vs portrait differ)
        ════════════════════════════════════════════════ */
        .slide-wine {
            background:
                radial-gradient(ellipse 55% 40% at 65% 35%, rgba(200,169,110,.06) 0%, transparent 60%),
                var(--base);
        }

        .wine-slide-inner { min-height: 100vh; display: flex; flex-direction: column; }

        /*
         * LANDSCAPE — photo left, all info stacked right
         * ┌──────┬───────────────────────────┐
         * │      │  title                    │
         * │ photo│  meta                     │
         * │      │  extras (desc + grapes)   │
         * │      ├───────────────────────────┤
         * │      │  harmony preview          │
         * └──────┴───────────────────────────┘
         */
        .wine-layout {
            flex: 1;
            display: grid;
            grid-template-areas:
                "photo title"
                "photo meta"
                "photo extras"
                "photo harmony";
            grid-template-columns: min(220px, 25vw) 1fr;
            grid-template-rows: auto auto auto 1fr;
            column-gap: 2.5rem;
            row-gap: 1rem;
            padding: 4.5rem 3rem 2rem;
            align-content: start;
        }
        .wine-photo-col  { grid-area: photo;   align-self: start; display: flex; flex-direction: column; align-items: center; gap: .75rem; min-width: 0; }
        .wine-title      { grid-area: title;   display: flex; flex-direction: column; gap: .55rem; min-width: 0; }
        .wine-meta-box   { grid-area: meta;    min-width: 0; }
        .wine-extras     { grid-area: extras;  display: flex; flex-direction: column; gap: .8rem; min-width: 0; }
        .wine-harmony    { grid-area: harmony; display: flex; flex-direction: column; gap: .75rem; padding-bottom: .5rem; min-width: 0; overflow-x: clip; }
        @media (orientation: portrait) and (max-width: 599px) { .wine-harmony { gap: .55rem; } }

        /*
         * PORTRAIT (phones) — photo | title+meta side by side, extras+harmony full-width below
         */
        @media (orientation: portrait) and (max-width: 599px) {
            .wine-layout {
                grid-template-areas:
                    "photo title"
                    "photo meta"
                    "extras  extras"
                    "harmony harmony";
                grid-template-columns: 35% 1fr;
                grid-template-rows: auto auto auto auto;
                column-gap: .85rem;
                row-gap: .7rem;
                padding: 3rem 1.25rem 1rem;
                align-content: start;
            }
            .wine-photo-col { align-self: stretch; grid-row: 1 / 3; overflow: hidden; }
            .wine-meta-box  { align-self: start; }
            .wine-meta      { justify-content: flex-start; }
            .wine-harmony   { overflow-x: visible; }
        }

        /* Portrait tablets (600px+) — use landscape-like layout, photo left, all info right */
        @media (orientation: portrait) and (min-width: 600px) {
            .wine-layout {
                grid-template-columns: min(260px, 30vw) 1fr;
                grid-template-rows: auto auto auto auto;
                column-gap: 2rem;
                padding: 4rem 2.5rem 2rem;
                flex: 0;
            }
            .wine-photo-col { align-self: start; }
        }

        /* Very small phones */
        @media (orientation: portrait) and (max-width: 374px) {
            .wine-layout {
                grid-template-columns: 32% 1fr;
                column-gap: .7rem;
                padding: 2.75rem 1rem .75rem;
            }
        }

        /* Short screens — compress vertical spacing */
        @media (orientation: portrait) and (max-width: 599px) and (max-height: 680px) {
            .wine-layout {
                padding-top: 2.5rem;
                row-gap: .45rem;
            }
            .wine-desc { font-size: .68rem; line-height: 1.5; }
            .grape-tags { gap: .2rem; }
            .grape-tag { padding: .18rem .5rem; font-size: .62rem; }
            .wine-harmony { gap: .4rem; }
            .divider { margin-bottom: 0; }
        }

        /* ── Photo ──────────────────────────────────── */
        .wine-photo-frame { position: relative; width: 100%; }
        @media (orientation: portrait) and (max-width: 599px) {
            .wine-photo-col { display: flex; flex-direction: column; }
            .wine-photo-frame { flex: 1; min-height: 0; }
            .wine-photo { height: 100% !important; width: 100% !important; object-fit: cover !important; object-position: top !important; aspect-ratio: unset !important; }
            .wine-photo-placeholder { height: 100%; aspect-ratio: unset; }
        }

        .wine-photo-shadow {
            position: absolute; inset: 0; border-radius: 12px;
            box-shadow: 0 0 50px rgba(200,169,110,.12), 0 20px 44px rgba(0,0,0,.7);
        }
        .wine-photo { position: relative; z-index: 1; width: 100%; border-radius: 12px; object-fit: cover; display: block; }
        .wine-photo-placeholder {
            position: relative; z-index: 1; width: 100%; aspect-ratio: 1/2.2;
            background: linear-gradient(160deg, #1e1e22, #141418);
            border-radius: 12px; border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
        }
        .stars { display: flex; gap: .2rem; justify-content: center; }
        .star       { color: var(--gold); font-size: .95rem; }
        .star.empty { color: rgba(200,169,110,.2); }
        /* stars-photo-col shown in landscape/tablet, hidden in portrait phones */
        /* stars-extras hidden in landscape/tablet, shown in portrait phones */
        @media (orientation: portrait) and (max-width: 599px) {
            .stars-photo-col { display: none; }
        }
        @media not all and (orientation: portrait) { .stars-extras { display: none; } }
        @media (orientation: portrait) and (min-width: 600px) { .stars-extras { display: none; } }

        /* ── Title ───────────────────────────────────── */
        .wine-badge {
            display: inline-flex; align-items: center; gap: .35rem;
            background: rgba(200,169,110,.1); border: 1px solid rgba(200,169,110,.22);
            border-radius: 100px; padding: .25rem .8rem;
            font-size: .67rem; font-weight: 500; letter-spacing: .13em; text-transform: uppercase;
            color: var(--gold); width: fit-content;
        }
        .wine-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.6rem, 3.3vw, 3rem);
            line-height: 1.1; color: var(--cream); font-weight: 400;
        }
        @media (orientation: portrait) and (max-width: 599px) { .wine-name { font-size: clamp(1.15rem, 4.5vw, 1.7rem); } }
        .wine-vintage {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic; color: var(--gold); font-size: 1.1rem; font-weight: 300;
        }
        @media (orientation: portrait) and (max-width: 599px) { .wine-vintage { font-size: .92rem; } }

        /* ── Meta ────────────────────────────────────── */
        .wine-meta {
            display: flex; flex-direction: column; gap: .38rem;
            padding: .8rem 1rem;
            background: var(--surface); border-radius: 12px; border: 1px solid var(--border);
        }
        @media (orientation: portrait) and (max-width: 599px) { .wine-meta { padding: .45rem .6rem; gap: .2rem; } }
        .meta-row { display: flex; justify-content: space-between; align-items: center; font-size: .83rem; }
        @media (orientation: portrait) and (max-width: 599px) { .meta-row { font-size: .68rem; } }
        .meta-label { color: var(--muted); font-weight: 300; letter-spacing: .03em; white-space: nowrap; }
        .meta-value { color: var(--cream); font-weight: 500; text-align: right; max-width: 60%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* ── Extras ──────────────────────────────────── */
        .wine-desc {
            font-size: .88rem; font-weight: 300; line-height: 1.7;
            color: rgba(245,240,235,.62); font-style: italic;
        }
        @media (orientation: portrait) and (max-width: 599px) {
            .wine-desc { font-size: .75rem; }
        }
        .grape-tags { display: flex; flex-wrap: wrap; gap: .28rem; }
        .grape-tag {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 100px; padding: .22rem .65rem;
            font-size: .7rem; color: rgba(245,240,235,.5);
        }

        /* ── Harmony preview ─────────────────────────── */
        /* Chips wrap into multiple lines */
        .occ-chips-row {
            display: flex; flex-wrap: wrap;
            gap: .42rem;
        }

        /* food mini grid — wraps into rows */
        .food-mini-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100px, 28%), 1fr));
            gap: .5rem;
        }
        @media (orientation: portrait) and (max-width: 599px) {
            .food-mini-grid { grid-template-columns: repeat(auto-fill, minmax(75px, 1fr)); gap: .45rem; }
        }
        @media (orientation: portrait) and (max-width: 599px) and (max-height: 680px) {
            .food-mini-grid { grid-template-columns: repeat(auto-fill, minmax(64px, 1fr)); gap: .35rem; }
        }

        .food-mini {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 11px; overflow: hidden;
        }
        .food-mini-img { width: 100%; aspect-ratio: 4/3; object-fit: cover; display: block; }
        .food-mini-ph  { width: 100%; aspect-ratio: 4/3; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,.03); font-size: 1.6rem; }
        @media (orientation: portrait) and (max-width: 599px) and (max-height: 680px) {
            .food-mini-ph  { aspect-ratio: 1/1; font-size: 1.2rem; }
            .food-mini-img { aspect-ratio: 1/1; }
        }
        .food-mini-body { padding: .4rem .5rem; }
        .food-mini-name { font-size: .72rem; font-weight: 600; color: var(--cream); line-height: 1.2; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .food-mini-cat  { font-size: .58rem; color: var(--gold); letter-spacing: .05em; text-transform: uppercase; margin-top: .1rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* Legal notice */
        .legal-notice {
            padding: .6rem 1.5rem; text-align: center;
            font-size: .6rem; color: rgba(245,240,235,.2); letter-spacing: .04em;
            border-top: 1px solid rgba(255,255,255,.04);
        }

        /* ════════════════════════════════════════════════
           SLIDE 2 — HARMONIZAÇÕES (completo)
           Occasion cards + food grid with notes
        ════════════════════════════════════════════════ */
        .slide-pairings {
            background:
                radial-gradient(ellipse 65% 35% at 50% 0%, rgba(200,169,110,.05) 0%, transparent 55%),
                var(--base);
        }

        .pairings-body { padding: 0 2.5rem 5rem; display: flex; flex-direction: column; gap: 2rem; }
        @media (orientation: portrait) { .pairings-body { padding: 0 1.5rem 5rem; } }

        /* Occasion cards */
        .occ-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(170px, 42vw), 1fr));
            gap: .85rem;
        }
        @media (orientation: portrait) {
            .occ-grid { grid-template-columns: repeat(2, 1fr); gap: .7rem; }
        }
        .occ-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 14px; padding: 1rem 1rem .9rem;
            display: flex; flex-direction: column; gap: .45rem;
        }
        .occ-card-icon { font-size: 1.75rem; line-height: 1; }
        .occ-card-name { font-size: .88rem; font-weight: 600; color: var(--cream); }
        .occ-card-desc { font-size: .75rem; font-weight: 300; color: var(--muted); line-height: 1.5; }

        /* Food cards */
        .food-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(185px, 40vw), 1fr));
            gap: .9rem;
        }
        @media (orientation: portrait) {
            .food-grid { grid-template-columns: repeat(auto-fill, minmax(min(150px, 44vw), 1fr)); gap: .7rem; }
        }
        .food-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 13px; overflow: hidden;
        }
        .food-img { width: 100%; aspect-ratio: 4/3; object-fit: cover; display: block; }
        .food-ph  { width: 100%; aspect-ratio: 4/3; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,.03); font-size: 2rem; }
        .food-body  { padding: .7rem .85rem; }
        .food-name  { font-size: .88rem; font-weight: 600; color: var(--cream); margin-bottom: .12rem; }
        .food-cat   { font-size: .66rem; color: var(--gold); letter-spacing: .06em; text-transform: uppercase; }
        .food-notes { margin-top: .38rem; font-size: .73rem; color: var(--muted); line-height: 1.5; font-style: italic; }

        .empty-state { text-align: center; padding: 3rem 2rem; color: var(--muted); font-size: .95rem; }

        /* ════════════════════════════════════════════════
           SLIDE 3 — RECEITAS
        ════════════════════════════════════════════════ */
        .slide-recipes {
            background:
                radial-gradient(ellipse 60% 35% at 50% 0%, rgba(120,80,200,.05) 0%, transparent 55%),
                var(--base);
        }

        .recipes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(290px, 42vw), 1fr));
            gap: 1.2rem; padding: 0 2.5rem 5rem;
        }
        @media (orientation: portrait) { .recipes-grid { grid-template-columns: 1fr; padding: 0 1.5rem 5rem; } }

        .recipe-card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
        .recipe-img  { width: 100%; aspect-ratio: 16/9; object-fit: cover; display: block; }
        .recipe-ph   { width: 100%; aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,.03); font-size: 2.4rem; }
        .recipe-body { padding: 1rem 1.2rem; }
        .recipe-tags { display: flex; gap: .4rem; flex-wrap: wrap; margin-bottom: .6rem; }
        .recipe-tag  { font-size: .67rem; letter-spacing: .08em; text-transform: uppercase; padding: .2rem .65rem; border-radius: 100px; background: var(--surface); border: 1px solid var(--border); }
        .recipe-tag.difficulty { color: rgba(245,240,235,.5); }
        .recipe-tag.time       { color: var(--gold); }
        .recipe-name { font-family: 'Cormorant Garamond', serif; font-size: clamp(1.15rem,2.4vw,1.55rem); font-weight: 400; color: var(--cream); line-height: 1.2; margin-bottom: .4rem; }
        .recipe-desc { font-size: .84rem; font-weight: 300; color: var(--muted); line-height: 1.6; margin-bottom: .8rem; }
        .recipe-note {
            margin-bottom: .8rem; padding: .48rem .78rem;
            background: rgba(200,169,110,.05); border-left: 2px solid rgba(200,169,110,.3);
            border-radius: 0 8px 8px 0; font-size: .75rem; color: var(--muted); font-style: italic;
        }
        .recipe-toggle {
            display: flex; align-items: center; gap: .4rem;
            font-size: .75rem; font-weight: 500; letter-spacing: .08em; text-transform: uppercase;
            color: var(--gold); cursor: pointer;
            padding: .5rem 0; border-top: 1px solid var(--border); touch-action: manipulation;
        }
        .recipe-toggle svg { transition: transform .3s; flex-shrink: 0; }
        .recipe-toggle.open svg { transform: rotate(180deg); }
        .recipe-steps { display: none; padding-top: .85rem; font-size: .84rem; font-weight: 300; line-height: 1.75; color: rgba(245,240,235,.52); white-space: pre-line; }
        .recipe-steps.open { display: block; }
    </style>
</head>
<body>

<a href="/" class="btn-close" title="Fechar">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
</a>

<div class="dots" id="dots">
    <div class="dot active" data-slide="0"></div>
    <div class="dot" data-slide="1"></div>
    <div class="dot" data-slide="2"></div>
</div>

<div class="track-wrap"><div class="track" id="track">

{{-- ═══════ SLIDE 1: VINHO + preview harmonizações ═══════ --}}
<div class="slide slide-wine"><div class="wine-slide-inner">
<div class="wine-layout">

    {{-- Foto --}}
    <div class="wine-photo-col">
        @php $photo = $wine->getFirstMedia('photos'); @endphp
        <div class="wine-photo-frame">
            <div class="wine-photo-shadow"></div>
            @if($photo)
                <img class="wine-photo"
                     src="{{ $photo->getTemporaryUrl(now()->addHours(2), 'card') }}"
                     alt="{{ $wine->name }}" draggable="false"
                     style="aspect-ratio:2/3;object-fit:cover;width:100%;">
            @else
                <div class="wine-photo-placeholder">
                    <svg width="36" height="64" viewBox="0 0 90 160" fill="none" opacity=".28">
                        <rect x="36" y="4" width="18" height="22" rx="4" fill="#c8a96e"/>
                        <path d="M28 36 Q20 50 18 70 L18 138 Q18 148 28 150 L62 150 Q72 148 72 138 L72 70 Q70 50 62 36 Z" fill="#c8a96e"/>
                    </svg>
                </div>
            @endif
        </div>
        @if($wine->rating)
        <div class="stars stars-photo-col">
            @for($i=1;$i<=5;$i++)<span class="star {{ $i<=round($wine->rating)?'':'empty' }}">★</span>@endfor
        </div>
        @endif
    </div>

    {{-- Título: badge + nome + safra --}}
    <div class="wine-title">
        @if($wine->wineType)
        <div class="wine-badge">
            <svg width="7" height="7" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="5"/></svg>
            {{ $wine->wineType->name }}
        </div>
        @endif
        <h1 class="wine-name">{{ $wine->name }}</h1>
        @if($wine->vintage)<p class="wine-vintage">{{ $wine->vintage }}</p>@endif
    </div>

    {{-- Meta: produtor, origem, álcool, temperatura --}}
    <div class="wine-meta-box">
        <div class="wine-meta">
            @if($wine->producer)
            <div class="meta-row">
                <span class="meta-label">Produtor</span>
                <span class="meta-value">{{ $wine->producer->name }}</span>
            </div>
            @endif
            @if($wine->country)
            <div class="meta-row">
                <span class="meta-label">Origem</span>
                <span class="meta-value">{{ $wine->country->name }}{{ $wine->region ? ', '.$wine->region->name : '' }}</span>
            </div>
            @endif
            @if($wine->alcohol_content)
            <div class="meta-row">
                <span class="meta-label">Teor alcoólico</span>
                <span class="meta-value">{{ number_format($wine->alcohol_content, 1) }}%</span>
            </div>
            @endif
            @if($wine->serving_temp_min || $wine->serving_temp_max)
            <div class="meta-row">
                <span class="meta-label">Temperatura</span>
                <span class="meta-value">
                    @if($wine->serving_temp_min && $wine->serving_temp_max){{ $wine->serving_temp_min }}°C – {{ $wine->serving_temp_max }}°C
                    @elseif($wine->serving_temp_min){{ $wine->serving_temp_min }}°C
                    @else{{ $wine->serving_temp_max }}°C
                    @endif
                </span>
            </div>
            @endif
        </div>
    </div>

    {{-- Extras: estrelas (portrait only) + descrição + uvas --}}
    <div class="wine-extras">
        @if($wine->rating)
        <div class="stars stars-extras">
            @for($i=1;$i<=5;$i++)<span class="star {{ $i<=round($wine->rating)?'':'empty' }}">★</span>@endfor
        </div>
        @endif
        @if($wine->description)<p class="wine-desc">{{ $wine->description }}</p>@endif
        @if($wine->grapeVarieties->count())
        <div class="grape-tags">
            @foreach($wine->grapeVarieties as $grape)
            <span class="grape-tag">{{ $grape->name }}{{ $grape->pivot->percentage ? ' '.$grape->pivot->percentage.'%' : '' }}</span>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Harmonizações preview --}}
    <div class="wine-harmony">
        @if($wine->occasions->count() || $wine->foods->count())
        <div class="divider"></div>
        @endif

        @if($wine->occasions->count())
        <div>
            <p class="sub-label">Momentos ideais</p>
            <div class="occ-chips-row no-slide-drag" id="occ-chips-1">
                @foreach($wine->occasions as $occ)
                <div class="occ-chip"><span class="oi">{{ $occ->icon }}</span>{{ $occ->name }}</div>
                @endforeach
            </div>
        </div>
        @endif

        @if($wine->foods->count())
        <div>
            <p class="sub-label">Harmoniza com</p>
            <div class="food-mini-grid" id="foods-strip-1">
                @foreach($wine->foods as $food)
                <div class="food-mini">
                    @php $img = $food->getFirstMedia('image'); @endphp
                    @if($img)
                        <img class="food-mini-img" draggable="false"
                             src="{{ $img->getTemporaryUrl(now()->addHours(2), 'thumb') }}"
                             alt="{{ $food->name }}">
                    @else
                        <div class="food-mini-ph">🍽️</div>
                    @endif
                    <div class="food-mini-body">
                        <p class="food-mini-name">{{ $food->name }}</p>
                        @if($food->foodCategory)<p class="food-mini-cat">{{ $food->foodCategory->name }}</p>@endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</div>{{-- /wine-layout --}}
<div class="legal-notice">
    🔞 Venda proibida para menores de 18 anos &nbsp;·&nbsp; Beba com moderação
</div>
</div></div>{{-- /inner /slide --}}

{{-- ═══════ SLIDE 2: HARMONIZAÇÕES completo ═══════ --}}
<div class="slide slide-pairings">
    <div class="section-header">
        <p class="section-label">Harmonizações</p>
        <h2 class="section-title">O que combina com este vinho?</h2>
        <p class="section-sub">{{ $wine->name }}{{ $wine->vintage ? ' · '.$wine->vintage : '' }}</p>
    </div>

    <div class="pairings-body">

        {{-- Ocasiões como cards com descrição --}}
        @if($wine->occasions->count())
        <div>
            <p class="sub-label">Momentos ideais</p>
            <div class="occ-grid">
                @foreach($wine->occasions as $occ)
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

        {{-- Alimentos com notas de harmonização --}}
        @if($wine->foods->count())
        <div>
            <p class="sub-label">Alimentos que combinam</p>
            <div class="food-grid">
                @foreach($wine->foods as $food)
                <div class="food-card">
                    @php $img = $food->getFirstMedia('image'); @endphp
                    @if($img)
                        <img class="food-img" draggable="false"
                             src="{{ $img->getTemporaryUrl(now()->addHours(2), 'thumb') }}"
                             alt="{{ $food->name }}">
                    @else
                        <div class="food-ph">🍽️</div>
                    @endif
                    <div class="food-body">
                        <p class="food-name">{{ $food->name }}</p>
                        @if($food->foodCategory)<p class="food-cat">{{ $food->foodCategory->name }}</p>@endif
                        @if($food->pivot->notes)<p class="food-notes">{{ $food->pivot->notes }}</p>@endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="empty-state">
            <p style="font-size:2rem;margin-bottom:.7rem">🍷</p>
            <p>Harmonizações ainda não cadastradas.</p>
        </div>
        @endif

    </div>
</div>

{{-- ═══════ SLIDE 3: RECEITAS ═══════ --}}
<div class="slide slide-recipes">
    <div class="section-header">
        <p class="section-label">Receitas</p>
        <h2 class="section-title">Prepare em casa</h2>
        <p class="section-sub">Receitas que harmonizam com {{ $wine->name }}</p>
    </div>

    @if($wine->recipes->count())
    <div class="recipes-grid">
        @foreach($wine->recipes as $recipe)
        <div class="recipe-card">
            @php $rImg = $recipe->getFirstMedia('photo'); @endphp
            @if($rImg)
                <img class="recipe-img" draggable="false"
                     src="{{ $rImg->getTemporaryUrl(now()->addHours(2), 'card') }}"
                     alt="{{ $recipe->name }}">
            @else
                <div class="recipe-ph">👨‍🍳</div>
            @endif
            <div class="recipe-body">
                <div class="recipe-tags">
                    <span class="recipe-tag difficulty">{{ ucfirst($recipe->difficulty) }}</span>
                    @if($recipe->prep_time)<span class="recipe-tag time">{{ $recipe->prep_time }} min</span>@endif
                </div>
                <h3 class="recipe-name">{{ $recipe->name }}</h3>
                @if($recipe->description)<p class="recipe-desc">{{ $recipe->description }}</p>@endif
                @if($recipe->pivot->notes)<div class="recipe-note">{{ $recipe->pivot->notes }}</div>@endif
                @if($recipe->instructions)
                <div class="recipe-toggle" onclick="toggleRecipe(this)">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                    Ver modo de preparo
                </div>
                <div class="recipe-steps">{{ $recipe->instructions }}</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <p style="font-size:2rem;margin-bottom:.7rem">👨‍🍳</p>
        <p>Receitas ainda não cadastradas.</p>
    </div>
    @endif
</div>

</div></div>{{-- /track /track-wrap --}}

<script>
(function () {
    const TOTAL        = 3;
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
        if (e.target.closest('.dot,.btn-close,.btn-back,.recipe-toggle,.no-slide-drag')) return;
        dragging = true; dragLocked = false;
        dragStartX = e.clientX; dragStartY = e.clientY;
        track.style.transition = 'none';
        if (e.pointerType === 'mouse') track.setPointerCapture(e.pointerId);
        resetInactivity();
    });

    document.addEventListener('pointermove', e => {
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

    document.addEventListener('pointercancel', () => {
        if (!dragging) return;
        dragging = false;
        track.style.transition = `transform ${CSS_DUR} ${EASE_OUT}`;
        applyTransform(current);
    });

    /* ── touch fallback (more reliable on mobile) ── */
    let touchStartX = 0, touchStartY = 0, touchLocked = false, touchDragging = false;

    document.addEventListener('touchstart', e => {
        if (e.target.closest('.dot,.btn-close,.btn-back,.recipe-toggle,.no-slide-drag')) return;
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

    function resetInactivity() {
        clearTimeout(inactTimer);
        inactTimer = setTimeout(() => window.location.href = '/', INACTIVITY);
    }
    document.addEventListener('pointerdown', resetInactivity);


    goTo(0);
    schedulePeek(PEEK_DELAY);
})();

function toggleRecipe(el) {
    el.classList.toggle('open');
    const steps = el.nextElementSibling;
    steps.classList.toggle('open');
    el.innerHTML = el.innerHTML.replace(/Ver|Ocultar/, steps.classList.contains('open') ? 'Ocultar' : 'Ver');
}
</script>
</body>
</html>
