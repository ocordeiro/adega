<?php

namespace App\Filament\Resources\DrinkRecipes\Schemas;

use App\Models\Spirit;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class DrinkRecipeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('name')
                    ->label('Nome do Drink')
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
                ->rows(6)
                ->nullable()
                ->columnSpanFull(),

            Repeater::make('ingredients')
                ->label('Ingredientes')
                ->relationship()
                ->reorderable('sort_order')
                ->schema([
                    Grid::make(4)->schema([
                        Select::make('spirit_id')
                            ->label('Destilado')
                            ->options(fn () => Spirit::where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->nullable()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $spirit = Spirit::find($state);
                                    if ($spirit) {
                                        $set('name', $spirit->name);
                                    }
                                }
                            })
                            ->live(),

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
                ->defaultItems(1)
                ->addActionLabel('Adicionar ingrediente')
                ->columnSpanFull(),

            SpatieMediaLibraryFileUpload::make('photo')
                ->label('Foto do Drink')
                ->collection('photo')
                ->disk('s3')
                ->image()
                ->imageEditor()
                ->maxSize(5120)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->columnSpanFull(),

            Toggle::make('is_active')
                ->label('Ativo')
                ->default(true)
                ->columnSpanFull(),
        ]);
    }
}
