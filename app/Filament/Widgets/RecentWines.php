<?php

namespace App\Filament\Widgets;

use App\Models\Wine;
use Filament\SpatieLaravelMediaLibraryPlugin\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentWines extends TableWidget
{
    protected static ?string $heading = 'Vinhos Recentes';

    protected int|string|array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Wine::with(['wineType', 'country'])->latest()->limit(10))
            ->columns([
                SpatieMediaLibraryImageColumn::make('photos')
                    ->label('')
                    ->collection('photos')
                    ->conversion('thumb')
                    ->width(40)
                    ->height(40),
                TextColumn::make('name')->label('Nome')->limit(30),
                TextColumn::make('wineType.name')->label('Tipo')->badge(),
                TextColumn::make('vintage')->label('Safra'),
                TextColumn::make('sale_price')->label('Preço')->money('BRL'),
                TextColumn::make('stock_quantity')->label('Estoque')->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'danger',
                        $state <= 5  => 'warning',
                        default      => 'success',
                    }),
            ])
            ->paginated(false);
    }
}
