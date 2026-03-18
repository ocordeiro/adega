<?php

namespace App\Filament\Resources\GrapeVarieties\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GrapeVarietiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Uva / Variedade')->searchable()->sortable(),
                TextColumn::make('wines_count')->label('Vinhos')->counts('wines')->badge()->sortable(),
            ])
            ->filters([])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
