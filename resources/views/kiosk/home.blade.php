<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Adega — Sommelier Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --gold:    #c8a96e;
            --gold-lt: #e2c98a;
            --cream:   #f5f0eb;
            --base:    #0d0d0f;
            --surface: rgba(255,255,255,.04);
            --border:  rgba(255,255,255,.08);
            --muted:   rgba(245,240,235,.4);
        }

        html, body {
            width: 100%; height: 100%;
            overflow: hidden;
            background: var(--base);
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            user-select: none;
            cursor: default;
        }

        /* background */
        .bg {
            position: fixed; inset: 0;
            background:
                radial-gradient(ellipse 70% 50% at 50% 100%, rgba(200,169,110,.07) 0%, transparent 65%),
                radial-gradient(ellipse 50% 40% at 15% 20%,  rgba(200,169,110,.04) 0%, transparent 60%),
                radial-gradient(ellipse 40% 35% at 85% 80%,  rgba(200,169,110,.03) 0%, transparent 60%),
                var(--base);
        }

        /* subtle noise */
        .bg::after {
            content: '';
            position: fixed; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.012'%3E%3Ccircle cx='40' cy='40' r='1'/%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        /* layout */
        .page {
            position: relative; z-index: 1;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            min-height: 100vh;
            gap: 0;
            padding: 2rem;
        }

        /* logo */
        .logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 300;
            letter-spacing: .35em;
            color: var(--gold);
            text-transform: uppercase;
            margin-bottom: .5rem;
        }
        .logo-sub {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(.75rem, 1.4vw, 1rem);
            font-weight: 300;
            letter-spacing: .5em;
            color: rgba(200,169,110,.4);
            text-transform: uppercase;
            margin-bottom: 3rem;
        }

        /* divider line */
        .rule {
            width: 1px;
            height: clamp(2rem, 4vh, 3.5rem);
            background: linear-gradient(to bottom, transparent, rgba(200,169,110,.3), transparent);
            margin-bottom: 3rem;
        }

        /* scanner area */
        .scanner-wrap {
            position: relative;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 3rem;
        }

        /* corner brackets */
        .scanner-frame {
            position: relative;
            width: clamp(160px, 22vw, 240px);
            height: clamp(160px, 22vw, 240px);
        }
        .scanner-frame::before,
        .scanner-frame::after,
        .corner-bl::before,
        .corner-br::before {
            content: '';
            position: absolute;
            width: 28px; height: 28px;
            border-color: rgba(200,169,110,.55);
            border-style: solid;
        }
        .scanner-frame::before {
            top: 0; left: 0;
            border-width: 2px 0 0 2px;
            border-radius: 2px 0 0 0;
        }
        .scanner-frame::after {
            top: 0; right: 0;
            border-width: 2px 2px 0 0;
            border-radius: 0 2px 0 0;
        }
        .corner-bl::before {
            bottom: 0; left: 0;
            border-width: 0 0 2px 2px;
            border-radius: 0 0 0 2px;
        }
        .corner-br::before {
            bottom: 0; right: 0;
            border-width: 0 2px 2px 0;
            border-radius: 0 0 2px 0;
        }

        /* scan line */
        .scan-line {
            position: absolute;
            left: 10%; right: 10%;
            height: 1.5px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            border-radius: 2px;
            animation: scanMove 2.4s cubic-bezier(.45,.05,.55,.95) infinite;
            opacity: 0;
        }
        @keyframes scanMove {
            0%   { top: 12%;  opacity: 0; }
            10%  { opacity: .8; }
            90%  { opacity: .8; }
            100% { top: 88%; opacity: 0; }
        }

        /* barcode icon inside frame */
        .scanner-inner {
            position: absolute; inset: 24px;
            display: flex; align-items: center; justify-content: center;
        }
        .barcode-icon {
            opacity: .12;
        }

        /* glow behind frame */
        .scanner-glow {
            position: absolute; inset: -30px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(200,169,110,.08) 0%, transparent 70%);
            animation: glowPulse 3s ease-in-out infinite;
            pointer-events: none;
        }
        @keyframes glowPulse {
            0%, 100% { opacity: .6; transform: scale(.95); }
            50%       { opacity: 1;  transform: scale(1.05); }
        }

        /* instruction text */
        .instruction {
            text-align: center;
            margin-bottom: 1.25rem;
        }
        .instruction-main {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.4rem, 3vw, 2rem);
            font-weight: 300;
            color: var(--cream);
            letter-spacing: .04em;
            line-height: 1.3;
        }
        .instruction-sub {
            margin-top: .5rem;
            font-size: clamp(.75rem, 1.5vw, .88rem);
            font-weight: 300;
            color: var(--muted);
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        /* touch demo link */
        .demo-btn {
            display: inline-flex; align-items: center; gap: .55rem;
            padding: .6rem 1.4rem;
            border: 1px solid var(--border);
            border-radius: 100px;
            background: var(--surface);
            text-decoration: none;
            transition: background .2s, border-color .2s;
            backdrop-filter: blur(8px);
        }
        .demo-btn:active { background: rgba(255,255,255,.07); border-color: rgba(200,169,110,.3); }
        .demo-btn-icon {
            font-size: .85rem;
        }
        .demo-btn-text {
            font-size: .78rem;
            font-weight: 400;
            color: var(--muted);
            letter-spacing: .06em;
        }

        /* flash */
        .flash {
            position: fixed;
            bottom: 3rem; left: 50%; transform: translateX(-50%);
            background: rgba(220,38,38,.88);
            color: #fff;
            padding: .75rem 1.75rem;
            border-radius: 100px;
            font-size: .9rem;
            font-weight: 500;
            opacity: 0;
            transition: opacity .4s;
            pointer-events: none;
            white-space: nowrap;
            z-index: 10;
        }
        .flash.show { opacity: 1; }

        /* footer */
        .footer {
            position: fixed; bottom: 1.25rem; left: 0; right: 0;
            text-align: center;
            font-size: .65rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: rgba(200,169,110,.18);
        }

        /* responsive: landscape phones */
        @media (max-height: 500px) {
            .rule { height: 1.5rem; margin-bottom: 1.5rem; }
            .logo-sub { margin-bottom: 1.5rem; }
            .scanner-frame { width: 120px; height: 120px; }
            .scanner-wrap { margin-bottom: 1.5rem; }
            .page { gap: 0; }
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
                <!-- barcode icon -->
                <svg class="barcode-icon" width="88" height="64" viewBox="0 0 88 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0"  y="0"  width="4"  height="64" rx="1" fill="white"/>
                    <rect x="8"  y="0"  width="2"  height="64" rx="1" fill="white"/>
                    <rect x="13" y="0"  width="6"  height="64" rx="1" fill="white"/>
                    <rect x="23" y="0"  width="2"  height="64" rx="1" fill="white"/>
                    <rect x="28" y="0"  width="4"  height="64" rx="1" fill="white"/>
                    <rect x="36" y="0"  width="2"  height="64" rx="1" fill="white"/>
                    <rect x="42" y="0"  width="6"  height="64" rx="1" fill="white"/>
                    <rect x="52" y="0"  width="2"  height="64" rx="1" fill="white"/>
                    <rect x="57" y="0"  width="4"  height="64" rx="1" fill="white"/>
                    <rect x="65" y="0"  width="2"  height="64" rx="1" fill="white"/>
                    <rect x="70" y="0"  width="6"  height="64" rx="1" fill="white"/>
                    <rect x="80" y="0"  width="2"  height="64" rx="1" fill="white"/>
                    <rect x="84" y="0"  width="4"  height="64" rx="1" fill="white"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="instruction">
        <p class="instruction-main">Aproxime a garrafa do leitor</p>
        <p class="instruction-sub">para descobrir harmonizações e receitas</p>
    </div>

    <a href="{{ route('kiosk.random') }}" class="demo-btn">
        <span class="demo-btn-icon">🍸</span>
        <span class="demo-btn-text">Ver uma bebida aleatória</span>
    </a>
</div>

@if(session('error'))
<div class="flash show" id="flash">{{ session('error') }}</div>
<script>
    setTimeout(() => document.getElementById('flash').classList.remove('show'), 4000);
</script>
@endif

<div class="footer">Aproxime o código de barras &bull; Deslize para explorar</div>
</body>
</html>
