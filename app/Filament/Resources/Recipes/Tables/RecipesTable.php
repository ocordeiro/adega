<?php

namespace App\Filament\Resources\Recipes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class RecipesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('photo')
                    ->label('Foto')
                    ->collection('photo')
                    ->conversion('thumb')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Receita')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('difficulty')
                    ->label('Dificuldade')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'fácil'   => 'success',
                        'médio'   => 'warning',
                        'difícil' => 'danger',
                        default   => 'gray',
                    }),
                TextColumn::make('prep_time')
                    ->label('Preparo')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} min" : '—')
                    ->sortable(),
                TextColumn::make('wines_count')
                    ->label('Vinhos')
                    ->counts('wines')
                    ->badge()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('difficulty')
                    ->label('Dificuldade')
                    ->options([
                        'fácil'   => 'Fácil',
                        'médio'   => 'Médio',
                        'difícil' => 'Difícil',
                    ]),
                TernaryFilter::make('is_active')->label('Ativo'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
