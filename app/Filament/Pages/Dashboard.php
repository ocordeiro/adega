<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Painel';
    protected static ?string $navigationLabel = 'Painel';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    public function getSubheading(): ?string
    {
        $hour = now()->hour;
        $greeting = match (true) {
            $hour < 12 => 'Bom dia',
            $hour < 18 => 'Boa tarde',
            default    => 'Boa noite',
        };

        $name = auth()->user()?->name ?? '';

        return "{$greeting}, {$name}! Aqui está o resumo da sua adega.";
    }
}
