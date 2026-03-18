<?php

namespace App\Filament\Resources\Countries\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class CountryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('name')->label('Nome')->required()->maxLength(100),
                TextInput::make('code')->label('Código ISO')->maxLength(2)->nullable()
                    ->helperText('Ex: BR, AR, FR')->extraInputAttributes(['style' => 'text-transform:uppercase']),
            ]),
        ]);
    }
}
