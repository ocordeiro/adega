<?php

namespace App\Filament\Resources\BeverageReports;

use App\Filament\Resources\BeverageReports\Pages\ListBeverageReports;
use App\Filament\Resources\BeverageReports\Tables\BeverageReportsTable;
use App\Models\BeverageReport;
use App\Models\Spirit;
use App\Models\Wine;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BeverageReportResource extends Resource
{
    protected static ?string $model = BeverageReport::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBellAlert;
    protected static UnitEnum|string|null $navigationGroup = 'Catálogo';
    protected static ?string $navigationLabel = 'Reportados';
    protected static ?string $modelLabel = 'Bebida Reportada';
    protected static ?string $pluralModelLabel = 'Bebidas Reportadas';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        $count = BeverageReport::whereNotIn('barcode', Wine::pluck('barcode'))
            ->whereNotIn('barcode', Spirit::pluck('barcode'))
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public static function form(Schema $schema): Schema { return $schema; }
    public static function table(Table $table): Table { return BeverageReportsTable::configure($table); }
    public static function getRelations(): array { return []; }
    public static function getPages(): array
    {
        return [
            'index' => ListBeverageReports::route('/'),
        ];
    }
}
