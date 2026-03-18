<?php

namespace App\Filament\Resources\Wines\Pages;

use App\Filament\Resources\Wines\WineResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWine extends CreateRecord
{
    protected static string $resource = WineResource::class;
}
