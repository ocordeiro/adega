<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SpiritResource;
use App\Http\Resources\V1\WineResource;
use App\Models\BeverageReport;
use App\Models\DrinkRecipe;
use App\Models\Spirit;
use App\Models\Wine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BeverageController extends Controller
{
    public function show(string $barcode): WineResource|SpiritResource|JsonResponse
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
                'foods.media',
                'foods.occasions' => fn ($q) => $q->where('is_active', true),
                'recipes' => fn ($q) => $q->where('is_active', true),
                'recipes.ingredients',
                'recipes.media',
                'media',
            ])
            ->first();

        if ($wine) {
            return new WineResource($wine);
        }

        $spirit = Spirit::where('barcode', $barcode)
            ->where('is_active', true)
            ->with(['spiritType', 'country', 'producer', 'media'])
            ->first();

        if ($spirit) {
            $drinkRecipes = DrinkRecipe::where('is_active', true)
                ->whereHas('ingredients', fn ($q) => $q->where('spirit_id', $spirit->id))
                ->with(['ingredients.spirit', 'media', 'occasions' => fn ($q) => $q->where('is_active', true)])
                ->get();

            return new SpiritResource($spirit, $drinkRecipes);
        }

        return response()->json(['message' => 'Bebida não encontrada.'], 404);
    }

    public function random(): WineResource|SpiritResource|JsonResponse
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
            $candidates->push($wine);
        }
        if ($spirit) {
            $candidates->push($spirit);
        }

        if ($candidates->isEmpty()) {
            return response()->json(['message' => 'Nenhuma bebida cadastrada.'], 404);
        }

        $chosen = $candidates->random();

        return $this->show($chosen->barcode);
    }

    public function report(Request $request): JsonResponse
    {
        $data = $request->validate([
            'barcode' => 'required|string|max:100',
            'type'    => 'required|in:wine,spirit',
        ]);

        BeverageReport::create($data);

        return response()->json(['message' => 'Reportado com sucesso.']);
    }
}
