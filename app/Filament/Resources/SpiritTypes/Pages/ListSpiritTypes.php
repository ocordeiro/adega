<?php

namespace App\Filament\Resources\SpiritTypes\Pages;

use App\Filament\Resources\SpiritTypes\SpiritTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSpiritTypes extends ListRecords
{
    protected static string $resource = SpiritTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
