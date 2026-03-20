<?php

namespace App\Filament\Resources\Occasions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class OccasionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('icon')->label('')->searchable(false)->sortable(false),
                TextColumn::make('name')->label('Ocasião')->searchable()->sortable(),
                TextColumn::make('description')->label('Descrição')->limit(60)->toggleable(),
                TextColumn::make('foods_count')->label('Alimentos')->counts('foods')->badge()->sortable(),
                TextColumn::make('sort_order')->label('Ordem')->sortable(),
                IconColumn::make('is_active')->label('Ativa')->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([TernaryFilter::make('is_active')->label('Ativa')])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
