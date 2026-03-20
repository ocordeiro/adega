<?php

namespace App\Filament\Resources\Spirits\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class SpiritForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(3)->components([

            // ── COLUNA PRINCIPAL (2/3) ─────────────────────────────────────
            Grid::make(1)->columnSpan(2)->schema([

                Section::make('Identificação')->schema([
                    TextInput::make('name')
                        ->label('Nome do Destilado')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Grid::make(2)->schema([
                        Select::make('spirit_type_id')
                            ->label('Tipo de Destilado')
                            ->relationship('spiritType', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->createOptionForm([
                                TextInput::make('name')->label('Nome')->required()->maxLength(100),
                            ]),

                        TextInput::make('barcode')
                            ->label('Código de Barras')
                            ->nullable()
                            ->maxLength(100),
                    ]),
                ]),

                Section::make('Origem')->columns(2)->schema([
                    Select::make('producer_id')
                        ->label('Produtor / Fabricante')
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
                        ->nullable(),
                ]),

                Section::make('Detalhes')->schema([
                    Textarea::make('description')
                        ->label('Descrição')
                        ->nullable()
                        ->rows(4)
                        ->columnSpanFull(),

                    TextInput::make('alcohol_content')
                        ->label('Teor Alcoólico')
                        ->numeric()
                        ->step(0.1)
                        ->minValue(0)
                        ->maxValue(99)
                        ->suffix('%')
                        ->nullable(),
                ]),

            ]),

            // ── SIDEBAR (1/3) ──────────────────────────────────────────────
            Grid::make(1)->columnSpan(1)->schema([

                Section::make('Status')->schema([
                    Toggle::make('is_active')
                        ->label('Destilado ativo')
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
