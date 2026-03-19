<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrinkRecipeIngredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'drink_recipe_id',
        'spirit_id',
        'name',
        'quantity',
        'unit',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function drinkRecipe(): BelongsTo
    {
        return $this->belongsTo(DrinkRecipe::class);
    }

    public function spirit(): BelongsTo
    {
        return $this->belongsTo(Spirit::class);
    }
}
