<?php

namespace App\Filament\Resources\GrapeVarieties\Pages;

use App\Filament\Resources\GrapeVarieties\GrapeVarietyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGrapeVariety extends EditRecord
{
    protected static string $resource = GrapeVarietyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
