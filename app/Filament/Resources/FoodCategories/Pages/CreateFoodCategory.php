<?php

namespace App\Filament\Resources\FoodCategories\Pages;

use App\Filament\Resources\FoodCategories\FoodCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFoodCategory extends CreateRecord
{
    protected static string $resource = FoodCategoryResource::class;
}
