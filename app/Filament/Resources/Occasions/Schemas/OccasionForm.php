<?php

namespace App\Filament\Resources\Occasions\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class OccasionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)->schema([
                TextInput::make('icon')
                    ->label('Ícone (emoji)')
                    ->default('🍷')
                    ->maxLength(10)
                    ->required(),

                TextInput::make('name')
                    ->label('Nome da Ocasião')
                    ->required()
                    ->maxLength(100)
                    ->columnSpan(2),
            ]),

            Textarea::make('description')
                ->label('Descrição')
                ->nullable()
                ->rows(2)
                ->columnSpanFull(),

            Grid::make(2)->schema([
                TextInput::make('sort_order')
                    ->label('Ordem de exibição')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_active')
                    ->label('Ativa')
                    ->default(true)
                    ->inline(false),
            ]),
        ]);
    }
}
