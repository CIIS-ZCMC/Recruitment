<?php

namespace App\Livewire;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class ApplicationStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Unique views', '192.1k')
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('Bounce rate', '21%')
                ->description('7% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make('Average time on page', '3:12')
                ->description('3% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }
}
