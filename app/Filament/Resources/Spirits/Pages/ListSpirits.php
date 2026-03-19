<?php

namespace App\Filament\Resources\Spirits\Pages;

use App\Filament\Resources\Spirits\SpiritResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSpirits extends ListRecords
{
    protected static string $resource = SpiritResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
