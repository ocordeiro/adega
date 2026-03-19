<?php

namespace App\Filament\Widgets;

use App\Models\Country;
use App\Models\Food;
use App\Models\Producer;
use App\Models\Recipe;
use App\Models\Wine;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Vinhos Ativos', Wine::where('is_active', true)->count())
                ->description('Total de vinhos cadastrados')
                ->icon('heroicon-o-beaker')
                ->color('primary'),
            Stat::make('Produtores / Vinícolas', Producer::count())
                ->description('Produtores cadastrados')
                ->icon('heroicon-o-building-office')
                ->color('info'),
            Stat::make('Alimentos', Food::count())
                ->description('Ingredientes de harmonização')
                ->icon('heroicon-o-cake')
                ->color('success'),
            Stat::make('Receitas', Recipe::where('is_active', true)->count())
                ->description('Receitas ativas')
                ->icon('heroicon-o-book-open')
                ->color('warning'),
        ];
    }
}
