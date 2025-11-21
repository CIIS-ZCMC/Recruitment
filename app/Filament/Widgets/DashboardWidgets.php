<?php

namespace App\Filament\Widgets;


use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\User;
use Filament\Widgets\Widget;

class DashboardWidgets extends Widget
{
    protected string $view = 'filament.widgets.dashboard-widgets';

    protected int | string | array $columnSpan = 'full';
    public $customData;

    public function mount(): void
    {
        $this->customData = [
            'message' => 'Hello from the widget!',
            'count'   => 42,
        ];
    }

    public function getColumnSpan(): int|string|array
    {
        return 1; // half width
    }


    protected function getStats(): array
    {
        return [
            Stat::make('Users', 100),
            Stat::make('Sales', '₱20,000'),
        ];
    }

    // public function getTable(): Table
    // {
    //     return Table::make()
    //         ->query(User::query())
    //         ->columns([
    //             TextColumn::make('name')
    //                 ->searchable()
    //                 ->sortable(),
    //             TextColumn::make('email')
    //                 ->searchable()
    //                 ->sortable(),
    //             TextColumn::make('created_at')
    //                 ->dateTime()
    //                 ->sortable(),
    //         ])
    //         ->paginate(10);
    // }
}
