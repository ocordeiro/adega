<?php

namespace App\Filament\Resources\Recipes;

use App\Filament\Resources\Recipes\Pages\CreateRecipe;
use App\Filament\Resources\Recipes\Pages\EditRecipe;
use App\Filament\Resources\Recipes\Pages\ListRecipes;
use App\Filament\Resources\Recipes\Schemas\RecipeForm;
use App\Filament\Resources\Recipes\Tables\RecipesTable;
use App\Models\Recipe;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RecipeResource extends Resource
{
    protected static ?string $model = Recipe::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;
    protected static UnitEnum|string|null $navigationGroup = 'Harmonização';
    protected static ?string $navigationLabel = 'Receitas';
    protected static ?string $modelLabel = 'Receita';
    protected static ?string $pluralModelLabel = 'Receitas';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema { return RecipeForm::configure($schema); }
    public static function table(Table $table): Table { return RecipesTable::configure($table); }
    public static function getRelations(): array { return []; }
    public static function getPages(): array
    {
        return [
            'index'  => ListRecipes::route('/'),
            'create' => CreateRecipe::route('/create'),
            'edit'   => EditRecipe::route('/{record}/edit'),
        ];
    }
}
