<?php

namespace App\Filament\Resources\Food\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\SpatieLaravelMediaLibraryPlugin\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FoodTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->label('Foto')
                    ->collection('image')
                    ->conversion('thumb')
                    ->circular(),
                TextColumn::make('name')->label('Nome')->searchable()->sortable(),
                TextColumn::make('foodCategory.name')->label('Categoria')->searchable()->sortable()->badge(),
                TextColumn::make('wines_count')->label('Harmonizações')->counts('wines')->badge()->sortable(),
            ])
            ->filters([
                SelectFilter::make('food_category_id')->label('Categoria')->relationship('foodCategory', 'name')->searchable()->preload(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
