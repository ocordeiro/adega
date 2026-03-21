<?php

namespace App\Filament\Resources\Ads\Schemas;

use Filament\Forms\Components\Select;
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

                Select::make('media_type')
                    ->label('Tipo de Mídia')
                    ->options([
                        'video' => 'Vídeo',
                        'image' => 'Imagem',
                    ])
                    ->default('video')
                    ->required()
                    ->live(),
            ]),

            SpatieMediaLibraryFileUpload::make('video')
                ->label('Vídeo do Anúncio')
                ->collection('video')
                ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                ->maxSize(512000)
                ->columnSpanFull()
                ->visible(fn ($get) => $get('media_type') === 'video'),

            SpatieMediaLibraryFileUpload::make('image')
                ->label('Imagem do Anúncio')
                ->collection('image')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                ->maxSize(10240)
                ->columnSpanFull()
                ->visible(fn ($get) => $get('media_type') === 'image'),

            TextInput::make('display_duration')
                ->label('Duração de exibição (segundos)')
                ->numeric()
                ->minValue(1)
                ->maxValue(300)
                ->default(10)
                ->helperText('Tempo em segundos que a imagem ficará visível')
                ->visible(fn ($get) => $get('media_type') === 'image')
                ->columnSpanFull(),

            Toggle::make('is_active')
                ->label('Ativo')
                ->default(true)
                ->columnSpanFull(),
        ]);
    }
}
