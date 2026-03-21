<?php

namespace App\Filament\Resources\Themes\Pages;

use App\Filament\Resources\Themes\ThemeResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditTheme extends EditRecord
{
    protected static string $resource = ThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('restoreDefaults')
                ->label('Restaurar Padrão')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Restaurar cores padrão')
                ->modalDescription("As cores serão restauradas para os valores originais.")
                ->hidden(fn () => $this->record->isAtDefaults())
                ->action(function () {
                    $this->record->restoreDefaults();
                    $this->fillForm();
                    Notification::make()
                        ->title('Cores restauradas ao padrão')
                        ->success()
                        ->send();
                }),
        ];
    }
}
