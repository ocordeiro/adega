<?php

namespace App\Filament\Resources\Themes\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ThemesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tema')
                    ->weight('medium'),

                TextColumn::make('color_preview')
                    ->label('Cores')
                    ->html()
                    ->getStateUsing(fn ($record) => self::colorPreviewHtml($record)),

                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
            ])
            ->recordActions([
                Action::make('activate')
                    ->label('Ativar')
                    ->icon(Heroicon::OutlinedBolt)
                    ->color('success')
                    ->hidden(fn ($record) => $record->is_active)
                    ->action(function ($record) {
                        $record->activate();
                        Notification::make()
                            ->title("Tema \"{$record->name}\" ativado")
                            ->success()
                            ->send();
                    }),


                EditAction::make()
                    ->label('Editar Cores')
                    ->hidden(fn ($record) => $record->slug === 'padrao'),
            ])
            ->paginated(false);
    }

    private static function colorPreviewHtml($record): string
    {
        $swatch = fn (string $color) => sprintf(
            '<span style="display:inline-block;width:1rem;height:1rem;border-radius:100%%;background:%s;border:1px solid rgba(255,255,255,.15);"></span>',
            e($color)
        );

        $bg = sprintf(
            '<span style="display:inline-flex;align-items:center;gap:.25rem;background:%s;padding:.2rem .5rem;border-radius:.375rem;">%s%s%s</span>',
            e($record->color_background),
            $swatch($record->color_primary),
            $swatch($record->color_secondary),
            $swatch($record->color_text)
        );

        return $bg;
    }
}
