<?php

namespace App\Filament\Resources\Recipes\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class RecipeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('name')
                    ->label('Nome da Receita')
                    ->required()
                    ->maxLength(200),

                Grid::make(2)->schema([
                    TextInput::make('prep_time')
                        ->label('Tempo de Preparo (min)')
                        ->numeric()
                        ->minValue(1)
                        ->nullable(),

                    Select::make('difficulty')
                        ->label('Dificuldade')
                        ->options([
                            'fácil'   => 'Fácil',
                            'médio'   => 'Médio',
                            'difícil' => 'Difícil',
                        ])
                        ->default('fácil')
                        ->required(),
                ]),
            ]),

            Textarea::make('description')
                ->label('Descrição')
                ->rows(2)
                ->nullable()
                ->columnSpanFull(),

            Textarea::make('instructions')
                ->label('Modo de Preparo')
                ->rows(8)
                ->nullable()
                ->columnSpanFull(),

            Repeater::make('ingredients')
                ->label('Ingredientes')
                ->relationship()
                ->reorderable('sort_order')
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make('name')
                            ->label('Ingrediente')
                            ->required()
                            ->maxLength(200),

                        TextInput::make('quantity')
                            ->label('Quantidade')
                            ->nullable()
                            ->maxLength(50),

                        TextInput::make('unit')
                            ->label('Unidade')
                            ->nullable()
                            ->maxLength(50)
                            ->placeholder('ml, unidade, colher...'),
                    ]),
                ])
                ->defaultItems(0)
                ->addActionLabel('Adicionar ingrediente')
                ->columnSpanFull(),

            SpatieMediaLibraryFileUpload::make('photo')
                ->label('Foto da Receita')
                ->collection('photo')
                ->disk('s3')
                ->image()
                ->imageEditor()
                ->maxSize(5120)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->columnSpanFull(),

            Select::make('wines')
                ->label('Vinhos que combinam')
                ->relationship('wines', 'name')
                ->multiple()
                ->searchable()
                ->preload()
                ->nullable()
                ->columnSpanFull(),

            Toggle::make('is_active')
                ->label('Ativo')
                ->default(true)
                ->columnSpanFull(),
        ]);
    }
}
