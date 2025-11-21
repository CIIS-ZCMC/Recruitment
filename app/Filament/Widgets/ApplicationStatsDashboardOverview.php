<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApplicationStatsDashboardOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Applications', 101),
            Stat::make('Total Applications', 100),
            Stat::make('Total Applications', 100),
            Stat::make('Total Applications', 100),
        ];
    }
}
