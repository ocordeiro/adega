<?php

namespace App\Filament\Widgets;

use App\Models\Country;
use Filament\Widgets\ChartWidget;

class WinesByCountryChart extends ChartWidget
{
    protected ?string $heading = 'Vinhos por País (Top 10)';

    protected int|string|array $columnSpan = 1;

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
                    'backgroundColor' => 'rgba(158, 45, 76, 0.85)',
                    'borderColor'     => 'rgb(123, 31, 58)',
                    'borderWidth'     => 1,
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
