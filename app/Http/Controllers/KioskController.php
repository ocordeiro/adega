<?php

namespace App\Http\Controllers;

use App\Models\Wine;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KioskController extends Controller
{
    public function home(): View
    {
        return view('kiosk.home');
    }

    public function random(): RedirectResponse
    {
        $wine = Wine::where('is_active', true)
            ->whereNotNull('barcode')
            ->inRandomOrder()
            ->first();

        if (! $wine) {
            return redirect('/')->with('error', 'Nenhum vinho cadastrado ainda.');
        }

        return redirect()->route('kiosk.wine', ['barcode' => $wine->barcode]);
    }

    public function show(string $barcode): View|RedirectResponse
    {
        $wine = Wine::where('barcode', $barcode)
            ->where('is_active', true)
            ->with([
                'wineType',
                'country',
                'region',
                'producer',
                'grapeVarieties',
                'foods.foodCategory',
                'occasions' => fn ($q) => $q->where('is_active', true),
                'recipes' => fn ($q) => $q->where('is_active', true),
            ])
            ->first();

        if (! $wine) {
            return redirect('/')->with('error', 'Vinho não encontrado. Tente escanear novamente.');
        }

        return view('kiosk.wine', compact('wine'));
    }
}
