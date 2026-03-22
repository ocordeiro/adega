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
                    'backgroundColor' => ['#c4506a','#e07898','#9e2d4c','#d4456e','#f09ab8','#7b1f3a','#e85c82','#b03060'],
                    'borderWidth'     => 0,
                    'hoverOffset'     => 10,
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'cutout'     => '68%',
            'plugins'    => [
                'legend' => [
                    'position' => 'bottom',
                    'labels'   => [
                        'padding'         => 18,
                        'usePointStyle'   => true,
                        'pointStyleWidth' => 10,
                        'color'           => '#94a3b8',
                        'font'            => ['size' => 12],
                    ],
                ],
                'tooltip' => [
                    'padding'      => 12,
                    'cornerRadius' => 8,
                ],
            ],
            'animation' => ['animateScale' => true],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
