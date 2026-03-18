<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo — Adega</title>
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

        body { font-family: 'Georgia', serif; background: var(--cream); color: var(--text); }

        nav {
            background: var(--wine-dark);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand { color: var(--gold); font-size: 1.75rem; font-weight: bold; text-decoration: none; }

        .nav-links { display: flex; gap: 1.5rem; list-style: none; }
        .nav-links a { color: #e8d5c0; text-decoration: none; font-size: 0.95rem; }
        .nav-links a:hover { color: var(--gold); }

        .page-header {
            background: var(--wine);
            color: #fff;
            padding: 3rem 2rem;
            text-align: center;
        }

        .page-header h1 { font-size: 2.25rem; color: var(--gold); margin-bottom: 0.5rem; }
        .page-header p { color: #e8d5c0; }

        .catalog-layout {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 2rem;
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 2rem;
        }

        /* SIDEBAR */
        .sidebar { }

        .sidebar-section {
            background: #fff;
            border-radius: 6px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 1px 6px rgba(123,31,58,.06);
        }

        .sidebar-section h3 {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--wine);
            margin-bottom: 1rem;
        }

        .filter-link {
            display: block;
            padding: 0.4rem 0;
            color: #5a3a3a;
            text-decoration: none;
            font-size: 0.9rem;
            border-bottom: 1px solid #f0e8e8;
        }

        .filter-link:last-child { border-bottom: none; }
        .filter-link:hover, .filter-link.active { color: var(--wine); font-weight: bold; }

        .filter-link .count { color: #9a7070; font-size: 0.8rem; float: right; }

        /* SEARCH */
        .search-form { display: flex; gap: 0.5rem; }
        .search-input {
            flex: 1;
            padding: 0.6rem 0.85rem;
            border: 1px solid #d4c4c4;
            border-radius: 4px;
            font-size: 0.9rem;
            font-family: inherit;
        }

        .search-btn {
            background: var(--wine);
            color: #fff;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .search-btn:hover { background: var(--wine-dark); }

        /* RESULTS */
        .results-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            color: #7a5c5c;
            font-size: 0.9rem;
        }

        .clear-filter {
            color: var(--wine);
            text-decoration: none;
            font-size: 0.85rem;
        }

        .wine-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .wine-card {
            background: #fff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(123,31,58,.08);
            transition: transform .2s, box-shadow .2s;
        }

        .wine-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(123,31,58,.15);
        }

        .wine-card-img {
            height: 160px;
            background: linear-gradient(160deg, var(--wine) 0%, var(--wine-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }

        .wine-card-body { padding: 1rem; }

        .wine-badge {
            display: inline-block;
            background: #f3e8ef;
            color: var(--wine);
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.15rem 0.5rem;
            border-radius: 2rem;
            margin-bottom: 0.4rem;
        }

        .wine-name { font-size: 0.95rem; font-weight: bold; line-height: 1.3; margin-bottom: 0.25rem; }
        .wine-meta { font-size: 0.78rem; color: #9a7070; margin-bottom: 0.6rem; }

        .wine-price { font-size: 1.2rem; font-weight: bold; color: var(--wine); }
        .wine-vintage { font-size: 0.75rem; color: #9a7070; }

        .no-wines { grid-column: 1/-1; text-align: center; color: #9a7070; padding: 3rem 0; }

        /* PAGINATION */
        .pagination { display: flex; gap: 0.5rem; justify-content: center; margin-top: 2.5rem; flex-wrap: wrap; }
        .pagination a, .pagination span {
            padding: 0.5rem 0.85rem;
            border: 1px solid #d4c4c4;
            border-radius: 4px;
            text-decoration: none;
            color: var(--wine);
            font-size: 0.9rem;
        }

        .pagination .active { background: var(--wine); color: #fff; border-color: var(--wine); }
        .pagination a:hover { background: #f3e8ef; }

        footer {
            background: var(--wine-dark);
            color: #e8d5c0;
            text-align: center;
            padding: 2.5rem 2rem;
            font-size: 0.9rem;
            margin-top: 2rem;
        }

        footer a { color: var(--gold); text-decoration: none; }

        @media (max-width: 768px) {
            .catalog-layout { grid-template-columns: 1fr; }
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

<div class="page-header">
    <h1>Catálogo de Vinhos</h1>
    <p>{{ $wines->total() }} {{ $wines->total() === 1 ? 'rótulo encontrado' : 'rótulos encontrados' }}</p>
</div>

<div class="catalog-layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-section">
            <h3>Buscar</h3>
            <form action="{{ route('catalogo') }}" method="GET" class="search-form">
                @if(request('tipo'))
                    <input type="hidden" name="tipo" value="{{ request('tipo') }}">
                @endif
                <input type="text" name="busca" value="{{ request('busca') }}" placeholder="Nome do vinho…" class="search-input">
                <button type="submit" class="search-btn">🔍</button>
            </form>
        </div>

        <div class="sidebar-section">
            <h3>Tipo de Vinho</h3>
            <a href="{{ route('catalogo', array_filter(['busca' => request('busca')])) }}"
               class="filter-link {{ !request('tipo') ? 'active' : '' }}">
                Todos os tipos
            </a>
            @foreach($types as $type)
            <a href="{{ route('catalogo', array_filter(['tipo' => $type->slug, 'busca' => request('busca')])) }}"
               class="filter-link {{ request('tipo') === $type->slug ? 'active' : '' }}">
                {{ $type->name }}<span class="count">{{ $type->wines_count }}</span>
            </a>
            @endforeach
        </div>
    </aside>

    <!-- MAIN -->
    <main>
        <div class="results-header">
            <span>
                @if(request('tipo') || request('busca'))
                    Filtrando por:
                    @if(request('busca')) "{{ request('busca') }}" @endif
                    @if(request('tipo')) · {{ $types->firstWhere('slug', request('tipo'))?->name }} @endif
                    · <a href="{{ route('catalogo') }}" class="clear-filter">Limpar filtros</a>
                @else
                    Exibindo todos os vinhos
                @endif
            </span>
            <span>Página {{ $wines->currentPage() }} de {{ $wines->lastPage() }}</span>
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
                        @if($wine->producer){{ $wine->producer->name }} · @endif
                        @if($wine->country){{ $wine->country->name }}@endif
                    </div>
                    @if($wine->sale_price)
                    <div class="wine-price">
                        R$ {{ number_format($wine->sale_price, 2, ',', '.') }}
                        @if($wine->vintage)
                        <span class="wine-vintage">· {{ $wine->vintage }}</span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="no-wines">Nenhum vinho encontrado para os filtros selecionados.</div>
            @endforelse
        </div>

        @if($wines->hasPages())
        <div class="pagination">
            @if($wines->onFirstPage())
                <span>‹ Anterior</span>
            @else
                <a href="{{ $wines->previousPageUrl() }}">‹ Anterior</a>
            @endif

            @foreach($wines->getUrlRange(max(1, $wines->currentPage()-2), min($wines->lastPage(), $wines->currentPage()+2)) as $page => $url)
                @if($page == $wines->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if($wines->hasMorePages())
                <a href="{{ $wines->nextPageUrl() }}">Próxima ›</a>
            @else
                <span>Próxima ›</span>
            @endif
        </div>
        @endif
    </main>

</div>

<footer>
    <p>&copy; {{ date('Y') }} Adega. · <a href="/">Início</a> · <a href="/admin">Painel Admin</a></p>
</footer>

</body>
</html>
