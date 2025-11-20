<x-filament-panels::page>
    {{ $this->form }}

    <style>
        .save-btn {
            background-color: #10b981 !important;
            border-color: #10b981 !important;
            width: 200px !important;
        }
    </style>
    <x-filament::button wire:click="save" class="save-btn">
        Save
    </x-filament::button>
</x-filament-panels::page>
