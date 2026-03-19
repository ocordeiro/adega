<?php

namespace App\Filament\Resources\DrinkRecipes\Pages;

use App\Filament\Resources\DrinkRecipes\DrinkRecipeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDrinkRecipes extends ListRecords
{
    protected static string $resource = DrinkRecipeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
