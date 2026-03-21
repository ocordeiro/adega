<?php

namespace App\Filament\Resources\Ads\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AdsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->width('4rem'),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('media_type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'video' ? 'Vídeo' : 'Imagem')
                    ->color(fn (string $state) => $state === 'video' ? 'info' : 'success'),
                TextColumn::make('display_duration')
                    ->label('Duração')
                    ->suffix('s')
                    ->placeholder('—'),
                TextColumn::make('media_count')
                    ->label('Mídia')
                    ->getStateUsing(fn ($record) => ($record->getFirstMedia('video') || $record->getFirstMedia('image')) ? '✓' : '—')
                    ->badge()
                    ->color(fn ($state) => $state === '✓' ? 'success' : 'gray'),
                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Atualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                TernaryFilter::make('is_active')->label('Ativo'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
