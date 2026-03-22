<?php

namespace App\Filament\Widgets;

use App\Models\SpiritType;
use Filament\Widgets\ChartWidget;

class SpiritsByTypeChart extends ChartWidget
{
    protected ?string $heading = 'Destilados por Tipo';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 2;

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
                    'backgroundColor' => ['#d4986a','#f0b870','#b87840','#e8a850','#9a6030','#f5cc88','#c88848','#a06838'],
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
            'cutout'  => '68%',
            'plugins' => [
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
