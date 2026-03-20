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
            background: var(--white);
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
        .slide-drinks {
            display: flex; flex-direction: column; overflow: hidden;
        }

        /* ════════════════════════════════════════════════
           SLIDE 1 — DESTILADO + preview
        ════════════════════════════════════════════════ */
        .slide-spirit { background: var(--white); }

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
            background: linear-gradient(160deg, #f0f2f5 0%, #e4e7ed 100%);
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
            background: var(--white);
            display: flex; flex-direction: column;
            align-items: center; text-align: center;
            padding: 3rem clamp(1.5rem, 4vw, 3rem) 2rem;
            gap: 1rem;
        }


        .spirit-badge {
            display: inline-flex; align-items: center; gap: .3rem;
            background: rgba(217,63,53,.1); border: 1px solid rgba(217,63,53,.2);
            border-radius: 100px; padding: .2rem .75rem;
            font-size: .65rem; font-weight: 500; letter-spacing: .12em; text-transform: uppercase;
            color: var(--primary); width: fit-content;
        }

        .spirit-name {
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            line-height: 1.15; color: var(--primary); font-weight: 800;
            text-align: center;
        }
        @media (orientation: portrait) and (max-width: 599px) { .spirit-name { font-size: clamp(1.4rem, 5vw, 1.9rem); } }

        .spirit-desc {
            font-size: .86rem; font-weight: 400; line-height: 1.65;
            color: var(--muted); max-width: 480px; text-align: center;
        }
        @media (orientation: portrait) and (max-width: 599px) { .spirit-desc { font-size: .75rem; } }

        .spirit-meta-row {
            display: flex; flex-wrap: wrap; justify-content: center; gap: .5rem .9rem;
            padding: .6rem .85rem;
            background: #f5f5f7; border-radius: 10px;
            border: 1px solid var(--border);
        }
        .meta-item { display: flex; flex-direction: column; align-items: center; gap: .1rem; }
        .meta-label { font-size: .58rem; letter-spacing: .1em; text-transform: uppercase; color: var(--muted); }
        .meta-value { font-size: .8rem; font-weight: 700; color: var(--text); }

        /* ── Seção preview ───────────────────────────── */
        .harmony-section { display: flex; flex-direction: column; align-items: center; gap: .65rem; flex: 1; margin-top: .5rem; }
        .harmony-divider { height: 1px; background: var(--border); margin-bottom: .2rem; align-self: stretch; }
        .harmony-title {
            font-size: clamp(1rem, 2vw, 1.3rem);
            font-weight: 800; color: var(--primary); letter-spacing: .01em;
            text-align: center;
        }

        /* Grid de 3 colunas: ocasião + drink */
        .pairing-grid {
            display: grid;
            grid-template-columns: repeat(3, clamp(72px, 9vw, 200px));
            gap: clamp(.5rem, 1.2vw, 2rem);
            margin-top: auto; margin-bottom: auto;
        }
        @media (orientation: portrait) and (max-width: 599px) { .pairing-grid { gap: .5rem; } }

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
        .pairing-img-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
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


        /* ── PORTRAIT phones ─────────────────────────── */
        @media (orientation: portrait) and (max-width: 599px) {
            .spirit-layout {
                grid-template-columns: 1fr;
                grid-template-areas: "photo" "info";
                min-height: unset;
            }
            .spirit-photo-col { padding: 1rem; min-height: 200px; }
            .spirit-info-col { padding: 1.25rem 1rem 1rem; gap: .75rem; }
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

        /* Drink cards */
        .drinks-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: 1fr;
            gap: clamp(.5rem, 1vw, .85rem);
            flex: 1; min-height: 0;
        }

        .drink-card { container-type: size; background: var(--white); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: 0 2px 8px var(--shadow); display: flex; flex-direction: column; min-height: 0; }
        .drink-img  { width: 100%; aspect-ratio: 5/1; object-fit: cover; display: block; flex-shrink: 0; }
        .drink-ph   { width: 100%; aspect-ratio: 5/1; display: flex; align-items: center; justify-content: center; background: var(--bg); font-size: max(1rem, 3cqh); flex-shrink: 0; }
        .drink-body { padding: max(.3rem,.8cqh) max(.5rem,1.2cqh); flex: 1; min-height: 0; display: flex; flex-direction: column; }
        .drink-tags { display: flex; gap: .25rem; flex-wrap: wrap; margin-bottom: max(.12rem,.4cqh); flex-shrink: 0; }
        .drink-tag  { font-size: max(.48rem, 1.3cqh); letter-spacing: .06em; text-transform: uppercase; padding: .12rem .45rem; border-radius: 100px; background: var(--bg); border: 1px solid var(--border); }
        .drink-tag.difficulty { color: var(--muted); }
        .drink-tag.time       { color: var(--primary); }
        .drink-name { font-size: max(.72rem, 2.8cqh); font-weight: 700; color: var(--text); line-height: 1.2; margin-bottom: max(.12rem,.4cqh); flex-shrink: 0; }
        .drink-desc { font-size: max(.6rem, 2cqh); font-weight: 300; color: var(--muted); line-height: 1.45; margin-bottom: max(.15rem,.45cqh); flex-shrink: 0; }

        .drink-ingredients {
            margin-bottom: max(.2rem,.55cqh); padding: max(.2rem,.5cqh) max(.4rem,.8cqh);
            background: rgba(217,63,53,.04); border: 1px solid rgba(217,63,53,.12);
            border-radius: 8px; flex-shrink: 0;
        }
        .drink-ingredients-title {
            font-size: max(.45rem, 1.3cqh); letter-spacing: .12em; text-transform: uppercase;
            color: var(--primary); opacity: .8; margin-bottom: max(.1rem,.35cqh);
        }
        .drink-ingredient {
            display: flex; justify-content: space-between; align-items: baseline;
            font-size: max(.55rem, 1.8cqh); color: var(--text); padding: .1rem 0;
            border-bottom: 1px solid var(--border);
        }
        .drink-ingredient:last-child { border-bottom: none; }
        .drink-ingredient-name { font-weight: 400; }
        .drink-ingredient-qty  { font-weight: 300; color: var(--muted); font-size: max(.5rem, 1.6cqh); white-space: nowrap; }

        .drink-steps { padding-top: max(.2rem,.55cqh); font-size: max(.55rem, 1.8cqh); font-weight: 300; line-height: 1.5; color: var(--muted); white-space: pre-line; border-top: 1px solid var(--border); }

        .drinks-body {
            flex: 1; min-height: 0; overflow: hidden;
            padding: 0 clamp(1rem, 3vw, 2.5rem) 1.25rem;
            display: flex; flex-direction: column; gap: .75rem;
            max-width: 1400px; width: 100%; margin-inline: auto;
        }
        .drinks-body > div { display: flex; flex-direction: column; flex: 1; min-height: 0; }
        @media (orientation: portrait) and (max-width: 599px) { .drinks-body { padding: 0 1rem 1rem; } }

        .empty-state { text-align: center; padding: 3rem 2rem; color: var(--muted); font-size: .95rem; }
    </style>
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
                        <rect x="18" y="2" width="12" height="10" rx="2" fill="#d93f35"/>
                        <path d="M14 16 Q10 24 10 34 L10 54 Q10 60 16 62 L32 62 Q38 60 38 54 L38 34 Q38 24 34 16 Z" fill="#d93f35"/>
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

        {{-- Preview: até 3 drinks --}}
        @php $previewDrinks = $drinkRecipes->take(3); @endphp
        @if($previewDrinks->count())
        <div class="harmony-section">
            <div class="harmony-divider"></div>
            <p class="harmony-title">Drinks que combinam</p>
            <div class="pairing-grid">
                @foreach($previewDrinks as $drink)
                <div class="pairing-col">
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
