<?php

namespace App\Filament\Resources\WineTypes\Pages;

use App\Filament\Resources\WineTypes\WineTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWineTypes extends ListRecords
{
    protected static string $resource = WineTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
