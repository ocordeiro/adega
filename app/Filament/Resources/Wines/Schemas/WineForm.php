<?php

namespace App\Filament\Resources\Wines\Schemas;

use App\Models\Region;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class WineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informações Básicas')
                ->schema([
                    TextInput::make('name')
                        ->label('Nome do Vinho')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Grid::make(3)->schema([
                        Select::make('wine_type_id')
                            ->label('Tipo de Vinho')
                            ->relationship('wineType', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        TextInput::make('vintage')
                            ->label('Safra (Ano)')
                            ->numeric()
                            ->minValue(1800)
                            ->maxValue(date('Y') + 1)
                            ->nullable(),
                        TextInput::make('barcode')
                            ->label('Código de Barras')
                            ->nullable()
                            ->maxLength(100),
                    ]),
                    Grid::make(2)->schema([
                        Select::make('producer_id')
                            ->label('Produtor / Vinícola')
                            ->relationship('producer', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->createOptionForm([
                                TextInput::make('name')->label('Nome')->required()->maxLength(200),
                            ]),
                        Select::make('country_id')
                            ->label('País')
                            ->relationship('country', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('region_id', null)),
                    ]),
                    Grid::make(2)->schema([
                        Select::make('region_id')
                            ->label('Região')
                            ->nullable()
                            ->searchable()
                            ->options(fn (Get $get) => Region::where('country_id', $get('country_id'))->pluck('name', 'id'))
                            ->disabled(fn (Get $get) => blank($get('country_id'))),
                        Select::make('grapeVarieties')
                            ->label('Uvas / Variedades')
                            ->relationship('grapeVarieties', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ]),
                ]),

            Section::make('Características')
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make('alcohol_content')
                            ->label('Teor Alcoólico (%)')
                            ->numeric()
                            ->step(0.1)
                            ->minValue(0)
                            ->maxValue(25)
                            ->suffix('%')
                            ->nullable(),
                        TextInput::make('serving_temp_min')
                            ->label('Temp. Mínima (°C)')
                            ->numeric()
                            ->minValue(-5)
                            ->maxValue(30)
                            ->suffix('°C')
                            ->nullable(),
                        TextInput::make('serving_temp_max')
                            ->label('Temp. Máxima (°C)')
                            ->numeric()
                            ->minValue(-5)
                            ->maxValue(30)
                            ->suffix('°C')
                            ->nullable(),
                    ]),
                    Textarea::make('description')
                        ->label('Descrição / Notas de Degustação')
                        ->nullable()
                        ->rows(5)
                        ->columnSpanFull(),
                    TextInput::make('rating')
                        ->label('Pontuação (1-100)')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(100)
                        ->nullable(),
                ]),

            Section::make('Estoque & Preços')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('cost_price')
                            ->label('Preço de Custo (R$)')
                            ->numeric()
                            ->prefix('R$')
                            ->step(0.01)
                            ->nullable(),
                        TextInput::make('sale_price')
                            ->label('Preço de Venda (R$)')
                            ->numeric()
                            ->prefix('R$')
                            ->step(0.01)
                            ->nullable(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('stock_quantity')
                            ->label('Quantidade em Estoque')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->required(),
                        Select::make('stock_unit')
                            ->label('Unidade')
                            ->options([
                                'bottle'      => 'Garrafa (750ml)',
                                'magnum'      => 'Magnum (1,5L)',
                                'half_bottle' => 'Meia Garrafa (375ml)',
                            ])
                            ->default('bottle')
                            ->required(),
                    ]),
                ]),

            Section::make('Fotos')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('photos')
                        ->label('Fotos do Vinho')
                        ->collection('photos')
                        ->disk('s3')
                        ->multiple()
                        ->reorderable()
                        ->image()
                        ->imageEditor()
                        ->maxFiles(10)
                        ->maxSize(10240)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->columnSpanFull(),
                ]),

            Section::make('Harmonização com Alimentos')
                ->schema([
                    Select::make('foods')
                        ->label('Alimentos que Harmonizam')
                        ->relationship('foods', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->columnSpanFull(),
                ]),

            Section::make('Configurações')
                ->schema([
                    Toggle::make('is_active')
                        ->label('Vinho Ativo')
                        ->default(true),
                ]),
        ]);
    }
}
