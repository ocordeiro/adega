<?php

namespace App\Filament\Resources\Regions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RegionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Região')->searchable()->sortable(),
                TextColumn::make('country.name')->label('País')->searchable()->sortable(),
                TextColumn::make('wines_count')->label('Vinhos')->counts('wines')->badge()->sortable(),
            ])
            ->filters([
                SelectFilter::make('country_id')->label('País')->relationship('country', 'name')->searchable()->preload(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
