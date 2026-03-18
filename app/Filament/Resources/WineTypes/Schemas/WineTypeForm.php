<?php

namespace App\Filament\Resources\WineTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WineTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(100)
                    ->columnSpanFull(),
            ]);
    }
}
