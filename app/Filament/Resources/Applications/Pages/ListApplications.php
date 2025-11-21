<?php

namespace App\Filament\Resources\Applications\Pages;

use App\Filament\Resources\Applications\ApplicationsResource;
use App\Filament\Resources\Applications\Widgets\ApplicationsWidget;
use App\Livewire\ApplicationStatsOverview;
use App\Livewire\CustomApplicationWidget1;
use App\Livewire\CustomApplicationWidget2;
use App\Livewire\CustomApplicationWidget3;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ApplicationStatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            CustomApplicationWidget1::class,
            CustomApplicationWidget2::class,
            CustomApplicationWidget3::class,
        ];
    }

    public function getFooterWidgetsColumns(): int|array
    {
        return 3;
    }
}
