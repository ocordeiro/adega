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
                    'backgroundColor' => ['#8b5e2a','#b87c3a','#d49a50','#9a6e3c','#c48848','#6b4420','#e0a858','#f0c070'],
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
