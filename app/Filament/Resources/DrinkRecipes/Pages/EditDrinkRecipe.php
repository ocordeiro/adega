<?php

namespace App\Filament\Resources\DrinkRecipes\Pages;

use App\Filament\Resources\DrinkRecipes\DrinkRecipeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDrinkRecipe extends EditRecord
{
    protected static string $resource = DrinkRecipeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
