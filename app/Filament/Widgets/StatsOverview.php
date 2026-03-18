<?php

namespace App\Filament\Widgets;

use App\Models\Producer;
use App\Models\Wine;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalWines = Wine::where('is_active', true)->count();
        $totalStock = Wine::where('is_active', true)->sum('stock_quantity');
        $stockValue = Wine::where('is_active', true)
            ->whereNotNull('sale_price')
            ->selectRaw('SUM(stock_quantity * sale_price) as total')
            ->value('total') ?? 0;
        $lowStock  = Wine::where('is_active', true)->where('stock_quantity', '<=', 5)->count();
        $producers = Producer::count();

        return [
            Stat::make('Vinhos Ativos', $totalWines)
                ->description('Total de vinhos cadastrados')
                ->icon('heroicon-o-beaker')
                ->color('primary'),
            Stat::make('Valor em Estoque', 'R$ ' . number_format($stockValue, 2, ',', '.'))
                ->description("{$totalStock} unidades em estoque")
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),
            Stat::make('Estoque Baixo', $lowStock)
                ->description('Vinhos com 5 ou menos unidades')
                ->icon('heroicon-o-exclamation-triangle')
                ->color($lowStock > 0 ? 'warning' : 'success'),
            Stat::make('Produtores / Vinícolas', $producers)
                ->description('Produtores cadastrados')
                ->icon('heroicon-o-building-office')
                ->color('info'),
        ];
    }
}
