<?php

namespace App\Filament\Widgets;

use App\Models\Wine;
use App\Models\WineType;
use Filament\Widgets\ChartWidget;

class WinesByTypeChart extends ChartWidget
{
    protected ?string $heading = 'Vinhos por Tipo';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 2;

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
                    'backgroundColor' => ['#9e2d4c','#c44d72','#e07898','#8b3a6a','#b85c50','#d4856a','#7b3558','#c86080'],
                    'borderWidth'     => 2,
                    'borderColor'     => '#ffffff',
                    'hoverOffset'     => 6,
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
