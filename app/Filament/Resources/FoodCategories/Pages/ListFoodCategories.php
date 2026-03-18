<?php

namespace App\Filament\Resources\FoodCategories\Pages;

use App\Filament\Resources\FoodCategories\FoodCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFoodCategories extends ListRecords
{
    protected static string $resource = FoodCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
