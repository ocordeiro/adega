<?php

namespace App\Filament\Widgets;

use App\Models\Wine;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LowStockWines extends TableWidget
{
    protected static ?string $heading = '⚠️ Estoque Baixo (≤ 5 unidades)';

    protected int|string|array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Wine::with(['wineType'])
                ->where('is_active', true)
                ->where('stock_quantity', '<=', 5)
                ->orderBy('stock_quantity'))
            ->columns([
                TextColumn::make('name')->label('Vinho')->limit(35)->searchable(),
                TextColumn::make('wineType.name')->label('Tipo')->badge(),
                TextColumn::make('vintage')->label('Safra'),
                TextColumn::make('sale_price')->label('Preço Venda')->money('BRL'),
                TextColumn::make('stock_quantity')->label('Estoque')
                    ->badge()
                    ->color(fn (int $state): string => $state === 0 ? 'danger' : 'warning'),
            ])
            ->paginated(false);
    }
}
