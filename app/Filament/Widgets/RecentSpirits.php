<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Spirits\SpiritResource;
use App\Models\Spirit;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentSpirits extends TableWidget
{
    protected static ?string $heading = 'Destilados Recentes';

    protected int|string|array $columnSpan = 2;

    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Spirit::with(['spiritType', 'country'])->latest()->limit(10))
            ->columns([
                SpatieMediaLibraryImageColumn::make('photos')
                    ->label('')
                    ->collection('photos')
                    ->conversion('thumb')
                    ->width(40)
                    ->height(40),
                TextColumn::make('name')->label('Nome')->limit(30),
                TextColumn::make('spiritType.name')->label('Tipo')->badge()->color('warning'),
                TextColumn::make('country.name')->label('País'),
                TextColumn::make('alcohol_content')
                    ->label('Teor')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1) . '%' : '—'),
            ])
            ->recordUrl(fn (Spirit $record): string => SpiritResource::getUrl('edit', ['record' => $record]))
            ->paginated(false);
    }
}
