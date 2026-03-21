<?php

namespace App\Filament\Resources\Themes\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ThemeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                ColorPicker::make('color_primary')
                    ->label('Cor Primária')
                    ->required(),

                ColorPicker::make('color_secondary')
                    ->label('Cor Secundária')
                    ->required(),

                ColorPicker::make('color_background')
                    ->label('Cor de Fundo')
                    ->required(),

                ColorPicker::make('color_text')
                    ->label('Cor do Texto')
                    ->required(),
            ]),
        ]);
    }
}
