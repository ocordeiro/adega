<?php

namespace App\Filament\Resources\Wines\Pages;

use App\Filament\Resources\Wines\WineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWines extends ListRecords
{
    protected static string $resource = WineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
