<?php

namespace App\Filament\Widgets;

use App\Models\Wine;
use App\Models\WineType;
use Filament\Widgets\ChartWidget;

class WinesByTypeChart extends ChartWidget
{
    protected ?string $heading = 'Vinhos por Tipo';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $data = WineType::withCount(['wines' => fn ($q) => $q->where('is_active', true)])
            ->having('wines_count', '>', 0)
            ->get();

        return [
            'datasets' => [
                [
                    'label'           => 'Vinhos',
                    'data'            => $data->pluck('wines_count')->toArray(),
                    'backgroundColor' => ['#7c3aed','#dc2626','#ea580c','#d97706','#16a34a','#0891b2','#db2777','#6d28d9'],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
