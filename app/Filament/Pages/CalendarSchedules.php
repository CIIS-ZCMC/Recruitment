<?php

namespace App\Filament\Pages;

use Filament\Support\Icons\Heroicon;
use Filament\Pages\Page;
use BackedEnum;
use Filament\Actions\Action;

class CalendarSchedules extends Page
{
    protected string $view = 'filament.pages.calendar-schedules';

    protected ?string $subheading = 'Manage event and calendar schedules';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

    public function getEvents(): array
    {
        return [
            [
                'title' => 'Meeting',
                'start' => '2025-11-25',
            ],
            [
                'title' => 'Deadline',
                'start' => '2025-11-26',
                'end' => '2025-11-27'
            ],
        ];
    }

    public function btnCreateEvent()
    {
        return Action::make('create-event')
            ->url(fn(): string => CalendarSchedules::getUrl())
            ->icon('heroicon-o-plus');
    }
}
