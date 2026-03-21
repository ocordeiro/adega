<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>{{ $wine->name }} — Adega Sommelier</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/flag-icons/flag-icons.min.css') }}">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:     #d93f35;
            --primary-dk:  #b83028;
            --primary-rgb: 217, 63, 53;
            --bg:          #dce9f0;
            --bg-alt:      #eaf2f7;
            --surface:     #ffffff;
            --surface-alt: #f5f5f7;
            --text:        #1a1a2e;
            --muted:       #6b7280;
            --border:      rgba(0,0,0,.1);
            --shadow:      rgba(0,0,0,.12);
            --slide-dur: 380ms;
            --ease-out:  cubic-bezier(.25,.46,.45,.94);
            --ease-in:   cubic-bezier(.55,.06,.68,.19);
        }

        html { font-size: 107%; }
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
            width: 2.64rem; height: 2.64rem;
            background: var(--primary); border: none;
            border-radius: 100px; color: #fff;
            text-decoration: none; cursor: pointer;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.22), inset 0 -2px 0 rgba(0,0,0,.18), 0 4px 14px rgba(var(--primary-rgb),.4);
            transition: background .15s, box-shadow .15s;
            touch-action: manipulation;
        }
        .btn-back-fixed svg { width: 16px; height: 16px; }
        .btn-back-fixed:active { background: var(--primary-dk); box-shadow: inset 0 2px 4px rgba(0,0,0,.25); }


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
        .slide-wine { background: var(--surface); }

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
            background: linear-gradient(160deg, var(--surface-alt) 0%, var(--surface-alt) 100%);
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

        .stars { display: flex; gap: .24rem; justify-content: center; margin-top: .9rem; }
        .star       { color: var(--primary); font-size: 1.08rem; }
        .star.empty { color: rgba(var(--primary-rgb),.25); }

        /* ── Coluna de info ──────────────────────────── */
        .wine-info-col {
            grid-area: info;
            background: var(--surface);
            display: flex; flex-direction: column;
            align-items: center; text-align: center;
            padding: 3rem clamp(1.5rem, 4vw, 3rem) 2rem;
            gap: 1.2rem;
        }


        .wine-badge {
            display: inline-flex; align-items: center; gap: .35rem;
            background: rgba(var(--primary-rgb),.1); border: 1px solid rgba(var(--primary-rgb),.2);
            border-radius: 100px; padding: .24rem .9rem;
            font-size: .78rem; font-weight: 500; letter-spacing: .12em; text-transform: uppercase;
            color: var(--primary); width: fit-content;
        }

        .wine-name {
            font-size: clamp(2.1875rem, 4.2vw, 3.375rem);
            line-height: 1.15; color: var(--primary); font-weight: 800;
            text-align: center;
        }
        @media (orientation: portrait) and (max-width: 599px) { .wine-name { font-size: clamp(1.6875rem, 6vw, 2.25rem); } }

        .wine-vintage {
            font-style: italic; color: var(--muted); font-size: 1.14rem; font-weight: 400;
            margin-top: -.4rem; text-align: center;
        }

        .wine-desc {
            font-size: 1.03rem; font-weight: 400; line-height: 1.65;
            color: var(--muted); max-width: 520px;
        }
        @media (orientation: portrait) and (max-width: 599px) { .wine-desc { font-size: .9rem; } }

        /* Meta: produtor, origem etc. — compact row */
        .wine-meta-row {
            display: flex; flex-wrap: wrap; justify-content: center; gap: .6rem 1.08rem;
            padding: .72rem 1rem;
            background: var(--surface-alt); border-radius: 12px;
            border: 1px solid var(--border);
        }
        .meta-item { display: flex; flex-direction: column; align-items: center; gap: .12rem; }
        .meta-label { font-size: .7rem; letter-spacing: .1em; text-transform: uppercase; color: var(--muted); }
        .meta-value { font-size: .96rem; font-weight: 700; color: var(--text); }
        .country-flag { font-size: 1em; vertical-align: middle; margin-top: -.1em; border-radius: 2px; }

        .grape-tags { display: flex; flex-wrap: wrap; justify-content: center; gap: .3rem; }
        .grape-tag {
            background: var(--surface-alt); border: 1px solid var(--border);
            border-radius: 100px; padding: .24rem .72rem;
            font-size: .82rem; color: var(--muted);
        }

        /* ── Seção "Combina com" ─────────────────────── */
        .harmony-section { display: flex; flex-direction: column; align-items: center; gap: .78rem; flex: 1; margin-top: .6rem; }

        .harmony-divider {
            height: 1px; background: var(--border); margin-bottom: .25rem; align-self: stretch;
        }

        .harmony-title {
            font-size: clamp(1.1875rem, 2.4vw, 1.5625rem);
            font-weight: 800; color: var(--primary); letter-spacing: .01em;
            text-align: center;
        }

        /* Grid de 3 colunas: ocasião + alimento */
        .pairing-grid {
            display: grid;
            grid-template-columns: repeat(3, clamp(86px, 11vw, 240px));
            gap: clamp(.6rem, 1.4vw, 2.4rem);
            margin-top: auto; margin-bottom: auto;
        }
        @media (orientation: portrait) and (max-width: 599px) {
            .pairing-grid { gap: .6rem; }
        }

        .pairing-col {
            display: flex; flex-direction: column;
            align-items: center; gap: .42rem;
            text-align: center;
        }

        .pairing-occ-label {
            font-size: .8rem; color: var(--muted); font-weight: 400;
            letter-spacing: .02em; line-height: 1.3;
            min-height: 2em;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .pairing-img-wrap {
            width: clamp(67px, 9.6vw, 192px);
            height: clamp(67px, 9.6vw, 192px);
            border-radius: 50%;
            overflow: hidden;
            background: var(--surface);
            box-shadow: 0 4px 14px var(--shadow);
            flex-shrink: 0;
        }
        .pairing-img-wrap img {
            width: 100%; height: 100%; object-fit: cover; display: block;
        }
        .pairing-img-ph {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.4rem; background: var(--bg-alt);
        }

        .pairing-food-name {
            font-size: .94rem; font-weight: 600; color: var(--text);
            line-height: 1.3; overflow: hidden; text-overflow: ellipsis;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            max-width: 100%;
        }
        .pairing-food-cat {
            font-size: .76rem; color: var(--primary); letter-spacing: .04em;
            text-transform: uppercase; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
            max-width: 100%;
        }
        @media (orientation: portrait) and (max-width: 599px) {
            .pairing-img-wrap { width: 67px; height: 67px; }
            .pairing-food-name { font-size: .82rem; }
            .pairing-food-cat  { font-size: .68rem; }
            .pairing-occ-label { font-size: .7rem; }
        }

        /* ── Botão "Ver mais" ───────────────────────── */
        .btn-ver-mais {
            display: inline-flex; align-items: center; gap: .55rem;
            background: var(--primary); color: #fff;
            border: none; border-radius: 100px;
            padding: .86rem 2.4rem;
            font-family: 'Nunito', sans-serif;
            font-size: 1.08rem; font-weight: 700; letter-spacing: .02em;
            cursor: pointer; touch-action: manipulation;
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,.22),
                inset 0 -2px 0 rgba(0,0,0,.18),
                0 4px 14px rgba(var(--primary-rgb),.4);
            transition: box-shadow .15s, background .15s;
            width: fit-content; align-self: center;
            margin-top: auto;
        }
        .btn-ver-mais:active {
            background: var(--primary-dk);
            box-shadow: inset 0 2px 4px rgba(0,0,0,.25), 0 1px 4px rgba(var(--primary-rgb),.3);
        }
        @media (orientation: portrait) and (max-width: 599px) {
            .btn-ver-mais { font-size: .94rem; padding: .72rem 1.68rem; }
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
                gap: .9rem;
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
            padding: 2.4rem clamp(1.8rem, 4.8vw, 3rem) .9rem;
            text-align: center; max-width: 900px; margin-inline: auto; width: 100%;
        }
        @media (orientation: portrait) and (max-width: 599px) { .section-header { padding: 1.8rem 1.2rem .6rem; } }

        .section-label {
            font-size: .78rem; letter-spacing: .22em; text-transform: uppercase;
            color: var(--primary); opacity: .8; margin-bottom: .5rem;
        }
        .section-title {
            font-size: clamp(1.6875rem, 3.6vw, 2.625rem); font-weight: 800; color: var(--primary);
        }
        .section-sub { margin-top: .4rem; font-size: 1rem; font-weight: 300; color: var(--muted); }

        .sub-label {
            font-size: .76rem; letter-spacing: .2em; text-transform: uppercase;
            color: var(--muted); margin-bottom: .66rem;
        }
        .pairings-body {
            flex: 1; min-height: 0; overflow: hidden;
            padding: 0 clamp(1.2rem, 3.6vw, 3rem) 1.5rem;
            display: flex; flex-direction: column; gap: .9rem;
            width: 100%;
        }
        .pairings-body > div {
            display: flex; flex-direction: column; flex: 1; min-height: 0;
        }
        @media (orientation: portrait) and (max-width: 599px) { .pairings-body { padding: 0 1.2rem 1.2rem; } }

        /* Occasion cards */
        .occ-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(180px, 50vw), 1fr));
            gap: .96rem;
        }
        .occ-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 17px; padding: 1.08rem 1.2rem;
            display: flex; flex-direction: column; gap: .48rem;
            box-shadow: 0 2px 8px var(--shadow);
        }
        .occ-card-icon { font-size: 1.92rem; line-height: 1; }
        .occ-card-name { font-size: 1.03rem; font-weight: 600; color: var(--text); }
        .occ-card-desc { font-size: .88rem; font-weight: 300; color: var(--muted); line-height: 1.5; }

        /* Food cards */
        .food-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: 1fr;
            gap: clamp(.6rem, 1.4vw, 1.02rem);
            flex: 1; min-height: 0;
        }
        .food-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden;
            box-shadow: 0 2px 8px var(--shadow);
            display: flex; flex-direction: column; min-height: 0;
        }
        .food-img { width: 100%; aspect-ratio: 16/9; object-fit: cover; display: block; flex-shrink: 0; }
        .food-ph  { width: 100%; aspect-ratio: 16/9; display: flex; align-items: center; justify-content: center; background: var(--bg); font-size: 2.4rem; flex-shrink: 0; }
        .food-body  { padding: .72rem .96rem; flex: 1; min-height: 0; overflow: hidden; }
        .food-name  { font-size: 1.03rem; font-weight: 600; color: var(--text); margin-bottom: .12rem; }
        .food-cat   { font-size: .76rem; color: var(--primary); letter-spacing: .06em; text-transform: uppercase; }
        .food-notes { margin-top: .36rem; font-size: .86rem; color: var(--muted); line-height: 1.5; font-style: italic; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

        .empty-state { text-align: center; padding: 3rem 2rem; color: var(--muted); font-size: 1.14rem; }

        /* Recipes */
        .recipes-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: 1fr;
            gap: clamp(.6rem, 1.4vw, 1.32rem);
            flex: 1; min-height: 0;
        }

        .recipe-card { background: var(--surface); border: 1px solid var(--border); border-radius: 19px; overflow: hidden; box-shadow: 0 2px 8px var(--shadow); display: flex; flex-direction: column; min-height: 0; }
        .recipe-img  { width: 100%; aspect-ratio: 4/1; object-fit: cover; display: block; flex-shrink: 0; }
        .recipe-ph   { width: 100%; aspect-ratio: 4/1; display: flex; align-items: center; justify-content: center; background: var(--bg); font-size: 3em; flex-shrink: 0; }
        .recipe-body { padding: .54em .9em; flex: 1; min-height: 0; display: flex; flex-direction: column; overflow: hidden; }
        .recipe-tags { display: flex; gap: .3rem; flex-wrap: wrap; margin-bottom: .34em; flex-shrink: 0; }
        .recipe-tag  { font-size: .9em; letter-spacing: .07em; text-transform: uppercase; padding: .18em .6em; border-radius: 100px; background: var(--bg); border: 1px solid var(--border); }
        .recipe-tag.difficulty { color: var(--muted); }
        .recipe-tag.time       { color: var(--primary); }
        .recipe-name { font-size: 2.04em; font-weight: 700; color: var(--text); line-height: 1.2; margin-bottom: .34em; flex-shrink: 0; }
        .recipe-desc { font-size: 1.32em; font-weight: 300; color: var(--muted); line-height: 1.5; margin-bottom: .36em; flex-shrink: 0; }
        .recipe-note { margin-bottom: .36em; padding: .38em .66em; background: rgba(var(--primary-rgb),.05); border-left: 2px solid rgba(var(--primary-rgb),.3); border-radius: 0 8px 8px 0; font-size: 1.2em; color: var(--muted); font-style: italic; flex-shrink: 0; }

        .recipe-ingredients {
            margin-bottom: .54em; padding: .46em .66em;
            background: rgba(var(--primary-rgb),.04); border: 1px solid rgba(var(--primary-rgb),.12);
            border-radius: 10px; flex-shrink: 0;
        }
        .recipe-ingredients-title {
            font-size: .94em; letter-spacing: .12em; text-transform: uppercase;
            color: var(--primary); opacity: .8; margin-bottom: .26em;
        }
        .recipe-ingredient {
            display: flex; justify-content: space-between; align-items: baseline;
            font-size: 1.2em; color: var(--text); padding: .14em 0;
            border-bottom: 1px solid var(--border);
        }
        .recipe-ingredient:last-child { border-bottom: none; }
        .recipe-ingredient-name { font-weight: 400; }
        .recipe-ingredient-qty  { font-weight: 300; color: var(--muted); font-size: .9em; white-space: nowrap; }

        .recipe-steps { padding-top: .48em; font-size: 1.2em; font-weight: 300; line-height: 1.55; color: var(--muted); white-space: pre-line; border-top: 1px solid var(--border); }
    </style>
@include('kiosk.partials.settings-cache')
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
                        <rect x="36" y="4" width="18" height="22" rx="4" fill="var(--primary)"/>
                        <path d="M28 36 Q20 50 18 70 L18 138 Q18 148 28 150 L62 150 Q72 148 72 138 L72 70 Q70 50 62 36 Z" fill="var(--primary)"/>
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
                <span class="meta-value"><span class="fi fi-{{ strtolower($wine->country->code) }} fis country-flag"></span> {{ $wine->country->name }}{{ $wine->region ? ', '.$wine->region->name : '' }}</span>
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

        {{-- Harmonizações preview: até 3 pratos com ocasiões distintas --}}
        @if($previewFoods->count())
        <div class="harmony-section">
            <div class="harmony-divider"></div>
            <p class="harmony-title">Combina com</p>
            <div class="pairing-grid">
                @foreach($previewFoods as $food)
                <div class="pairing-col">
                    @php $occ = $food->occasions->first(); @endphp
                    @if($occ)<p class="pairing-occ-label">{{ $occ->icon }} {{ $occ->name }}</p>@endif
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

                        @if($recipe->ingredients->count())
                        <div class="recipe-ingredients">
                            <p class="recipe-ingredients-title">Ingredientes</p>
                            @foreach($recipe->ingredients as $ing)
                            <div class="recipe-ingredient">
                                <span class="recipe-ingredient-name">{{ $ing->name }}</span>
                                <span class="recipe-ingredient-qty">
                                    @if($ing->quantity){{ $ing->quantity }}@endif
                                    @if($ing->unit) {{ $ing->unit }}@endif
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @endif

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
    const TOTAL           = 2;
    const INACTIVITY_HOME = 60000;
    const INACTIVITY_READ = 180000;
    const DUR             = '380ms';
    const EASE            = 'cubic-bezier(.25,.46,.45,.94)';

    let current = 0;
    let inactTimer;

    const track = document.getElementById('track');
    function goTo(idx) {
        if (idx < 0 || idx >= TOTAL) return;
        current = idx;
        track.style.transition = `transform ${DUR} ${EASE}`;
        track.style.transform  = `translateX(${-idx * 100}vw)`;
        resetInactivity();
        if (idx === 1) fitRecipeCards();
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

    const btnBack = document.querySelector('.btn-back-fixed');
    let _backTouched = false;
    btnBack.addEventListener('touchend', e => {
        e.preventDefault();
        _backTouched = true;
        setTimeout(() => _backTouched = false, 300);
        if (current > 0) goTo(current - 1); else window.location.href = '/';
    });
    btnBack.addEventListener('click', e => {
        e.preventDefault();
        if (_backTouched) return;
        if (current > 0) goTo(current - 1); else window.location.href = '/';
    });

    function resetInactivity() {
        clearTimeout(inactTimer);
        const delay = current === 0 ? INACTIVITY_HOME : INACTIVITY_READ;
        inactTimer = setTimeout(() => window.location.href = '/', delay);
    }
    document.addEventListener('pointerdown', resetInactivity);

    goTo(0);

    // Ajusta font-size dos recipe cards dinamicamente, uniforme entre todos
    function fitRecipeCards() {
        const cards = Array.from(document.querySelectorAll('.recipe-card'));
        if (!cards.length) return;
        let minFs = Infinity;
        cards.forEach(card => {
            const body = card.querySelector('.recipe-body');
            if (!body) return;
            card.style.fontSize = '';
            let lo = 8, hi = 52;
            card.style.fontSize = hi + 'px';
            for (let i = 0; i < 14; i++) {
                const mid = (lo + hi) / 2;
                card.style.fontSize = mid + 'px';
                body.scrollHeight <= body.clientHeight ? (lo = mid) : (hi = mid);
            }
            minFs = Math.min(minFs, Math.floor(lo));
        });
        cards.forEach(card => card.style.fontSize = minFs + 'px');
    }
    requestAnimationFrame(fitRecipeCards);
    let _rrt; window.addEventListener('resize', () => { clearTimeout(_rrt); _rrt = setTimeout(fitRecipeCards, 120); });
})();
</script>

@include('kiosk.partials.settings-script')
</body>
</html>
