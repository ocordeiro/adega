<?php

namespace App\Filament\Resources\GrapeVarieties;

use App\Filament\Resources\GrapeVarieties\Pages\CreateGrapeVariety;
use App\Filament\Resources\GrapeVarieties\Pages\EditGrapeVariety;
use App\Filament\Resources\GrapeVarieties\Pages\ListGrapeVarieties;
use App\Filament\Resources\GrapeVarieties\Schemas\GrapeVarietyForm;
use App\Filament\Resources\GrapeVarieties\Tables\GrapeVarietiesTable;
use App\Models\GrapeVariety;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GrapeVarietyResource extends Resource
{
    protected static ?string $model = GrapeVariety::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSun;
    protected static UnitEnum|string|null $navigationGroup = 'Catálogo';
    protected static ?string $navigationLabel = 'Uvas / Variedades';
    protected static ?string $modelLabel = 'Uva / Variedade';
    protected static ?string $pluralModelLabel = 'Uvas / Variedades';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema { return GrapeVarietyForm::configure($schema); }
    public static function table(Table $table): Table { return GrapeVarietiesTable::configure($table); }
    public static function getRelations(): array { return []; }
    public static function getPages(): array
    {
        return [
            'index'  => ListGrapeVarieties::route('/'),
            'create' => CreateGrapeVariety::route('/create'),
            'edit'   => EditGrapeVariety::route('/{record}/edit'),
        ];
    }
}
