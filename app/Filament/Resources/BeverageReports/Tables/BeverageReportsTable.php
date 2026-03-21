<?php

namespace App\Filament\Resources\BeverageReports\Tables;

use App\Filament\Resources\Spirits\SpiritResource;
use App\Filament\Resources\Wines\WineResource;
use App\Models\Spirit;
use App\Models\Wine;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BeverageReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('barcode')
                    ->label('Código de Barras')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'wine'   => 'Vinho',
                        'spirit' => 'Destilado',
                        default  => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'wine'   => 'danger',
                        'spirit' => 'warning',
                        default  => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Reportado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'wine'   => 'Vinho',
                        'spirit' => 'Destilado',
                    ]),
                Filter::make('hide_registered')
                    ->label('Ocultar já cadastrados')
                    ->default()
                    ->query(fn (Builder $query) => $query
                        ->whereNotIn('barcode', Wine::pluck('barcode'))
                        ->whereNotIn('barcode', Spirit::pluck('barcode'))
                    ),
            ])
            ->recordActions([
                Action::make('register_wine')
                    ->label('Cadastrar Vinho')
                    ->icon(Heroicon::OutlinedBeaker)
                    ->color('danger')
                    ->url(fn ($record) => WineResource::getUrl('create', ['barcode' => $record->barcode]))
                    ->visible(fn ($record) => $record->type === 'wine'),
                Action::make('register_spirit')
                    ->label('Cadastrar Destilado')
                    ->icon(Heroicon::OutlinedFire)
                    ->color('warning')
                    ->url(fn ($record) => SpiritResource::getUrl('create', ['barcode' => $record->barcode]))
                    ->visible(fn ($record) => $record->type === 'spirit'),
                DeleteAction::make()
                    ->label('Excluir'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
