<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>{{ $spirit->name }} — Adega Sommelier</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
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
            background: var(--surface);
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
        .slide-drinks {
            display: flex; flex-direction: column; overflow: hidden;
        }

        /* ════════════════════════════════════════════════
           SLIDE 1 — DESTILADO + preview
        ════════════════════════════════════════════════ */
        .slide-spirit { background: var(--surface); }

        .spirit-slide-inner { height: 100vh; display: flex; flex-direction: column; overflow: hidden; }

        .spirit-layout {
            flex: 1;
            display: grid;
            height: 100%;
            overflow: hidden;
            grid-template-columns: clamp(240px, 38vw, 440px) 1fr;
            grid-template-areas: "photo info";
            min-height: 100vh;
        }

        /* ── Coluna da foto ──────────────────────────── */
        .spirit-photo-col {
            grid-area: photo;
            background: linear-gradient(160deg, var(--surface-alt) 0%, var(--surface-alt) 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 2rem 1.5rem;
            position: relative;
        }

        .spirit-photo-frame {
            position: relative; width: 100%;
            display: flex; align-items: center; justify-content: center;
        }
        .spirit-photo {
            max-width: 100%; max-height: 70vh;
            width: auto; height: auto;
            object-fit: contain; display: block;
            filter: drop-shadow(0 24px 40px rgba(0,0,0,.28)) drop-shadow(0 6px 12px rgba(0,0,0,.16));
        }
        .spirit-photo-placeholder {
            width: 100%; aspect-ratio: 2/3;
            display: flex; align-items: center; justify-content: center;
        }

        /* ── Coluna de info ──────────────────────────── */
        .spirit-info-col {
            grid-area: info;
            background: var(--surface);
            display: flex; flex-direction: column;
            align-items: center; text-align: center;
            padding: 3rem clamp(1.5rem, 4vw, 3rem) 2rem;
            gap: 1.2rem;
        }


        .spirit-badge {
            display: inline-flex; align-items: center; gap: .35rem;
            background: rgba(var(--primary-rgb),.1); border: 1px solid rgba(var(--primary-rgb),.2);
            border-radius: 100px; padding: .24rem .9rem;
            font-size: .78rem; font-weight: 500; letter-spacing: .12em; text-transform: uppercase;
            color: var(--primary); width: fit-content;
        }

        .spirit-name {
            font-size: clamp(35px, 4.2vw, 54px);
            line-height: 1.15; color: var(--primary); font-weight: 800;
            text-align: center;
        }
        @media (orientation: portrait) and (max-width: 599px) { .spirit-name { font-size: clamp(27px, 6vw, 36px); } }

        .spirit-desc {
            font-size: 1.03rem; font-weight: 400; line-height: 1.65;
            color: var(--muted); max-width: 520px; text-align: center;
        }
        @media (orientation: portrait) and (max-width: 599px) { .spirit-desc { font-size: .9rem; } }

        .spirit-meta-row {
            display: flex; flex-wrap: wrap; justify-content: center; gap: .6rem 1.08rem;
            padding: .72rem 1rem;
            background: var(--surface-alt); border-radius: 12px;
            border: 1px solid var(--border);
        }
        .meta-item { display: flex; flex-direction: column; align-items: center; gap: .12rem; }
        .meta-label { font-size: .7rem; letter-spacing: .1em; text-transform: uppercase; color: var(--muted); }
        .meta-value { font-size: .96rem; font-weight: 700; color: var(--text); }

        /* ── Seção preview ───────────────────────────── */
        .harmony-section { display: flex; flex-direction: column; align-items: center; gap: .78rem; flex: 1; margin-top: .6rem; }
        .harmony-divider { height: 1px; background: var(--border); margin-bottom: .25rem; align-self: stretch; }
        .harmony-title {
            font-size: clamp(19px, 2.4vw, 25px);
            font-weight: 800; color: var(--primary); letter-spacing: .01em;
            text-align: center;
        }

        /* Grid de 3 colunas: ocasião + drink */
        .pairing-grid {
            display: grid;
            grid-template-columns: repeat(3, clamp(86px, 11vw, 240px));
            gap: clamp(.6rem, 1.4vw, 2.4rem);
            margin-top: auto; margin-bottom: auto;
        }
        @media (orientation: portrait) and (max-width: 599px) { .pairing-grid { gap: .6rem; } }

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
        .pairing-img-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
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


        /* ── PORTRAIT phones ─────────────────────────── */
        @media (orientation: portrait) and (max-width: 599px) {
            .spirit-layout {
                grid-template-columns: 1fr;
                grid-template-areas: "photo" "info";
                min-height: unset;
            }
            .spirit-photo-col { padding: 1rem; min-height: 200px; }
            .spirit-info-col { padding: 1.25rem 1rem 1rem; gap: .9rem; }
            .spirit-meta-row { display: none; }
        }

        @media (orientation: portrait) and (min-width: 600px) {
            .spirit-layout { grid-template-columns: clamp(180px, 30vw, 260px) 1fr; }
        }

        /* ════════════════════════════════════════════════
           SLIDE 2 — DRINKS completo
        ════════════════════════════════════════════════ */
        .slide-drinks { background: var(--bg-alt); }

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
            font-size: clamp(27px, 3.6vw, 42px); font-weight: 800; color: var(--primary);
        }
        .section-sub { margin-top: .4rem; font-size: 1rem; font-weight: 300; color: var(--muted); }

        .sub-label {
            font-size: .76rem; letter-spacing: .2em; text-transform: uppercase;
            color: var(--muted); margin-bottom: .66rem;
        }

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

        /* Drink cards */
        .drinks-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: 1fr;
            gap: clamp(.6rem, 1.2vw, 1.02rem);
            flex: 1; min-height: 0;
        }

        .drink-card { background: var(--surface); border: 1px solid var(--border); border-radius: 19px; overflow: hidden; box-shadow: 0 2px 8px var(--shadow); display: flex; flex-direction: column; min-height: 0; }
        .drink-img  { width: 100%; aspect-ratio: 5/1; object-fit: cover; display: block; flex-shrink: 0; }
        .drink-ph   { width: 100%; aspect-ratio: 5/1; display: flex; align-items: center; justify-content: center; background: var(--bg); font-size: 3em; flex-shrink: 0; }
        .drink-body { padding: .54em .9em; flex: 1; min-height: 0; display: flex; flex-direction: column; overflow: hidden; }
        .drink-tags { display: flex; gap: .3rem; flex-wrap: wrap; margin-bottom: .34em; flex-shrink: 0; }
        .drink-tag  { font-size: .9em; letter-spacing: .06em; text-transform: uppercase; padding: .18em .6em; border-radius: 100px; background: var(--bg); border: 1px solid var(--border); }
        .drink-tag.difficulty { color: var(--muted); }
        .drink-tag.time       { color: var(--primary); }
        .drink-name { font-size: 2.04em; font-weight: 700; color: var(--text); line-height: 1.2; margin-bottom: .34em; flex-shrink: 0; }
        .drink-desc { font-size: 1.32em; font-weight: 300; color: var(--muted); line-height: 1.45; margin-bottom: .36em; flex-shrink: 0; }

        .drink-ingredients {
            margin-bottom: .54em; padding: .46em .66em;
            background: rgba(var(--primary-rgb),.04); border: 1px solid rgba(var(--primary-rgb),.12);
            border-radius: 10px; flex-shrink: 0;
        }
        .drink-ingredients-title {
            font-size: .94em; letter-spacing: .12em; text-transform: uppercase;
            color: var(--primary); opacity: .8; margin-bottom: .26em;
        }
        .drink-ingredient {
            display: flex; justify-content: space-between; align-items: baseline;
            font-size: 1.2em; color: var(--text); padding: .14em 0;
            border-bottom: 1px solid var(--border);
        }
        .drink-ingredient:last-child { border-bottom: none; }
        .drink-ingredient-name { font-weight: 400; }
        .drink-ingredient-qty  { font-weight: 300; color: var(--muted); font-size: .9em; white-space: nowrap; }

        .drink-steps { padding-top: .48em; font-size: 1.2em; font-weight: 300; line-height: 1.5; color: var(--muted); white-space: pre-line; border-top: 1px solid var(--border); }

        .drinks-body {
            flex: 1; min-height: 0; overflow: hidden;
            padding: 0 clamp(1.2rem, 3.6vw, 3rem) 1.5rem;
            display: flex; flex-direction: column; gap: .9rem;
            max-width: 1400px; width: 100%; margin-inline: auto;
        }
        .drinks-body > div { display: flex; flex-direction: column; flex: 1; min-height: 0; }
        @media (orientation: portrait) and (max-width: 599px) { .drinks-body { padding: 0 1.2rem 1.2rem; } }

        .empty-state { text-align: center; padding: 3rem 2rem; color: var(--muted); font-size: 1.14rem; }
    </style>
@include('kiosk.partials.settings-cache')
</head>
<body>

<a href="/" class="btn-back-fixed" title="Voltar">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
</a>

<div class="track-wrap"><div class="track" id="track">

{{-- ═══════ SLIDE 1: DESTILADO + preview ═══════ --}}
<div class="slide slide-spirit"><div class="spirit-slide-inner">
<div class="spirit-layout">

    {{-- Foto --}}
    <div class="spirit-photo-col">
        @php $photo = $spirit->getFirstMedia('photos'); @endphp
        <div class="spirit-photo-frame">
            @if($photo)
                <img class="spirit-photo"
                     src="{{ $photo->hasGeneratedConversion('card') ? $photo->getUrl('card') : $photo->getUrl() }}"
                     alt="{{ $spirit->name }}" draggable="false">
            @else
                <div class="spirit-photo-placeholder">
                    <svg width="48" height="64" viewBox="0 0 48 64" fill="none" opacity=".35">
                        <rect x="18" y="2" width="12" height="10" rx="2" fill="var(--primary)"/>
                        <path d="M14 16 Q10 24 10 34 L10 54 Q10 60 16 62 L32 62 Q38 60 38 54 L38 34 Q38 24 34 16 Z" fill="var(--primary)"/>
                    </svg>
                </div>
            @endif
        </div>
    </div>

    {{-- Info --}}
    <div class="spirit-info-col">

        @if($spirit->spiritType)
        <div class="spirit-badge">
            <svg width="6" height="6" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="5"/></svg>
            {{ $spirit->spiritType->name }}
        </div>
        @endif
        <h1 class="spirit-name">{{ $spirit->name }}</h1>

        @if($spirit->description)<p class="spirit-desc">{{ $spirit->description }}</p>@endif

        @php
            $hasMeta = $spirit->producer || $spirit->country || $spirit->alcohol_content;
        @endphp
        @if($hasMeta)
        <div class="spirit-meta-row">
            @if($spirit->producer)
            <div class="meta-item">
                <span class="meta-label">Produtor</span>
                <span class="meta-value">{{ $spirit->producer->name }}</span>
            </div>
            @endif
            @if($spirit->country)
            <div class="meta-item">
                <span class="meta-label">Origem</span>
                <span class="meta-value">{{ $spirit->country->name }}</span>
            </div>
            @endif
            @if($spirit->alcohol_content)
            <div class="meta-item">
                <span class="meta-label">Álcool</span>
                <span class="meta-value">{{ number_format($spirit->alcohol_content, 1) }}%</span>
            </div>
            @endif
        </div>
        @endif

        {{-- Preview: até 3 drinks com ocasiões distintas --}}
        @if($previewDrinks->count())
        <div class="harmony-section">
            <div class="harmony-divider"></div>
            <p class="harmony-title">Drinks que combinam</p>
            <div class="pairing-grid">
                @foreach($previewDrinks as $drink)
                <div class="pairing-col">
                    @php $occ = $drink->occasions->first(); @endphp
                    @if($occ)<p class="pairing-occ-label">{{ $occ->icon }} {{ $occ->name }}</p>@endif
                    <div class="pairing-img-wrap">
                        @php $dImg = $drink->getFirstMedia('photo'); @endphp
                        @if($dImg)
                            <img src="{{ $dImg->hasGeneratedConversion('thumb') ? $dImg->getUrl('thumb') : $dImg->getUrl() }}"
                                 alt="{{ $drink->name }}" draggable="false">
                        @else
                            <div class="pairing-img-ph">🍸</div>
                        @endif
                    </div>
                    <p class="pairing-food-name">{{ $drink->name }}</p>
                    @if($drink->difficulty)<p class="pairing-food-cat">{{ ucfirst($drink->difficulty) }}</p>@endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($drinkRecipes->count() > 0)
        <button class="btn-ver-mais" id="btn-ver-mais">
            Ver mais
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </button>
        @endif

    </div>{{-- /spirit-info-col --}}

</div>{{-- /spirit-layout --}}
</div></div>{{-- /inner /slide --}}

{{-- ═══════ SLIDE 2: DRINKS completo ═══════ --}}
<div class="slide slide-drinks">
    <div class="section-header">
        <p class="section-label">Drinks</p>
        <h2 class="section-title">O que preparar com {{ $spirit->name }}?</h2>
        <p class="section-sub">Receitas de coquetéis e drinks</p>
    </div>

    <div class="drinks-body">

        @php $slideDrinks = $drinkRecipes->take(3); @endphp
        @if($slideDrinks->count())
        <div>
            <p class="sub-label">Receitas de drinks</p>
            <div class="drinks-grid">
                @foreach($slideDrinks as $drink)
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
        </div>
        @else
        <div class="empty-state">
            <p style="font-size:2rem;margin-bottom:.7rem">🍸</p>
            <p>Receitas de drinks ainda não cadastradas.</p>
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
        if (idx === 1) fitDrinkCards();
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

    // Ajusta font-size dos drink cards dinamicamente, uniforme entre todos
    function fitDrinkCards() {
        const cards = Array.from(document.querySelectorAll('.drink-card'));
        if (!cards.length) return;
        let minFs = Infinity;
        cards.forEach(card => {
            const body = card.querySelector('.drink-body');
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
    requestAnimationFrame(fitDrinkCards);
    let _drt; window.addEventListener('resize', () => { clearTimeout(_drt); _drt = setTimeout(fitDrinkCards, 120); });
})();
</script>

@include('kiosk.partials.settings-script')
</body>
</html>
