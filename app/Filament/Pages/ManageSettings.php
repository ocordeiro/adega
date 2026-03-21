<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Aparência';
    protected static ?string $navigationLabel = 'Aparência';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaintBrush;
    protected static UnitEnum|string|null $navigationGroup = 'Configurações';
    protected static ?int $navigationSort = 20;
    protected static bool $shouldRegisterNavigation = false;
    protected string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Setting::instance()->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->model(Setting::instance())
            ->components([
                Section::make('Logo')
                    ->description('Logotipo exibido no kiosk.')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->label('Imagem do Logo')
                            ->collection('logo')
                            ->disk('s3')
                            ->image()
                            ->imageEditor()
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp'])
                            ->maxSize(5120),
                    ]),

                Section::make('Tamanho dos Elementos')
                    ->description('Fator de escala aplicado a todos os elementos da interface (fontes, espaçamentos, etc).')
                    ->columns(2)
                    ->schema([
                        TextInput::make('element_scale')
                            ->label('Escala')
                            ->numeric()
                            ->step(0.1)
                            ->minValue(0.5)
                            ->maxValue(3.0)
                            ->required()
                            ->helperText('1.0 = tamanho original. Valores maiores aumentam todos os elementos proporcionalmente.'),

                        TextInput::make('font_scale')
                            ->label('Tamanho da Fonte')
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0.8)
                            ->maxValue(1.5)
                            ->required()
                            ->helperText('1.07 = 7% maior que o padrão. Afeta apenas o tamanho das fontes.'),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Salvar Configurações')
                ->icon(Heroicon::OutlinedCheck)
                ->color('primary')
                ->action(fn () => $this->save()),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $setting = Setting::instance();
        $setting->update($data);

        $this->form->model($setting)->saveRelationships();

        Notification::make()
            ->title('Configurações salvas com sucesso')
            ->success()
            ->send();
    }
}
