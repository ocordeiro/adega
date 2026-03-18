<?php

namespace App\Filament\Resources\FoodCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FoodCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nome da Categoria')->required()->maxLength(100)->columnSpanFull(),
        ]);
    }
}
