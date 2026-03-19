<?php

namespace App\Filament\Resources\Spirits\Pages;

use App\Filament\Resources\Spirits\SpiritResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditSpirit extends EditRecord
{
    protected static string $resource = SpiritResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
