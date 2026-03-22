<?php

namespace App\Filament\Widgets;

use App\Models\Country;
use Filament\Widgets\ChartWidget;

class SpiritsByCountryChart extends ChartWidget
{
    protected ?string $heading = 'Destilados por País (Top 10)';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = Country::withCount(['spirits' => fn ($q) => $q->where('is_active', true)])
            ->having('spirits_count', '>', 0)
            ->orderByDesc('spirits_count')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label'           => 'Destilados',
                    'data'            => $data->pluck('spirits_count')->toArray(),
                    'backgroundColor' => 'rgba(184, 124, 58, 0.6)',
                    'borderColor'     => 'rgba(184, 124, 58, 0.9)',
                    'borderWidth'     => 0,
                    'borderRadius'    => 4,
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
