<?php

namespace App\Filament\Resources\Wines;

use App\Filament\Resources\Wines\Pages\CreateWine;
use App\Filament\Resources\Wines\Pages\EditWine;
use App\Filament\Resources\Wines\Pages\ListWines;
use App\Filament\Resources\Wines\Schemas\WineForm;
use App\Filament\Resources\Wines\Tables\WinesTable;
use App\Models\Wine;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WineResource extends Resource
{
    protected static ?string $model = Wine::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;
    protected static UnitEnum|string|null $navigationGroup = 'Catálogo';
    protected static ?string $navigationLabel = 'Vinhos';
    protected static ?string $modelLabel = 'Vinho';
    protected static ?string $pluralModelLabel = 'Vinhos';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema { return WineForm::configure($schema); }
    public static function table(Table $table): Table { return WinesTable::configure($table); }
    public static function getRelations(): array { return []; }
    public static function getPages(): array
    {
        return [
            'index'  => ListWines::route('/'),
            'create' => CreateWine::route('/create'),
            'edit'   => EditWine::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
