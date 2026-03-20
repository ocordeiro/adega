<?php

namespace App\Http\Controllers;

use App\Models\DrinkRecipe;
use App\Models\Spirit;
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

        $spirit = Spirit::where('is_active', true)
            ->whereNotNull('barcode')
            ->inRandomOrder()
            ->first();

        $candidates = collect();
        if ($wine) {
            $candidates->push(['type' => 'wine', 'barcode' => $wine->barcode]);
        }
        if ($spirit) {
            $candidates->push(['type' => 'spirit', 'barcode' => $spirit->barcode]);
        }

        if ($candidates->isEmpty()) {
            return redirect('/')->with('error', 'Nenhuma bebida cadastrada ainda.');
        }

        $pick = $candidates->random();

        return $pick['type'] === 'wine'
            ? redirect()->route('kiosk.wine', ['barcode' => $pick['barcode']])
            : redirect()->route('kiosk.spirit', ['barcode' => $pick['barcode']]);
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
                'foods.occasions' => fn ($q) => $q->where('is_active', true),
                'recipes' => fn ($q) => $q->where('is_active', true),
            ])
            ->first();

        if ($wine) {
            return view('kiosk.wine', compact('wine'));
        }

        // Fallback: buscar em destilados
        $spirit = Spirit::where('barcode', $barcode)
            ->where('is_active', true)
            ->first();

        if ($spirit) {
            return redirect()->route('kiosk.spirit', ['barcode' => $barcode]);
        }

        return redirect('/')->with('error', 'Bebida não encontrada. Tente escanear novamente.');
    }

    public function showSpirit(string $barcode): View|RedirectResponse
    {
        $spirit = Spirit::where('barcode', $barcode)
            ->where('is_active', true)
            ->with([
                'spiritType',
                'country',
                'producer',
            ])
            ->first();

        if (! $spirit) {
            return redirect('/')->with('error', 'Destilado não encontrado. Tente escanear novamente.');
        }

        $drinkRecipes = DrinkRecipe::where('is_active', true)
            ->whereHas('ingredients', fn ($q) => $q->where('spirit_id', $spirit->id))
            ->with('ingredients')
            ->get();

        return view('kiosk.spirit', compact('spirit', 'drinkRecipes'));
    }
}
