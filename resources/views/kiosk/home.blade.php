<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Adega — Sommelier Digital</title>
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
        }

        html, body {
            width: 100%; height: 100%;
            overflow: hidden;
            background: var(--white);
            font-family: 'Nunito', sans-serif;
            -webkit-font-smoothing: antialiased;
            user-select: none; -webkit-user-select: none;
            color: var(--text);
        }

        /* background sutil */
        .bg {
            position: fixed; inset: 0;
            background:
                radial-gradient(ellipse 70% 50% at 50% 100%, rgba(217,63,53,.05) 0%, transparent 65%),
                radial-gradient(ellipse 50% 40% at 15% 20%,  rgba(220,233,240,.8) 0%, transparent 60%),
                var(--white);
            pointer-events: none;
        }

        /* layout */
        .page {
            position: relative; z-index: 1;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            min-height: 100vh;
            padding: 2rem;
            gap: 0;
        }

        /* logo */
        .logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.4rem, 5.4vw, 3.84rem);
            font-weight: 300;
            letter-spacing: .35em;
            color: var(--primary);
            text-transform: uppercase;
            margin-bottom: .5rem;
        }
        .logo-sub {
            font-size: clamp(.84rem, 1.7vw, 1.08rem);
            font-weight: 400;
            letter-spacing: .45em;
            color: var(--muted);
            text-transform: uppercase;
            margin-bottom: 2.5rem;
        }

        /* divider */
        .rule {
            width: 1px;
            height: clamp(2.16rem, 4.8vh, 3.6rem);
            background: linear-gradient(to bottom, transparent, var(--border), transparent);
            margin-bottom: 2.5rem;
        }

        /* scanner area */
        .scanner-wrap {
            position: relative;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 2.5rem;
        }

        /* glow */
        .scanner-glow {
            position: absolute; inset: -43px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(217,63,53,.08) 0%, transparent 70%);
            animation: glowPulse 3s ease-in-out infinite;
            pointer-events: none;
        }
        @keyframes glowPulse {
            0%, 100% { opacity: .5; transform: scale(.93); }
            50%       { opacity: 1;  transform: scale(1.07); }
        }

        /* corner brackets */
        .scanner-frame {
            position: relative;
            width: clamp(192px, 26vw, 276px);
            height: clamp(192px, 26vw, 276px);
        }
        .scanner-frame::before,
        .scanner-frame::after,
        .corner-bl::before,
        .corner-br::before {
            content: '';
            position: absolute;
            width: 34px; height: 34px;
            border-color: rgba(217,63,53,.55);
            border-style: solid;
        }
        .scanner-frame::before { top: 0; left: 0;  border-width: 2px 0 0 2px; border-radius: 3px 0 0 0; }
        .scanner-frame::after  { top: 0; right: 0; border-width: 2px 2px 0 0; border-radius: 0 3px 0 0; }
        .corner-bl::before     { bottom: 0; left: 0;  border-width: 0 0 2px 2px; border-radius: 0 0 0 3px; }
        .corner-br::before     { bottom: 0; right: 0; border-width: 0 2px 2px 0; border-radius: 0 0 3px 0; }

        /* scan line */
        .scan-line {
            position: absolute;
            left: 10%; right: 10%;
            height: 1.5px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            border-radius: 2px;
            animation: scanMove 2.4s cubic-bezier(.45,.05,.55,.95) infinite;
            opacity: 0;
        }
        @keyframes scanMove {
            0%   { top: 12%; opacity: 0; }
            10%  { opacity: .7; }
            90%  { opacity: .7; }
            100% { top: 88%; opacity: 0; }
        }

        /* barcode icon */
        .scanner-inner {
            position: absolute; inset: 29px;
            display: flex; align-items: center; justify-content: center;
        }
        .barcode-icon { opacity: .1; width: 106px; height: 77px; }

        /* instruction */
        .instruction {
            text-align: center;
            margin-bottom: 1.8rem;
        }
        .instruction-main {
            font-size: clamp(1.56rem, 3.36vw, 2.28rem);
            font-weight: 700;
            color: var(--text);
            letter-spacing: .01em;
            line-height: 1.25;
        }
        .instruction-sub {
            margin-top: .6rem;
            font-size: clamp(.86rem, 1.7vw, 1.02rem);
            font-weight: 400;
            color: var(--muted);
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        /* demo button — igual btn-ver-mais dos outros slides */
        .demo-btn {
            display: inline-flex; align-items: center; gap: .55rem;
            background: var(--primary); color: var(--white);
            border: none; border-radius: 100px;
            padding: .86rem 2.4rem;
            font-family: 'Nunito', sans-serif;
            font-size: 1.08rem; font-weight: 700; letter-spacing: .02em;
            text-decoration: none;
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,.22),
                inset 0 -2px 0 rgba(0,0,0,.18),
                0 4px 14px rgba(217,63,53,.35);
            transition: box-shadow .15s, background .15s;
            touch-action: manipulation;
        }
        .demo-btn:active {
            background: var(--primary-dk);
            box-shadow: inset 0 2px 4px rgba(0,0,0,.25), 0 1px 4px rgba(217,63,53,.3);
        }

        /* flash de erro */
        .flash {
            position: fixed;
            bottom: 3rem; left: 50%; transform: translateX(-50%);
            background: rgba(220,38,38,.9);
            color: #fff;
            padding: .9rem 2.1rem;
            border-radius: 100px;
            font-size: 1.08rem; font-weight: 600;
            opacity: 0; transition: opacity .4s;
            pointer-events: none;
            white-space: nowrap;
            z-index: 10;
            box-shadow: 0 4px 16px rgba(217,63,53,.35);
        }
        .flash.show { opacity: 1; }

        /* footer */
        .footer {
            position: fixed; bottom: 1.25rem; left: 0; right: 0;
            text-align: center;
            font-size: .74rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--muted);
            opacity: .45;
        }

        @media (max-height: 500px) {
            .rule { height: 1.4rem; margin-bottom: 1.2rem; }
            .logo-sub { margin-bottom: 1.2rem; }
            .scanner-frame { width: 144px; height: 144px; }
            .scanner-wrap { margin-bottom: 1.2rem; }
        }

        /* ── AD OVERLAY ───────────────────────────────────────────────── */
        .ad-overlay {
            position: fixed; inset: 0; z-index: 100;
            background: #000;
            display: flex; align-items: center; justify-content: center;
            opacity: 1;
            transition: opacity .5s ease;
        }
        .ad-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }
        .ad-video {
            width: 100%; height: 100%;
            object-fit: contain;
        }
        .ad-hint {
            position: absolute; bottom: 2rem; left: 50%;
            transform: translateX(-50%);
            color: rgba(255,255,255,.45);
            font-size: .9rem; letter-spacing: .2em; text-transform: uppercase;
            white-space: nowrap;
            animation: adPulse 2.2s ease-in-out infinite;
            pointer-events: none;
        }
        @keyframes adPulse {
            0%,100% { opacity: .2; }
            50%      { opacity: .7; }
        }
    </style>
</head>
<body>
<div class="bg"></div>

<div class="page">
    <div class="logo">Adega</div>
    <div class="logo-sub">Sommelier Digital</div>

    <div class="rule"></div>

    <div class="scanner-wrap">
        <div class="scanner-glow"></div>
        <div class="scanner-frame">
            <div class="corner-bl"></div>
            <div class="corner-br"></div>
            <div class="scan-line"></div>
            <div class="scanner-inner">
                <svg class="barcode-icon" width="88" height="64" viewBox="0 0 88 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0"  y="0" width="4" height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="8"  y="0" width="2" height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="13" y="0" width="6" height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="23" y="0" width="2" height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="28" y="0" width="4" height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="36" y="0" width="2" height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="42" y="0" width="6" height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="52" y="0" width="2" height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="57" y="0" width="4" height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="65" y="0" width="2" height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="70" y="0" width="6" height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="80" y="0" width="2" height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="84" y="0" width="4" height="64" rx="1" fill="#1a1a2e"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="instruction">
        <p class="instruction-main">Aproxime a garrafa do leitor</p>
        <p class="instruction-sub">para descobrir harmonizações e receitas</p>
    </div>

    <a href="{{ route('kiosk.random') }}" class="demo-btn">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        Ver uma bebida aleatória
    </a>
</div>

@if(session('error'))
<div class="flash show" id="flash">{{ session('error') }}</div>
<script>
    setTimeout(() => document.getElementById('flash').classList.remove('show'), 4000);
</script>
@endif

<div class="footer">Aproxime o código de barras</div>

@if(!empty($adUrls))
<div id="ad-overlay" class="ad-overlay hidden">
    <video id="ad-video" class="ad-video" muted playsinline></video>
    <div class="ad-hint">Toque para continuar</div>
</div>

<script>
(function () {
    const AD_URLS     = @json($adUrls);
    const AD_DELAY    = 30000; // 30 seg de inatividade

    if (!AD_URLS.length) return;

    const overlay = document.getElementById('ad-overlay');
    const video   = document.getElementById('ad-video');
    let adIndex   = 0;
    let adTimer;

    function playAd(idx) {
        video.src = AD_URLS[idx];
        video.load();
        video.play().catch(() => {});
    }

    video.addEventListener('ended', () => {
        adIndex = (adIndex + 1) % AD_URLS.length;
        playAd(adIndex);
    });

    function showAds() {
        adIndex = 0;
        playAd(0);
        overlay.classList.remove('hidden');
    }

    function dismissAds() {
        overlay.classList.add('hidden');
        video.pause();
        video.src = '';
        scheduleAds();
    }

    function scheduleAds() {
        clearTimeout(adTimer);
        adTimer = setTimeout(showAds, AD_DELAY);
    }

    overlay.addEventListener('click',    dismissAds);
    overlay.addEventListener('touchend', e => { e.preventDefault(); dismissAds(); });
    document.addEventListener('keydown', () => { if (!overlay.classList.contains('hidden')) dismissAds(); });
    document.addEventListener('pointerdown', e => {
        if (!overlay.contains(e.target)) scheduleAds();
    });

    scheduleAds();
})();
</script>
@endif
</body>
</html>
