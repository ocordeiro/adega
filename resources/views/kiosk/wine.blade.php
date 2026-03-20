<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>{{ $wine->name }} — Adega Sommelier</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:   #d93f35;
            --primary-dk:#b83028;
            --bg:        #dce9f0;
            --bg-alt:    #eaf2f7;
            --white:     #ffffff;
            --text:      #1a1a2e;
            --muted:     #6b7280;
            --border:    rgba(0,0,0,.1);
            --shadow:    rgba(0,0,0,.12);
            --slide-dur: 380ms;
            --ease-out:  cubic-bezier(.25,.46,.45,.94);
            --ease-in:   cubic-bezier(.55,.06,.68,.19);
        }

        html, body {
            width: 100%; height: 100%; overflow: hidden;
            background: var(--bg);
            font-family: 'Nunito', sans-serif;
            -webkit-font-smoothing: antialiased;
            user-select: none; -webkit-user-select: none; -webkit-touch-callout: none;
            color: var(--text);
        }

        /* ── BACK BUTTON (fixed, top-left) ───────────── */
        .btn-back-fixed {
            position: fixed; top: 1.1rem; left: 1.25rem; z-index: 50;
            display: flex; align-items: center; justify-content: center;
            width: 2.2rem; height: 2.2rem;
            background: rgba(255,255,255,.8); border: 1px solid var(--border);
            border-radius: 100px; color: var(--muted);
            text-decoration: none;
            backdrop-filter: blur(10px); transition: background .2s, color .2s;
            touch-action: manipulation;
        }
        .btn-back-fixed:active { background: var(--white); color: var(--text); }


        /* ── TRACK ────────────────────────────────────── */
        .track-wrap { width: 100vw; height: 100vh; overflow: hidden; }
        .track { display: flex; flex-direction: row; width: 200vw; height: 100%; will-change: transform; }
        .slide {
            width: 100vw; height: 100vh; flex-shrink: 0;
            overflow: hidden;
        }
        .slide-pairings {
            display: flex; flex-direction: column; overflow: hidden;
        }

        /* ════════════════════════════════════════════════
           SLIDE 1 — VINHO + preview harmonizações
        ════════════════════════════════════════════════ */
        .slide-wine { background: var(--white); }

        .wine-slide-inner {
            height: 100vh; display: flex; flex-direction: column; overflow: hidden;
        }

        /* LANDSCAPE: foto esquerda, info direita */
        .wine-layout {
            flex: 1;
            display: grid;
            grid-template-columns: clamp(240px, 38vw, 440px) 1fr;
            grid-template-areas: "photo info";
            height: 100%;
            overflow: hidden;
        }

        /* ── Coluna da foto ──────────────────────────── */
        .wine-photo-col {
            grid-area: photo;
            background: linear-gradient(160deg, #f0f2f5 0%, #e4e7ed 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 2rem 1.5rem;
            position: relative;
        }

        .wine-photo-frame {
            position: relative; width: 100%;
            display: flex; align-items: center; justify-content: center;
        }
        .wine-photo {
            max-width: 100%; max-height: 70vh;
            width: auto; height: auto;
            object-fit: contain; display: block;
            filter: drop-shadow(0 24px 40px rgba(0,0,0,.28)) drop-shadow(0 6px 12px rgba(0,0,0,.16));
        }
        .wine-photo-placeholder {
            width: 100%; aspect-ratio: 2/3;
            display: flex; align-items: center; justify-content: center;
        }

        .stars { display: flex; gap: .2rem; justify-content: center; margin-top: .75rem; }
        .star       { color: var(--primary); font-size: .9rem; }
        .star.empty { color: rgba(217,63,53,.25); }

        /* ── Coluna de info ──────────────────────────── */
        .wine-info-col {
            grid-area: info;
            background: var(--white);
            display: flex; flex-direction: column;
            align-items: center; text-align: center;
            padding: 3rem clamp(1.5rem, 4vw, 3rem) 2rem;
            gap: 1rem;
        }


        .wine-badge {
            display: inline-flex; align-items: center; gap: .3rem;
            background: rgba(217,63,53,.1); border: 1px solid rgba(217,63,53,.2);
            border-radius: 100px; padding: .2rem .75rem;
            font-size: .65rem; font-weight: 500; letter-spacing: .12em; text-transform: uppercase;
            color: var(--primary); width: fit-content;
        }

        .wine-name {
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            line-height: 1.15; color: var(--primary); font-weight: 800;
            text-align: center;
        }
        @media (orientation: portrait) and (max-width: 599px) { .wine-name { font-size: clamp(1.4rem, 5vw, 1.9rem); } }

        .wine-vintage {
            font-style: italic; color: var(--muted); font-size: .95rem; font-weight: 400;
            margin-top: -.4rem; text-align: center;
        }

        .wine-desc {
            font-size: .86rem; font-weight: 400; line-height: 1.65;
            color: var(--muted); max-width: 480px;
        }
        @media (orientation: portrait) and (max-width: 599px) { .wine-desc { font-size: .75rem; } }

        /* Meta: produtor, origem etc. — compact row */
        .wine-meta-row {
            display: flex; flex-wrap: wrap; justify-content: center; gap: .5rem .9rem;
            padding: .6rem .85rem;
            background: #f5f5f7; border-radius: 10px;
            border: 1px solid var(--border);
        }
        .meta-item { display: flex; flex-direction: column; align-items: center; gap: .1rem; }
        .meta-label { font-size: .58rem; letter-spacing: .1em; text-transform: uppercase; color: var(--muted); }
        .meta-value { font-size: .8rem; font-weight: 700; color: var(--text); }

        .grape-tags { display: flex; flex-wrap: wrap; justify-content: center; gap: .25rem; }
        .grape-tag {
            background: #f5f5f7; border: 1px solid var(--border);
            border-radius: 100px; padding: .2rem .6rem;
            font-size: .68rem; color: var(--muted);
        }

        /* ── Seção "Combina com" ─────────────────────── */
        .harmony-section { display: flex; flex-direction: column; align-items: center; gap: .65rem; flex: 1; margin-top: .5rem; }

        .harmony-divider {
            height: 1px; background: var(--border); margin-bottom: .2rem; align-self: stretch;
        }

        .harmony-title {
            font-size: clamp(1rem, 2vw, 1.3rem);
            font-weight: 800; color: var(--primary); letter-spacing: .01em;
            text-align: center;
        }

        /* Grid de 3 colunas: ocasião + alimento */
        .pairing-grid {
            display: grid;
            grid-template-columns: repeat(3, clamp(72px, 9vw, 200px));
            gap: clamp(.5rem, 1.2vw, 2rem);
            margin-top: auto; margin-bottom: auto;
        }
        @media (orientation: portrait) and (max-width: 599px) {
            .pairing-grid { gap: .5rem; }
        }

        .pairing-col {
            display: flex; flex-direction: column;
            align-items: center; gap: .35rem;
            text-align: center;
        }

        .pairing-occ-label {
            font-size: .67rem; color: var(--muted); font-weight: 400;
            letter-spacing: .02em; line-height: 1.3;
            min-height: 1.8em;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .pairing-img-wrap {
            width: clamp(56px, 8vw, 160px);
            height: clamp(56px, 8vw, 160px);
            border-radius: 50%;
            overflow: hidden;
            background: var(--white);
            box-shadow: 0 4px 14px var(--shadow);
            flex-shrink: 0;
        }
        .pairing-img-wrap img {
            width: 100%; height: 100%; object-fit: cover; display: block;
        }
        .pairing-img-ph {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; background: var(--bg-alt);
        }

        .pairing-food-name {
            font-size: .78rem; font-weight: 600; color: var(--text);
            line-height: 1.3; overflow: hidden; text-overflow: ellipsis;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            max-width: 100%;
        }
        .pairing-food-cat {
            font-size: .63rem; color: var(--primary); letter-spacing: .04em;
            text-transform: uppercase; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
            max-width: 100%;
        }
        @media (orientation: portrait) and (max-width: 599px) {
            .pairing-img-wrap { width: 56px; height: 56px; }
            .pairing-food-name { font-size: .68rem; }
            .pairing-food-cat  { font-size: .57rem; }
            .pairing-occ-label { font-size: .58rem; }
        }

        /* ── Botão "Ver mais" ───────────────────────── */
        .btn-ver-mais {
            display: inline-flex; align-items: center; gap: .45rem;
            background: var(--primary); color: var(--white);
            border: none; border-radius: 100px;
            padding: .72rem 2rem;
            font-family: 'Nunito', sans-serif;
            font-size: .9rem; font-weight: 700; letter-spacing: .02em;
            cursor: pointer; touch-action: manipulation;
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,.22),
                inset 0 -2px 0 rgba(0,0,0,.18),
                0 4px 14px rgba(217,63,53,.4);
            transition: box-shadow .15s, background .15s;
            width: fit-content; align-self: center;
            margin-top: auto;
        }
        .btn-ver-mais:active {
            background: var(--primary-dk);
            box-shadow: inset 0 2px 4px rgba(0,0,0,.25), 0 1px 4px rgba(217,63,53,.3);
        }
        @media (orientation: portrait) and (max-width: 599px) {
            .btn-ver-mais { font-size: .78rem; padding: .6rem 1.4rem; }
        }


        /* ── PORTRAIT phones — layout empilhado ──────── */
        @media (orientation: portrait) and (max-width: 599px) {
            .wine-layout {
                grid-template-columns: 1fr;
                grid-template-areas:
                    "photo"
                    "info";
                min-height: unset;
            }
            .wine-photo-col {
                padding: 1.5rem 2rem 1rem;
                min-height: 200px;
            }
            .wine-info-col {
                padding: 1.25rem 1rem 1rem;
                gap: .75rem;
            }
            .wine-meta-row { display: none; }
        }

        /* Portrait tablets */
        @media (orientation: portrait) and (min-width: 600px) {
            .wine-layout {
                grid-template-columns: clamp(180px, 30vw, 260px) 1fr;
            }
        }

        /* ════════════════════════════════════════════════
           SLIDE 2 — HARMONIZAÇÕES completo
        ════════════════════════════════════════════════ */
        .slide-pairings { background: var(--bg-alt); }

        .section-header {
            flex-shrink: 0;
            padding: 2rem clamp(1.5rem, 4vw, 2.5rem) .75rem;
            text-align: center; max-width: 900px; margin-inline: auto; width: 100%;
        }
        @media (orientation: portrait) and (max-width: 599px) { .section-header { padding: 1.5rem 1rem .5rem; } }

        .section-label {
            font-size: .65rem; letter-spacing: .22em; text-transform: uppercase;
            color: var(--primary); opacity: .8; margin-bottom: .4rem;
        }
        .section-title {
            font-size: clamp(1.4rem, 3vw, 2.2rem); font-weight: 800; color: var(--text);
        }
        .section-sub { margin-top: .35rem; font-size: .84rem; font-weight: 300; color: var(--muted); }

        .sub-label {
            font-size: .63rem; letter-spacing: .2em; text-transform: uppercase;
            color: var(--muted); margin-bottom: .55rem;
        }
        .pairings-body {
            flex: 1; min-height: 0; overflow: hidden;
            padding: 0 clamp(1rem, 3vw, 2.5rem) 1.25rem;
            display: flex; flex-direction: column; gap: .75rem;
            max-width: 1400px; width: 100%; margin-inline: auto;
        }
        .pairings-body > div {
            display: flex; flex-direction: column; flex: 1; min-height: 0;
        }
        @media (orientation: portrait) and (max-width: 599px) { .pairings-body { padding: 0 1rem 1rem; } }

        /* Occasion cards */
        .occ-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(150px, 42vw), 1fr));
            gap: .8rem;
        }
        .occ-card {
            background: var(--white); border: 1px solid var(--border);
            border-radius: 14px; padding: .9rem 1rem;
            display: flex; flex-direction: column; gap: .4rem;
            box-shadow: 0 2px 8px var(--shadow);
        }
        .occ-card-icon { font-size: 1.6rem; line-height: 1; }
        .occ-card-name { font-size: .86rem; font-weight: 600; color: var(--text); }
        .occ-card-desc { font-size: .73rem; font-weight: 300; color: var(--muted); line-height: 1.5; }

        /* Food cards */
        .food-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: 1fr;
            gap: clamp(.5rem, 1.2vw, .85rem);
            flex: 1; min-height: 0;
        }
        .food-card {
            background: var(--white); border: 1px solid var(--border);
            border-radius: 13px; overflow: hidden;
            box-shadow: 0 2px 8px var(--shadow);
            display: flex; flex-direction: column; min-height: 0;
        }
        .food-img { width: 100%; aspect-ratio: 16/9; object-fit: cover; display: block; flex-shrink: 0; }
        .food-ph  { width: 100%; aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; background: var(--bg); font-size: 2rem; flex-shrink: 0; }
        .food-body  { padding: .6rem .8rem; flex: 1; min-height: 0; overflow: hidden; }
        .food-name  { font-size: .86rem; font-weight: 600; color: var(--text); margin-bottom: .1rem; }
        .food-cat   { font-size: .63rem; color: var(--primary); letter-spacing: .06em; text-transform: uppercase; }
        .food-notes { margin-top: .3rem; font-size: .72rem; color: var(--muted); line-height: 1.5; font-style: italic; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

        .empty-state { text-align: center; padding: 3rem 2rem; color: var(--muted); font-size: .95rem; }

        /* Recipes */
        .recipes-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: 1fr;
            gap: clamp(.5rem, 1.2vw, 1.1rem);
            flex: 1; min-height: 0;
        }

        .recipe-card { background: var(--white); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: 0 2px 8px var(--shadow); display: flex; flex-direction: column; min-height: 0; }
        .recipe-img  { width: 100%; aspect-ratio: 4/1; object-fit: cover; display: block; flex-shrink: 0; }
        .recipe-ph   { width: 100%; aspect-ratio: 4/1; display: flex; align-items: center; justify-content: center; background: var(--bg); font-size: 1.8rem; flex-shrink: 0; }
        .recipe-body { padding: .5rem .75rem; flex: 1; min-height: 0; overflow: hidden; display: flex; flex-direction: column; }
        .recipe-tags { display: flex; gap: .3rem; flex-wrap: wrap; margin-bottom: .3rem; flex-shrink: 0; }
        .recipe-tag  { font-size: .6rem; letter-spacing: .07em; text-transform: uppercase; padding: .14rem .5rem; border-radius: 100px; background: var(--bg); border: 1px solid var(--border); }
        .recipe-tag.difficulty { color: var(--muted); }
        .recipe-tag.time       { color: var(--primary); }
        .recipe-name { font-size: clamp(.82rem,1.6vw,1.05rem); font-weight: 700; color: var(--text); line-height: 1.2; margin-bottom: .25rem; flex-shrink: 0; }
        .recipe-desc { font-size: .73rem; font-weight: 300; color: var(--muted); line-height: 1.5; margin-bottom: .35rem; flex-shrink: 0; }
        .recipe-note { margin-bottom: .35rem; padding: .28rem .55rem; background: rgba(217,63,53,.05); border-left: 2px solid rgba(217,63,53,.3); border-radius: 0 8px 8px 0; font-size: .66rem; color: var(--muted); font-style: italic; flex-shrink: 0; }
        .recipe-steps { padding-top: .4rem; font-size: .7rem; font-weight: 300; line-height: 1.55; color: var(--muted); white-space: pre-line; border-top: 1px solid var(--border); flex: 1; overflow: hidden; }
    </style>
</head>
<body>

<a href="/" class="btn-back-fixed" title="Voltar">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
</a>


<div class="track-wrap"><div class="track" id="track">

{{-- ═══════ SLIDE 1: VINHO + preview harmonizações ═══════ --}}
<div class="slide slide-wine"><div class="wine-slide-inner">
<div class="wine-layout">

    {{-- Foto --}}
    <div class="wine-photo-col">
        @php $photo = $wine->getFirstMedia('photos'); @endphp
        <div class="wine-photo-frame">
            @if($photo)
                <img class="wine-photo"
                     src="{{ $photo->hasGeneratedConversion('card') ? $photo->getUrl('card') : $photo->getUrl() }}"
                     alt="{{ $wine->name }}" draggable="false">
            @else
                <div class="wine-photo-placeholder">
                    <svg width="36" height="64" viewBox="0 0 90 160" fill="none" opacity=".35">
                        <rect x="36" y="4" width="18" height="22" rx="4" fill="#d93f35"/>
                        <path d="M28 36 Q20 50 18 70 L18 138 Q18 148 28 150 L62 150 Q72 148 72 138 L72 70 Q70 50 62 36 Z" fill="#d93f35"/>
                    </svg>
                </div>
            @endif
        </div>
        @if($wine->rating)
        <div class="stars">
            @for($i=1;$i<=5;$i++)<span class="star {{ $i<=round($wine->rating)?'':'empty' }}">★</span>@endfor
        </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="wine-info-col">

        {{-- Badge + nome + safra --}}
        @if($wine->wineType)
        <div class="wine-badge">
            <svg width="6" height="6" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="5"/></svg>
            {{ $wine->wineType->name }}
        </div>
        @endif
        <h1 class="wine-name">{{ $wine->name }}</h1>
        @if($wine->vintage)<p class="wine-vintage">{{ $wine->vintage }}</p>@endif

        {{-- Descrição --}}
        @if($wine->description)<p class="wine-desc">{{ $wine->description }}</p>@endif

        {{-- Meta compact --}}
        @php
            $hasMeta = $wine->producer || $wine->country || $wine->alcohol_content
                       || $wine->serving_temp_min || $wine->serving_temp_max;
        @endphp
        @if($hasMeta)
        <div class="wine-meta-row">
            @if($wine->producer)
            <div class="meta-item">
                <span class="meta-label">Produtor</span>
                <span class="meta-value">{{ $wine->producer->name }}</span>
            </div>
            @endif
            @if($wine->country)
            <div class="meta-item">
                <span class="meta-label">Origem</span>
                <span class="meta-value">{{ $wine->country->name }}{{ $wine->region ? ', '.$wine->region->name : '' }}</span>
            </div>
            @endif
            @if($wine->alcohol_content)
            <div class="meta-item">
                <span class="meta-label">Álcool</span>
                <span class="meta-value">{{ number_format($wine->alcohol_content, 1) }}%</span>
            </div>
            @endif
            @if($wine->serving_temp_min || $wine->serving_temp_max)
            <div class="meta-item">
                <span class="meta-label">Temperatura</span>
                <span class="meta-value">
                    @if($wine->serving_temp_min && $wine->serving_temp_max){{ $wine->serving_temp_min }}°C–{{ $wine->serving_temp_max }}°C
                    @elseif($wine->serving_temp_min){{ $wine->serving_temp_min }}°C
                    @else{{ $wine->serving_temp_max }}°C
                    @endif
                </span>
            </div>
            @endif
        </div>
        @endif

        {{-- Uvas --}}
        @if($wine->grapeVarieties->count())
        <div class="grape-tags">
            @foreach($wine->grapeVarieties as $grape)
            <span class="grape-tag">{{ $grape->name }}{{ $grape->pivot->percentage ? ' '.$grape->pivot->percentage.'%' : '' }}</span>
            @endforeach
        </div>
        @endif

        {{-- Harmonizações preview: até 3 pratos --}}
        @php $previewFoods = $wine->foods->take(3); @endphp
        @if($previewFoods->count())
        <div class="harmony-section">
            <div class="harmony-divider"></div>
            <p class="harmony-title">Combina com</p>
            <div class="pairing-grid">
                @foreach($previewFoods as $food)
                <div class="pairing-col">
                    <div class="pairing-img-wrap">
                        @php $img = $food->getFirstMedia('image'); @endphp
                        @if($img)
                            <img src="{{ $img->hasGeneratedConversion('thumb') ? $img->getUrl('thumb') : $img->getUrl() }}"
                                 alt="{{ $food->name }}" draggable="false">
                        @else
                            <div class="pairing-img-ph">🍽️</div>
                        @endif
                    </div>
                    <p class="pairing-food-name">{{ $food->name }}</p>
                    @if($food->foodCategory)<p class="pairing-food-cat">{{ $food->foodCategory->name }}</p>@endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Botão ver mais --}}
        @if($wine->foods->count() > 0 || $wine->recipes->count() > 0)
        <button class="btn-ver-mais" id="btn-ver-mais">
            Ver mais
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </button>
        @endif

    </div>{{-- /wine-info-col --}}

</div>{{-- /wine-layout --}}
</div></div>{{-- /inner /slide --}}

{{-- ═══════ SLIDE 2: RECEITAS ═══════ --}}
<div class="slide slide-pairings">
    <div class="section-header">
        <p class="section-label">Receitas</p>
        <h2 class="section-title">Receitas que combinam com este vinho</h2>
        <p class="section-sub">{{ $wine->name }}{{ $wine->vintage ? ' · '.$wine->vintage : '' }}</p>
    </div>

    <div class="pairings-body">

        @php $slideRecipes = $wine->recipes->take(3); @endphp
        @if($slideRecipes->count())
        <div>
            <div class="recipes-grid">
                @foreach($slideRecipes as $recipe)
                <div class="recipe-card">
                    @php $rImg = $recipe->getFirstMedia('photo'); @endphp
                    @if($rImg)
                        <img class="recipe-img" draggable="false"
                             src="{{ $rImg->hasGeneratedConversion('card') ? $rImg->getUrl('card') : $rImg->getUrl() }}"
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
                        <div class="recipe-steps">{{ $recipe->instructions }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="empty-state">
            <p style="font-size:2rem;margin-bottom:.7rem">👨‍🍳</p>
            <p>Receitas ainda não cadastradas.</p>
        </div>
        @endif

    </div>
</div>

</div></div>{{-- /track /track-wrap --}}

<script>
(function () {
    const TOTAL      = 2;
    const INACTIVITY = 60000;
    const DUR        = '380ms';
    const EASE       = 'cubic-bezier(.25,.46,.45,.94)';

    let current = 0;
    let inactTimer;

    const track = document.getElementById('track');
    function goTo(idx) {
        if (idx < 0 || idx >= TOTAL) return;
        current = idx;
        track.style.transition = `transform ${DUR} ${EASE}`;
        track.style.transform  = `translateX(${-idx * 100}vw)`;
        resetInactivity();
    }

    const btnVerMais = document.getElementById('btn-ver-mais');
    if (btnVerMais) {
        btnVerMais.addEventListener('click',    () => goTo(1));
        btnVerMais.addEventListener('touchend', e => { e.preventDefault(); goTo(1); });
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'ArrowRight') goTo(current + 1);
        if (e.key === 'ArrowLeft')  goTo(current - 1);
    });

    document.querySelector('.btn-back-fixed').addEventListener('touchend', e => {
        e.preventDefault();
        window.location.href = '/';
    });

    function resetInactivity() {
        clearTimeout(inactTimer);
        inactTimer = setTimeout(() => window.location.href = '/', INACTIVITY);
    }
    document.addEventListener('pointerdown', resetInactivity);

    goTo(0);
})();
</script>
</body>
</html>
