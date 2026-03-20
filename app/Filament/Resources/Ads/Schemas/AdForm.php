<?php

namespace App\Filament\Resources\Ads\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class AdForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(200),

                TextInput::make('sort_order')
                    ->label('Ordem')
                    ->numeric()
                    ->default(0)
                    ->helperText('Menor número aparece primeiro'),
            ]),

            SpatieMediaLibraryFileUpload::make('video')
                ->label('Vídeo do Anúncio')
                ->collection('video')
                ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                ->maxSize(512000)
                ->columnSpanFull(),

            Toggle::make('is_active')
                ->label('Ativo')
                ->default(true)
                ->columnSpanFull(),
        ]);
    }
}
