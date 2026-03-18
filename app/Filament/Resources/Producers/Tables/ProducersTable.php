<?php

namespace App\Filament\Resources\Producers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProducersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Produtor / Vinícola')->searchable()->sortable(),
                TextColumn::make('country.name')->label('País')->searchable()->sortable(),
                TextColumn::make('website')->label('Website')->url(fn ($record) => $record->website)->limit(40)->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('wines_count')->label('Vinhos')->counts('wines')->badge()->sortable(),
            ])
            ->filters([
                SelectFilter::make('country_id')->label('País')->relationship('country', 'name')->searchable()->preload(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
