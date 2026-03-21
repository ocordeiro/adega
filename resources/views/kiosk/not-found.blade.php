<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Produto não encontrado — Adega Sommelier</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:     #d93f35;
            --primary-dk:  #b83028;
            --primary-rgb: 217, 63, 53;
            --bg:          #dce9f0;
            --surface:     #ffffff;
            --text:        #1a1a2e;
            --muted:       #6b7280;
            --border:      rgba(0,0,0,.1);
        }

        html { font-size: 107%; }
        html, body {
            width: 100%; height: 100%;
            overflow: hidden;
            background: var(--surface);
            font-family: 'Nunito', sans-serif;
            -webkit-font-smoothing: antialiased;
            user-select: none; -webkit-user-select: none;
            color: var(--text);
        }

        .bg {
            position: fixed; inset: 0;
            background:
                radial-gradient(ellipse 70% 50% at 50% 100%, rgba(var(--primary-rgb),.05) 0%, transparent 65%),
                radial-gradient(ellipse 50% 40% at 15% 20%, rgba(220,233,240,.8) 0%, transparent 60%),
                var(--surface);
            pointer-events: none;
        }

        .page {
            position: relative; z-index: 1;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            min-height: 100vh;
            padding: 2rem;
            gap: 0;
        }

        .logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.375rem, 5.4vw, 3.8125rem);
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

        .rule {
            width: 1px;
            height: clamp(2.16rem, 4.8vh, 3.6rem);
            background: linear-gradient(to bottom, transparent, var(--border), transparent);
            margin-bottom: 2.5rem;
        }

        /* icon */
        .icon-wrap {
            margin-bottom: 2rem;
            opacity: .18;
        }

        /* steps */
        .step {
            display: none;
            flex-direction: column;
            align-items: center;
            text-align: center;
            animation: fadeIn .35s ease;
        }
        .step.active { display: flex; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .step-title {
            font-size: clamp(1.5rem, 3.2vw, 2.1rem);
            font-weight: 700;
            color: var(--text);
            line-height: 1.25;
            margin-bottom: .6rem;
        }
        .step-sub {
            font-size: clamp(.92rem, 1.8vw, 1.08rem);
            font-weight: 400;
            color: var(--muted);
            margin-bottom: 2rem;
            max-width: 420px;
        }

        .btn-group {
            display: flex; gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn {
            display: inline-flex; align-items: center; gap: .55rem;
            border: none; border-radius: 100px;
            padding: .86rem 2.4rem;
            font-family: 'Nunito', sans-serif;
            font-size: 1.08rem; font-weight: 700; letter-spacing: .02em;
            text-decoration: none;
            cursor: pointer;
            transition: box-shadow .15s, background .15s, transform .1s;
            touch-action: manipulation;
        }
        .btn:active { transform: scale(.97); }

        .btn-primary {
            background: var(--primary); color: #fff;
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,.22),
                inset 0 -2px 0 rgba(0,0,0,.18),
                0 4px 14px rgba(var(--primary-rgb),.35);
        }
        .btn-primary:active {
            background: var(--primary-dk);
            box-shadow: inset 0 2px 4px rgba(0,0,0,.25), 0 1px 4px rgba(var(--primary-rgb),.3);
        }

        .btn-outline {
            background: transparent; color: var(--muted);
            border: 2px solid var(--border);
        }
        .btn-outline:active {
            background: rgba(0,0,0,.04);
        }

        .btn-wine {
            background: #722F37; color: #fff;
            box-shadow: 0 4px 14px rgba(114,47,55,.35);
        }
        .btn-wine:active { background: #5a252c; }

        .btn-spirit {
            background: #b8860b; color: #fff;
            box-shadow: 0 4px 14px rgba(184,134,11,.35);
        }
        .btn-spirit:active { background: #996f09; }

        /* success check */
        .success-icon {
            width: 64px; height: 64px;
            margin-bottom: 1.5rem;
            color: #22c55e;
        }

        .footer {
            position: fixed; bottom: 1.25rem; left: 0; right: 0;
            text-align: center;
            font-size: .74rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--muted);
            opacity: .45;
        }
    </style>
@include('kiosk.partials.settings-cache')
</head>
<body>
<div class="bg"></div>

<div class="page">
    <div class="logo">Adega</div>
    <div class="logo-sub">Sommelier Digital</div>

    <div class="rule"></div>

    <!-- Step 1: Produto não encontrado -->
    <div class="step active" id="step-1">
        <div class="icon-wrap">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                <line x1="8" y1="11" x2="14" y2="11"/>
            </svg>
        </div>
        <p class="step-title">Produto não encontrado</p>
        <p class="step-sub">O produto é uma bebida?</p>
        <div class="btn-group">
            <button class="btn btn-primary" onclick="showStep(2)">Sim</button>
            <a href="/" class="btn btn-outline">Não</a>
        </div>
    </div>

    <!-- Step 2: Que tipo de bebida? -->
    <div class="step" id="step-2">
        <div class="icon-wrap">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M8 2h8l-1 9H9L8 2z"/>
                <path d="M12 11v7"/>
                <path d="M8 22h8"/>
                <path d="M7 2h10"/>
            </svg>
        </div>
        <p class="step-title">A bebida em questão é:</p>
        <p class="step-sub">Selecione o tipo para nos ajudar a cadastrar</p>
        <div class="btn-group">
            <button class="btn btn-wine" onclick="report('wine')">Vinho</button>
            <button class="btn btn-spirit" onclick="report('spirit')">Destilado</button>
        </div>
    </div>

    <!-- Step 3: Obrigado -->
    <div class="step" id="step-3">
        <svg class="success-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <p class="step-title">Obrigado!</p>
        <p class="step-sub">Em breve estaremos adicionando as informações deste produto.</p>
        <div class="btn-group">
            <a href="/" class="btn btn-primary">Voltar ao início</a>
        </div>
    </div>
</div>

<div class="footer">Sommelier Digital de Bebidas</div>

<script>
function showStep(n) {
    document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
    document.getElementById('step-' + n).classList.add('active');
}

function report(type) {
    fetch('{{ route("kiosk.report") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ barcode: '{{ $barcode }}', type: type }),
    }).then(() => showStep(3)).catch(() => showStep(3));

    // Auto-redirect after 8 seconds
    setTimeout(() => { window.location.href = '/'; }, 8000);
}
</script>
@include('kiosk.partials.settings-script')
</body>
</html>
