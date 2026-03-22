<?php

namespace App\Filament\Widgets;

use App\Models\SpiritType;
use Filament\Widgets\ChartWidget;

class SpiritsByTypeChart extends ChartWidget
{
    protected ?string $heading = 'Destilados por Tipo';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = SpiritType::withCount(['spirits' => fn ($q) => $q->where('is_active', true)])
            ->having('spirits_count', '>', 0)
            ->get();

        return [
            'datasets' => [
                [
                    'label'           => 'Destilados',
                    'data'            => $data->pluck('spirits_count')->toArray(),
                    'backgroundColor' => ['#b45309','#d97706','#f59e0b','#fbbf24','#78350f','#92400e','#a16207','#854d0e'],
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
