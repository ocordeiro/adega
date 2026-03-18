<?php

namespace App\Filament\Resources\Producers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ProducerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nome do Produtor / Vinícola')->required()->maxLength(200)->columnSpanFull(),
            Grid::make(2)->schema([
                Select::make('country_id')->label('País')->relationship('country', 'name')->searchable()->preload()->nullable(),
                TextInput::make('website')->label('Website')->url()->nullable()->maxLength(255)->placeholder('https://'),
            ]),
        ]);
    }
}
