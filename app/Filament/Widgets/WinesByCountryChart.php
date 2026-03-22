<?php

namespace App\Filament\Widgets;

use App\Models\Country;
use Filament\Widgets\ChartWidget;

class WinesByCountryChart extends ChartWidget
{
    protected ?string $heading = 'Vinhos por País (Top 10)';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = Country::withCount(['wines' => fn ($q) => $q->where('is_active', true)])
            ->having('wines_count', '>', 0)
            ->orderByDesc('wines_count')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label'           => 'Vinhos',
                    'data'            => $data->pluck('wines_count')->toArray(),
                    'backgroundColor' => 'rgba(196, 80, 106, 0.75)',
                    'borderColor'     => 'rgba(196, 80, 106, 1)',
                    'borderWidth'     => 0,
                    'borderRadius'    => 6,
                    'borderSkipped'   => false,
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
                'tooltip' => ['padding' => 12, 'cornerRadius' => 8],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => [
                        'color'     => '#64748b',
                        'precision' => 0,
                        'stepSize'  => 1,
                    ],
                    'grid'   => ['color' => 'rgba(148,163,184,0.08)'],
                    'border' => ['display' => false],
                ],
                'x' => [
                    'ticks'  => ['color' => '#64748b'],
                    'grid'   => ['display' => false],
                    'border' => ['display' => false],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
