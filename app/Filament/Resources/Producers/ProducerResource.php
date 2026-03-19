<?php

namespace App\Filament\Resources\Producers;

use App\Filament\Resources\Producers\Pages\CreateProducer;
use App\Filament\Resources\Producers\Pages\EditProducer;
use App\Filament\Resources\Producers\Pages\ListProducers;
use App\Filament\Resources\Producers\Schemas\ProducerForm;
use App\Filament\Resources\Producers\Tables\ProducersTable;
use App\Models\Producer;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProducerResource extends Resource
{
    protected static ?string $model = Producer::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;
    protected static UnitEnum|string|null $navigationGroup = 'Catálogo';
    protected static ?string $navigationLabel = 'Produtores';
    protected static ?string $modelLabel = 'Produtor';
    protected static ?string $pluralModelLabel = 'Produtores';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema { return ProducerForm::configure($schema); }
    public static function table(Table $table): Table { return ProducersTable::configure($table); }
    public static function getRelations(): array { return []; }
    public static function getPages(): array
    {
        return [
            'index'  => ListProducers::route('/'),
            'create' => CreateProducer::route('/create'),
            'edit'   => EditProducer::route('/{record}/edit'),
        ];
    }
}
