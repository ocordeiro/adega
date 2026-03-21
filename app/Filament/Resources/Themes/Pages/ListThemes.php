<?php

namespace App\Filament\Resources\Themes\Pages;

use App\Filament\Resources\Themes\ThemeResource;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class ListThemes extends ListRecords implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ThemeResource::class;
    protected string $view = 'filament.resources.themes.pages.list-themes';

    public ?array $settingsData = [];

    public function mount(): void
    {
        parent::mount();
        $this->settingsForm->fill(Setting::instance()->toArray());
    }

    public function settingsForm(Schema $schema): Schema
    {
        return $schema
            ->model(Setting::instance())
            ->components([
                Section::make('Logo & Escala')
                    ->description('Logotipo e tamanho dos elementos do kiosk.')
                    ->columns(2)
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->label('Logo')
                            ->collection('logo')
                            ->disk('s3')
                            ->image()
                            ->imageEditor()
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp'])
                            ->maxSize(5120)
                            ->columnSpan(1),

                        TextInput::make('element_scale')
                            ->label('Escala dos Elementos')
                            ->numeric()
                            ->step(0.1)
                            ->minValue(0.5)
                            ->maxValue(3.0)
                            ->required()
                            ->helperText('1.0 = tamanho original.')
                            ->columnSpan(1),
                    ]),
            ])
            ->statePath('settingsData');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('saveSettings')
                ->label('Salvar Configurações')
                ->icon(Heroicon::OutlinedCheck)
                ->color('primary')
                ->action(fn () => $this->saveSettings()),
        ];
    }

    public function saveSettings(): void
    {
        $data = $this->settingsForm->getState();

        $setting = Setting::instance();
        $setting->update($data);

        $this->settingsForm->model($setting)->saveRelationships();

        Notification::make()
            ->title('Configurações salvas com sucesso')
            ->success()
            ->send();
    }
}
