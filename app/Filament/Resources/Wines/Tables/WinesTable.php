<?php

namespace App\Filament\Resources\Wines\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class WinesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('photos')
                    ->label('Foto')
                    ->collection('photos')
                    ->conversion('thumb')
                    ->width(60)
                    ->height(60),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('wineType.name')
                    ->label('Tipo')
                    ->badge()
                    ->sortable(),
                TextColumn::make('vintage')
                    ->label('Safra')
                    ->sortable(),
                TextColumn::make('producer.name')
                    ->label('Produtor')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('country.name')
                    ->label('País')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('sale_price')
                    ->label('Preço Venda')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('stock_quantity')
                    ->label('Estoque')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0    => 'danger',
                        $state <= 5     => 'warning',
                        default         => 'success',
                    }),
                TextColumn::make('rating')
                    ->label('Pontuação')
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('wine_type_id')
                    ->label('Tipo de Vinho')
                    ->relationship('wineType', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('country_id')
                    ->label('País')
                    ->relationship('country', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('producer_id')
                    ->label('Produtor')
                    ->relationship('producer', 'name')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('is_active')->label('Ativo'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
