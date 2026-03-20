<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Occasion extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon', 'description', 'is_active', 'sort_order'];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function foods(): BelongsToMany
    {
        return $this->belongsToMany(Food::class, 'food_occasion');
    }

    public function drinkRecipes(): BelongsToMany
    {
        return $this->belongsToMany(DrinkRecipe::class, 'drink_recipe_occasion');
    }
}
