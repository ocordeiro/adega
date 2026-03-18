<?php

namespace App\Filament\Resources\WineTypes\Pages;

use App\Filament\Resources\WineTypes\WineTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWineType extends EditRecord
{
    protected static string $resource = WineTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
