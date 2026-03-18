<?php

namespace App\Filament\Resources\GrapeVarieties\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GrapeVarietyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nome da Uva')->required()->maxLength(150)->columnSpanFull(),
        ]);
    }
}
