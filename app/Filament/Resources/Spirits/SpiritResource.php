<?php

namespace App\Filament\Resources\Spirits;

use App\Filament\Resources\Spirits\Pages\CreateSpirit;
use App\Filament\Resources\Spirits\Pages\EditSpirit;
use App\Filament\Resources\Spirits\Pages\ListSpirits;
use App\Filament\Resources\Spirits\Schemas\SpiritForm;
use App\Filament\Resources\Spirits\Tables\SpiritsTable;
use App\Models\Spirit;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpiritResource extends Resource
{
    protected static ?string $model = Spirit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFire;

    protected static UnitEnum|string|null $navigationGroup = 'Catálogo';

    protected static ?string $navigationLabel = 'Destilados';

    protected static ?string $modelLabel = 'Destilado';

    protected static ?string $pluralModelLabel = 'Destilados';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema { return SpiritForm::configure($schema); }
    public static function table(Table $table): Table { return SpiritsTable::configure($table); }
    public static function getRelations(): array { return []; }
    public static function getPages(): array
    {
        return [
            'index'  => ListSpirits::route('/'),
            'create' => CreateSpirit::route('/create'),
            'edit'   => EditSpirit::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
