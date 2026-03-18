<?php

namespace App\Filament\Resources\Countries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CountriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->label('ISO')->badge()->sortable(),
                TextColumn::make('name')->label('Nome')->searchable()->sortable(),
                TextColumn::make('regions_count')->label('Regiões')->counts('regions')->badge()->sortable(),
                TextColumn::make('wines_count')->label('Vinhos')->counts('wines')->badge()->sortable(),
            ])
            ->filters([])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
