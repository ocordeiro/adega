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
                    'backgroundColor' => ['#7b1f3a','#a83256','#c2185b','#d4a373','#8b5e3c','#6d4c41','#b71c1c','#880e4f'],
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
