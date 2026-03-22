<?php

namespace App\Filament\Resources\Wines\Schemas;

use App\Models\Region;
use App\Models\Wine;
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
        return $schema->columns(3)->components([

            // ── COLUNA PRINCIPAL (2/3) ─────────────────────────────────────
            Grid::make(1)->columnSpan(2)->schema([

                Section::make('Identificação')->schema([
                    TextInput::make('name')
                        ->label('Nome do Vinho')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Grid::make(4)->schema([
                        Select::make('wine_type_id')
                            ->label('Tipo de Vinho')
                            ->relationship('wineType', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Select::make('classification')
                            ->label('Classificação')
                            ->options(Wine::CLASSIFICATIONS)
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
                            ->maxLength(100)
                            ->default(fn () => request()->query('barcode')),
                    ]),
                ]),

                Section::make('Origem')->columns(2)->schema([
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

                    Select::make('region_id')
                        ->label('Região')
                        ->nullable()
                        ->searchable()
                        ->options(fn (Get $get) => Region::where('country_id', $get('country_id'))->pluck('name', 'id'))
                        ->disabled(fn (Get $get) => blank($get('country_id'))),

                    Select::make('grapeVarieties')
                        ->label('Uvas / Castas')
                        ->relationship('grapeVarieties', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->nullable(),
                ]),

                Section::make('Características')->schema([
                    Textarea::make('description')
                        ->label('Notas de Degustação')
                        ->nullable()
                        ->rows(4)
                        ->columnSpanFull(),

                    Grid::make(3)->schema([
                        TextInput::make('alcohol_content')
                            ->label('Teor Alcoólico')
                            ->numeric()
                            ->step(0.1)
                            ->minValue(0)
                            ->maxValue(25)
                            ->suffix('%')
                            ->nullable(),

                        TextInput::make('serving_temp_min')
                            ->label('Temp. Mínima')
                            ->numeric()
                            ->minValue(-5)
                            ->maxValue(30)
                            ->suffix('°C')
                            ->nullable(),

                        TextInput::make('serving_temp_max')
                            ->label('Temp. Máxima')
                            ->numeric()
                            ->minValue(-5)
                            ->maxValue(30)
                            ->suffix('°C')
                            ->nullable(),
                    ]),

                    TextInput::make('rating')
                        ->label('Pontuação Parker (1–100)')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(100)
                        ->nullable(),
                ]),

                Section::make('Harmonização')->schema([
                    Select::make('foods')
                        ->label('Alimentos que harmonizam')
                        ->relationship('foods', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->columnSpanFull(),
                ]),

            ]),

            // ── SIDEBAR (1/3) ──────────────────────────────────────────────
            Grid::make(1)->columnSpan(1)->schema([

                Section::make('Status')->schema([
                    Toggle::make('is_active')
                        ->label('Vinho ativo')
                        ->default(true)
                        ->inline(false),
                ]),

                Section::make('Fotos')->schema([
                    SpatieMediaLibraryFileUpload::make('photos')
                        ->label(false)
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

            ]),

        ]);
    }
}
