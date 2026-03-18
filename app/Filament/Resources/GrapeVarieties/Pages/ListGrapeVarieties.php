<?php

namespace App\Filament\Resources\GrapeVarieties\Pages;

use App\Filament\Resources\GrapeVarieties\GrapeVarietyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGrapeVarieties extends ListRecords
{
    protected static string $resource = GrapeVarietyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
