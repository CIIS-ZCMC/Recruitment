<?php

namespace App\Filament\Resources\Applications\Tables;

use App\Models\JobPosts;
use App\Models\PublishedJobPosts;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                TextColumn::make('applicant.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('age')
                    ->getStateUsing(
                        fn($record) =>
                        Carbon::parse($record->applicant->personalInformation->date_of_birth)->age
                    )
                    ->sortable(),
                TextColumn::make('applicant.personalInformation.sex')
                    ->label("Gender")
                    ->sortable(),
                TextColumn::make('contact')
                    ->label("Contact Information")
                    ->getStateUsing(fn($record) => $record->applicant->email . " <br> " . $record->applicant->phone)
                    ->html(),
                TextColumn::make('publishedJobPosts.jobPost.title')
                    ->label("Job Post")
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->weight("small")
                    ->size("14px")
                    ->formatStateUsing(fn(string $state): string => strtoupper($state))
                    ->color(fn(string $state): string => match ($state) {
                        'pending'           => 'warning',
                        'approved'          => 'success',
                        'shortlisted'       => 'info',
                        'interviewed'       => 'primary',
                        'recommended'       => 'primary',
                        'hired'             => 'success',
                        'onboarded'         => 'success',

                        // Negative / terminal statuses
                        'failed_interview'  => 'danger',
                        'not_qualified'     => 'danger',
                        'rejected'          => 'danger',
                        'not_selected'      => 'danger',
                        'withdrawn'         => 'gray',
                        'no_show'           => 'gray',

                        default             => 'secondary',
                    }),
                TextColumn::make('created_at')
                    ->label("Date Applied")
                    ->dateTime("h:ia M j,Y ")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                SelectFilter::make('position_id')
                    ->label('Job Postings')
                    ->options(function () {
                        // Get [id => title] from the related jobPost
                        return PublishedJobPosts::with('jobPost')
                            ->get()
                            ->pluck('jobPost.title', 'id')
                            ->toArray();
                    })
                    ->query(function ($query, $data) {
                        $value = $data['value'] ?? null;

                        if ($value) {
                            $query->where('published_job_post_id', $value);
                        }
                    })
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(function () {
                        return [
                            'pending'           => 'Pending',
                            'approved'          => 'Approved',
                            'shortlisted'       => 'Shortlisted',
                            'interviewed'       => 'Interviewed',
                            'recommended'       => 'Recommended',
                            'hired'             => 'Hired',
                            'onboarded'         => 'Onboarded',

                            // Negative / terminal statuses
                            'failed_interview'  => 'Failed_interview',
                            'not_qualified'     => 'Not_qualified',
                            'rejected'          => 'Rejected',
                            'not_selected'      => 'Not_selected',
                            'withdrawn'         => 'Withdrawn',
                            'no_show'           => 'No_show',
                        ];
                    })
                    ->query(function ($query, $data) {
                        $value = $data['value'] ?? null;

                        if ($value) {
                            $query->where('status', $value);
                        }
                    })
                    ->searchable()
                    ->preload()


            ])
            ->recordActions([
                // ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
