<?php

namespace App\Filament\Resources\SpiritTypes\Pages;

use App\Filament\Resources\SpiritTypes\SpiritTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSpiritType extends EditRecord
{
    protected static string $resource = SpiritTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
