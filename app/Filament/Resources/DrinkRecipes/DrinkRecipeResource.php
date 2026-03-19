<?php

namespace App\Filament\Resources\DrinkRecipes;

use App\Filament\Resources\DrinkRecipes\Pages\CreateDrinkRecipe;
use App\Filament\Resources\DrinkRecipes\Pages\EditDrinkRecipe;
use App\Filament\Resources\DrinkRecipes\Pages\ListDrinkRecipes;
use App\Filament\Resources\DrinkRecipes\Schemas\DrinkRecipeForm;
use App\Filament\Resources\DrinkRecipes\Tables\DrinkRecipesTable;
use App\Models\DrinkRecipe;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DrinkRecipeResource extends Resource
{
    protected static ?string $model = DrinkRecipe::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;
    protected static UnitEnum|string|null $navigationGroup = 'Harmonização';
    protected static ?string $navigationLabel = 'Drinks';
    protected static ?string $modelLabel = 'Drink';
    protected static ?string $pluralModelLabel = 'Drinks';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema { return DrinkRecipeForm::configure($schema); }
    public static function table(Table $table): Table { return DrinkRecipesTable::configure($table); }
    public static function getRelations(): array { return []; }
    public static function getPages(): array
    {
        return [
            'index'  => ListDrinkRecipes::route('/'),
            'create' => CreateDrinkRecipe::route('/create'),
            'edit'   => EditDrinkRecipe::route('/{record}/edit'),
        ];
    }
}
