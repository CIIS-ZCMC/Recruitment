<?php

namespace App\Filament\Resources\JobPosts\Tables;

use App\Models\JobPostPlantilla;
use App\Models\JobPosts;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group as ComponentsGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class JobPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label("Job Position")
                    ->searchable()->toggleable(),
                TextColumn::make('description')

                    ->searchable()->toggleable(),
                TextColumn::make('Place of assignment')
                    ->label("Assignment")
                    ->getStateUsing(function ($record) {
                        return $record->status->place_of_assignment;
                    })
                    ->searchable()->toggleable(),
                TextColumn::make('Published Date')
                    ->dateTime('M j, Y')
                    ->getStateUsing(function ($record) {

                        return $record->published->first()->published_date ?? null;
                    })
                    ->searchable()->toggleable(),
                TextColumn::make('Closing Date-Time')
                    ->dateTime('M j, Y h:i A')
                    ->getStateUsing(function ($record) {
                        return $record->published->first()->closing_date . ' ' . $record->published->first()->closing_time;
                    })
                    ->searchable()->toggleable(),
                TextColumn::make('no_of_vacancy')
                    ->color('primary')
                    ->alignCenter()
                    ->getStateUsing(function ($record) {
                        return count($record->plantilla);
                    })
                    ->searchable()->toggleable(),
                TextColumn::make('Employment Type')
                    ->label("Employment Type")
                    ->size('sm')
                    ->getStateUsing(function ($record) {
                        return $record->plantilla->first()->is_contract ? 'Contractual/Job Order' : 'Permanent/Regular';
                    })
                    ->searchable()->toggleable(),
                TextColumn::make('Status')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $dateNow = date('Y-m-d');
                        $publishedDate = $record->published->first()->published_date;
                        $closingDate = $record->published->first()->closing_date;
                        if ($publishedDate <= $dateNow) {
                            if (Carbon::parse($closingDate)->format("Y-m-d") <= $dateNow) {
                                return 'Filing Closed';
                            } else {
                                return 'Published';
                            }
                        } else {
                            return 'Unpublished';
                        }
                    })
                    ->color(fn($state) => match ($state) {
                        'Published' => 'success',
                        'Filing Closed'    => 'warning',
                        'Unpublished'     => 'danger',
                        default     => 'gray',
                    })
                    ->sortable()->toggleable(),
                TextColumn::make('created_at')->toggleable()
                    ->dateTime('h:i A M j, Y'),
            ])
            ->filters([
                TrashedFilter::make(),

                Filter::make('created_at')->label("Created At")
                    ->schema([
                        ComponentsGroup::make()

                            ->schema([
                                DatePicker::make('from')->label("Created From"),
                                DatePicker::make('until')->label(" To"),
                            ])->columns(2)
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),

                Filter::make("status")->label("Status")
                    ->schema([
                        Select::make('status')
                            ->options([
                                'published' => 'Published',
                                'unpublished' => 'Unpublished',
                                'filing_closed' => 'Filing Closed',
                            ])
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['status'] === 'published') {
                            return $query->whereHas('published', function ($q) {
                                $q->where('published_date', '<=', now()->toDateString())
                                    ->whereRaw('DATE(CONCAT(closing_date, " ", closing_time)) >= ?', [now()->toDateString()]);
                            });
                        } elseif ($data['status'] === 'unpublished') {
                            return $query->whereHas('published', function ($q) {
                                $q->where('published_date', '>', now()->toDateString());
                            });
                        } elseif ($data['status'] === 'filing_closed') {
                            return $query->whereHas('published', function ($q) {
                                $q->whereRaw('DATE(CONCAT(closing_date, " ", closing_time)) <= ?', [now()->toDateString()]);
                            });
                        }
                        return $query;
                    }),



                Filter::make('published_date')->label("Published Date")
                    ->schema([
                        ComponentsGroup::make()

                            ->schema([
                                DatePicker::make('from')->label("Published From"),
                                DatePicker::make('until')->label(" To"),
                            ])->columns(2)
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereHas('published', function ($subQ) use ($data) {
                                $subQ->whereDate('published_date', '>=', $data['from']);
                            }))
                            ->when($data['until'], fn($q) => $q->whereHas('published', function ($subQ) use ($data) {
                                $subQ->whereDate('published_date', '<=', $data['until']);
                            }));
                    }),


            ])
            ->recordActions([

                ActionGroup::make([
                    ViewAction::make()->icon('heroicon-o-eye'),
                    EditAction::make()->icon(Heroicon::PencilSquare),
                    Action::make('Publish')
                        ->color('success')
                        ->hidden(function ($record) {
                            $published = $record->published->first();
                            if (! $published || ! $published->published_date) {
                                return false;
                            }
                            return $published->published_date <= now()->toDateString();
                        })

                        ->icon(Heroicon::ArrowUpOnSquare)->action(function ($record) {
                            // $record->published()->create([
                            //     'published_date' => now(),
                            //     'closing_date' => now()->addDays(30),
                            // ]);
                        })->action(function ($record) {
                            $record->published()->updateOrCreate(
                                ['job_post_id' => $record->id],
                                [
                                    'published_date' => now(),
                                ]
                            );

                            Notification::make()
                                ->title('Job Post Published')
                                ->icon('heroicon-o-check-circle')
                                ->color('success')
                                ->body('The job posting has been published successfully.')
                                ->success()
                                ->send();
                        }),
                    Action::make('Unpublish')
                        ->color('danger')
                        ->hidden(function ($record) {
                            $published = $record->published->first();
                            if (! $published || ! $published->published_date) {
                                return false;
                            }

                            // Check if the job post is already closed
                            $closingDate = $published->closing_date;
                            if ($closingDate && Carbon::parse($closingDate)->isPast()) {
                                return true;
                            }

                            return $published->published_date > now()->toDateString();
                        })

                        ->icon(Heroicon::ArrowDownOnSquare)->action(function ($record) {
                            // $record->published()->delete();
                        })->action(function ($record) {
                            $record->published()->updateOrCreate(
                                ['job_post_id' => $record->id],
                                [
                                    'published_date' => now()->addDays(5),
                                ]
                            );

                            Notification::make()
                                ->title('Job Post Unpublished')
                                ->icon('heroicon-o-x-circle')
                                ->color('warning')
                                ->body('The job posting has been unpublished successfully.')
                                ->warning()
                                ->send();
                        }),
                    Action::make('Close')->icon(Heroicon::LockClosed)
                        ->hidden(function ($record) {
                            $published = $record->published->first();
                            if (! $published || ! $published->published_date) {
                                return false;
                            }
                            // Check if the job post is already closed
                            $closingDate = $published->closing_date;
                            if ($closingDate && Carbon::parse($closingDate)->isPast()) {
                                return true;
                            }


                            return $published->published_date > now()->toDateString();
                        })
                        ->action(function ($record) {
                            $record->published()->updateOrCreate(
                                ['job_post_id' => $record->id],
                                [
                                    'closing_date' => now()->toDateString(),
                                    'closing_time' => now()->toTimeString(),
                                ]
                            );

                            Notification::make()
                                ->title('Job Post Closed')
                                ->icon('heroicon-o-x-circle')
                                ->color('warning')
                                ->body('The job posting has been closed successfully.')
                                ->warning()
                                ->send();
                        }),

                ])

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    Action::make("Publish Selected")
                        ->icon(Heroicon::ArrowUpOnSquare)
                        ->color("success")
                        ->requiresConfirmation()
                        ->accessSelectedRecords()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->published()->updateOrCreate(
                                    ['job_post_id' => $record->id],
                                    [
                                        'published_date' => now(),
                                    ]
                                );
                            }
                            Notification::make()
                                ->title('Selected Job Posts Published')
                                ->color("success")
                                ->body('The selected job posts have been published successfully.')
                                ->success()
                                ->send();
                        }),

                    Action::make("Unpublish Selected")
                        ->icon(Heroicon::ArrowDownOnSquare)
                        ->color("warning")
                        ->requiresConfirmation()
                        ->accessSelectedRecords()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->published()->updateOrCreate(
                                    ['job_post_id' => $record->id],
                                    [
                                        'published_date' => now()->addDays(5),
                                    ]
                                );
                            }
                            Notification::make()
                                ->title('Selected Job Posts Set to Expire in 5 Days')
                                ->color("warning")
                                ->body('The selected job posts will expire in 5 days.')
                                ->warning()
                                ->send();
                        })

                ]),
            ]);
    }

    public function getPlantilla(JobPosts $record)
    {
        return JobPostPlantilla::query()
            ->where('job_post_id', $record->id)
            ->first();
    }
}
