<?php

namespace App\Filament\Widgets;

use App\Models\WineType;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class StockValueChart extends ChartWidget
{
    protected ?string $heading = 'Valor em Estoque por Tipo';

    protected int|string|array $columnSpan = 2;

    protected function getData(): array
    {
        $data = WineType::select('wine_types.name')
            ->selectRaw('SUM(wines.stock_quantity * wines.sale_price) as total_value')
            ->join('wines', 'wine_types.id', '=', 'wines.wine_type_id')
            ->where('wines.is_active', true)
            ->whereNull('wines.deleted_at')
            ->whereNotNull('wines.sale_price')
            ->groupBy('wine_types.id', 'wine_types.name')
            ->having('total_value', '>', 0)
            ->orderByDesc('total_value')
            ->get();

        return [
            'datasets' => [
                [
                    'label'           => 'Valor (R$)',
                    'data'            => $data->pluck('total_value')->map(fn ($v) => round($v, 2))->toArray(),
                    'backgroundColor' => ['#7c3aed','#dc2626','#ea580c','#d97706','#16a34a','#0891b2','#db2777','#6d28d9'],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
