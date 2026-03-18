<?php

namespace App\Filament\Resources\WineTypes;

use App\Filament\Resources\WineTypes\Pages\CreateWineType;
use App\Filament\Resources\WineTypes\Pages\EditWineType;
use App\Filament\Resources\WineTypes\Pages\ListWineTypes;
use App\Filament\Resources\WineTypes\Schemas\WineTypeForm;
use App\Filament\Resources\WineTypes\Tables\WineTypesTable;
use App\Models\WineType;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WineTypeResource extends Resource
{
    protected static ?string $model = WineType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static UnitEnum|string|null $navigationGroup = 'Catálogo';

    protected static ?string $navigationLabel = 'Tipos de Vinho';

    protected static ?string $modelLabel = 'Tipo de Vinho';

    protected static ?string $pluralModelLabel = 'Tipos de Vinho';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return WineTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WineTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListWineTypes::route('/'),
            'create' => CreateWineType::route('/create'),
            'edit'   => EditWineType::route('/{record}/edit'),
        ];
    }
}
