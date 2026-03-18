<?php

namespace App\Filament\Resources\Regions\Schemas;

use App\Models\Country;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class RegionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                Select::make('country_id')
                    ->label('País')
                    ->relationship('country', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')->label('Nome da Região')->required()->maxLength(150),
            ]),
        ]);
    }
}
