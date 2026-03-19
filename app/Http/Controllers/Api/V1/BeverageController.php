<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\SpiritResource;
use App\Http\Resources\V1\WineResource;
use App\Models\DrinkRecipe;
use App\Models\Spirit;
use App\Models\Wine;
use Illuminate\Http\JsonResponse;

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
                'occasions' => fn ($q) => $q->where('is_active', true),
                'recipes' => fn ($q) => $q->where('is_active', true),
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
                ->with(['ingredients.spirit', 'media'])
                ->get();

            return new SpiritResource($spirit, $drinkRecipes);
        }

        return response()->json(['message' => 'Bebida não encontrada.'], 404);
    }
}
