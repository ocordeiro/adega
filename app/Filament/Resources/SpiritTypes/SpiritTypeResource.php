<?php

namespace App\Filament\Resources\SpiritTypes;

use App\Filament\Resources\SpiritTypes\Pages\CreateSpiritType;
use App\Filament\Resources\SpiritTypes\Pages\EditSpiritType;
use App\Filament\Resources\SpiritTypes\Pages\ListSpiritTypes;
use App\Filament\Resources\SpiritTypes\Schemas\SpiritTypeForm;
use App\Filament\Resources\SpiritTypes\Tables\SpiritTypesTable;
use App\Models\SpiritType;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SpiritTypeResource extends Resource
{
    protected static ?string $model = SpiritType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static UnitEnum|string|null $navigationGroup = 'Catálogo';

    protected static ?string $navigationLabel = 'Tipos de Destilado';

    protected static ?string $modelLabel = 'Tipo de Destilado';

    protected static ?string $pluralModelLabel = 'Tipos de Destilado';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return SpiritTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SpiritTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSpiritTypes::route('/'),
            'create' => CreateSpiritType::route('/create'),
            'edit'   => EditSpiritType::route('/{record}/edit'),
        ];
    }
}
