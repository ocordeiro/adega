<?php

namespace App\Filament\Resources\FoodCategories\Pages;

use App\Filament\Resources\FoodCategories\FoodCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFoodCategory extends EditRecord
{
    protected static string $resource = FoodCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
