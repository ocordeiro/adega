<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\DrinkRecipe;
use App\Models\Spirit;
use App\Models\Wine;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KioskController extends Controller
{
    private function activeAdUrls(): array
    {
        return Ad::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Ad $ad) => $ad->getFirstMediaUrl('video'))
            ->filter()
            ->values()
            ->all();
    }

    public function home(): View
    {
        $adUrls = $this->activeAdUrls();
        return view('kiosk.home', compact('adUrls'));
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
                'recipes' => fn ($q) => $q->where('is_active', true)->with('ingredients'),
            ])
            ->first();

        if ($wine) {
            // Seleciona até 3 foods com ocasiões distintas para o preview
            $seenOccasions = [];
            $previewFoods = collect();
            foreach ($wine->foods->shuffle() as $food) {
                $occ = $food->occasions->first();
                $key = $occ ? $occ->id : 'none_'.$food->id;
                if (! in_array($key, $seenOccasions)) {
                    $seenOccasions[] = $key;
                    $previewFoods->push($food);
                    if ($previewFoods->count() === 3) break;
                }
            }

            return view('kiosk.wine', compact('wine', 'previewFoods'));
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
            ->with(['ingredients', 'occasions' => fn ($q) => $q->where('is_active', true)])
            ->get();

        // Seleciona até 3 drinks com ocasiões distintas para o preview
        $seenOccasions = [];
        $previewDrinks = collect();
        foreach ($drinkRecipes->shuffle() as $drink) {
            $occ = $drink->occasions->first();
            $key = $occ ? $occ->id : 'none_'.$drink->id;
            if (! in_array($key, $seenOccasions)) {
                $seenOccasions[] = $key;
                $previewDrinks->push($drink);
                if ($previewDrinks->count() === 3) break;
            }
        }

        return view('kiosk.spirit', compact('spirit', 'drinkRecipes', 'previewDrinks'));
    }
}
