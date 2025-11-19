<?php

namespace App\Filament\Resources\JobPosts\Tables;

use App\Models\JobPostPlantilla;
use App\Models\JobPosts;
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
use Filament\Support\Icons\Heroicon;

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
                        if ($publishedDate < $dateNow) {
                            if ($closingDate < $dateNow) {
                                return 'Closed';
                            } else {
                                return 'Published';
                            }
                        } else {
                            return 'Unpublished';
                        }
                    })
                    ->color(fn($state) => match ($state) {
                        'Published' => 'success',
                        'Closed'    => 'default',
                        'Unpublished'     => 'danger',
                        default     => 'gray',
                    })
                    ->sortable()->toggleable(),
                TextColumn::make('created_at')->toggleable()
                    ->dateTime('h:i A M j, Y'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([

                ActionGroup::make([
                    ViewAction::make()->icon('heroicon-o-eye'),
                    EditAction::make()->icon('heroicon-o-pencil'),
                    Action::make('Publish')
                        ->color('success')
                        ->hidden(function ($record) {
                            $published = $record->published->first();
                            if (! $published || ! $published->published_date) {
                                return false;
                            }
                            return $published->published_date < now()->toDateString();
                        })

                        ->icon(Heroicon::ArrowUpOnSquare)->action(function ($record) {
                            // $record->published()->create([
                            //     'published_date' => now(),
                            //     'closing_date' => now()->addDays(30),
                            // ]);
                        }),
                    Action::make('Unpublish')
                        ->color('warning')
                        ->hidden(function ($record) {
                            $published = $record->published->first();
                            if (! $published || ! $published->published_date) {
                                return false;
                            }
                            return $published->published_date > now()->toDateString();
                        })

                        ->icon(Heroicon::ArrowDownOnSquare)->action(function ($record) {
                            // $record->published()->delete();
                        }),
                    Action::make('Close')->icon(Heroicon::LockClosed)->action(function ($record) {
                        //$record->published()->delete();
                    }),

                ])

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
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
