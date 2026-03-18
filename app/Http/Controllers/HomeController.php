<?php

namespace App\Http\Controllers;

use App\Models\Wine;
use App\Models\WineType;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $wines = Wine::with(['wineType', 'country', 'producer'])
            ->where('is_active', true)
            ->whereNotNull('sale_price')
            ->latest()
            ->take(12)
            ->get();

        $types = WineType::withCount(['wines' => fn ($q) => $q->where('is_active', true)])
            ->get()
            ->filter(fn ($t) => $t->wines_count > 0)
            ->values();

        return view('home', compact('wines', 'types'));
    }

    public function catalogo(): View
    {
        $query = Wine::with(['wineType', 'country', 'producer'])
            ->where('is_active', true);

        if (request('tipo')) {
            $query->whereHas('wineType', fn ($q) => $q->where('slug', request('tipo')));
        }

        if (request('busca')) {
            $query->where('name', 'like', '%' . request('busca') . '%');
        }

        $wines = $query->orderBy('name')->paginate(24)->withQueryString();

        $types = WineType::withCount(['wines' => fn ($q) => $q->where('is_active', true)])
            ->get()
            ->filter(fn ($t) => $t->wines_count > 0)
            ->values();

        return view('catalogo', compact('wines', 'types'));
    }
}
