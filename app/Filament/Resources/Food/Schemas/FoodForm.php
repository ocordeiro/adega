<?php

namespace App\Filament\Resources\Food\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\SpatieLaravelMediaLibraryPlugin\Forms\Components\SpatieMediaLibraryFileUpload;

class FoodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('name')->label('Nome')->required()->maxLength(200),
                Select::make('food_category_id')
                    ->label('Categoria')
                    ->relationship('foodCategory', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->createOptionForm([
                        TextInput::make('name')->label('Nome')->required()->maxLength(100),
                    ]),
            ]),
            Textarea::make('description')->label('Descrição')->nullable()->rows(3)->columnSpanFull(),
            SpatieMediaLibraryFileUpload::make('image')
                ->label('Imagem')
                ->collection('image')
                ->disk('s3')
                ->image()
                ->imageEditor()
                ->maxSize(5120)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->columnSpanFull(),
        ]);
    }
}
