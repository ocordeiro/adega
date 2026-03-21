<x-filament-panels::page>
    {{-- Settings form (logo + scale) --}}
    <form wire:submit="saveSettings">
        {{ $this->settingsForm }}
    </form>

    {{-- Themes table --}}
    {{ $this->table }}
</x-filament-panels::page>
