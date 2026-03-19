<?php

namespace App\Filament\Resources\DrinkRecipes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DrinkRecipesTable
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
                    ->label('Drink')
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
                TextColumn::make('ingredients_count')
                    ->label('Ingredientes')
                    ->counts('ingredients')
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
