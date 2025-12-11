<?php

namespace App\Filament\Resources\Applications\Tables;

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ApplicationController;
use App\Models\JobPosts;
use App\Models\PublishedJobPosts;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Support\Icons\Heroicon;
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
                    ),
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
                    ->formatStateUsing(fn(string $state): string =>  strtoupper(str_replace('_', ' ', $state)))
                    ->color(fn(string $state): string => match ($state) {

                        // Pre-Screening
                        'pending'                   => 'warning',
                        'for_initial_screening'     => 'info',
                        'for_shortlisting'          => 'info',

                        // Shortlisting & Evaluation
                        'shortlisted'               => 'primary',
                        'for_interview'             => 'primary',
                        'interviewed'               => 'primary',
                        'for_assessment'            => 'primary',
                        'assessment_completed'      => 'success',

                        // Background Check & Recommendation
                        'for_background_check'      => 'info',
                        'recommended'               => 'primary',
                        'for_final_deliberation'    => 'info',
                        'approved_for_hire'         => 'success',

                        // Hiring & Onboarding
                        'job_offer_sent'            => 'warning',
                        'job_offer_accepted'        => 'success',
                        'pre_employment_requirements' => 'info',
                        'hired'                     => 'success',
                        'onboarded'                 => 'success',

                        // Negative / End-of-process
                        'not_qualified'             => 'danger',
                        'failed_assessment'         => 'danger',
                        'failed_interview'          => 'danger',
                        'not_selected'              => 'danger',
                        'rejected'                  => 'danger',
                        'withdrawn'                 => 'gray',
                        'no_show'                   => 'gray',
                        'offer_declined'            => 'gray',

                        default                     => 'secondary',
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
                            // Pre-Screening
                            'pending'                   => 'Pending',
                            'for_initial_screening'     => 'For Initial Screening',
                            'for_shortlisting'          => 'For Shortlisting',

                            // Shortlisting & Evaluation
                            'shortlisted'               => 'Shortlisted',
                            'for_interview'             => 'For Interview',
                            'interviewed'               => 'Interviewed',
                            'for_assessment'            => 'For Assessment',
                            'assessment_completed'      => 'Assessment Completed',

                            // Background Check & Recommendation
                            'for_background_check'      => 'For Background Check',
                            'recommended'               => 'Recommended',
                            'for_final_deliberation'    => 'For Final Deliberation',
                            'approved_for_hire'         => 'Approved for Hire',

                            // Hiring & Onboarding
                            'job_offer_sent'            => 'Job Offer Sent',
                            'job_offer_accepted'        => 'Job Offer Accepted',
                            'pre_employment_requirements' => 'Pre-Employment Requirements',
                            'hired'                     => 'Hired',
                            'onboarded'                 => 'Onboarded',

                            // Negative Results
                            'not_qualified'             => 'Not Qualified',
                            'failed_assessment'         => 'Failed Assessment',
                            'failed_interview'          => 'Failed Interview',
                            'not_selected'              => 'Not Selected',
                            'rejected'                  => 'Rejected',
                            'withdrawn'                 => 'Withdrawn',
                            'no_show'                   => 'No-Show',
                            'offer_declined'            => 'Offer Declined',
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
                ActionGroup::make([
                    ViewAction::make(),
                    ActionGroup::make([
                        Action::make('Acknowledgement Email')
                            ->icon(Heroicon::BellAlert),
                        Action::make('Letter of Regret')
                            ->icon(Heroicon::BellAlert),
                        Action::make('Custom Message')
                            ->icon(Heroicon::BellAlert),

                    ])
                        ->label("Send Email Notification")
                        ->icon(Heroicon::Envelope),


                    ActionGroup::make([
                        Action::make('Educational Background')
                            ->icon(Heroicon::InformationCircle),
                        Action::make('Training')
                            ->icon(Heroicon::InformationCircle),
                        Action::make('Experience')
                            ->icon(Heroicon::InformationCircle),
                        Action::make('Eligibility')
                            ->icon(Heroicon::InformationCircle),
                        Action::make('Incomplete file uploads')
                            ->icon(Heroicon::InformationCircle),
                        Action::make('Others')
                            ->icon(Heroicon::InformationCircle),

                    ])
                        ->label("Mark not qualified")
                        ->icon(Heroicon::NoSymbol)
                        ->color("danger"),
                    Action::make("Set for Initial screening")
                        ->icon(Heroicon::ChatBubbleBottomCenter)
                        ->hidden(function ($record) {
                            return $record->status !== 'pending';
                        })
                        ->color("success")
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            return ApplicationController::ChangeStatus('for_initial_screening', $record->id);
                        }),
                    Action::make("Set for Interview")
                        ->icon(Heroicon::Calendar)
                        ->color("warning"),
                    // ->action(function ($record) {
                    //     return ApplicationController::ChangeStatus('for_interview', $record->id);
                    // }),

                    Action::make("change_status")
                        ->icon(Heroicon::ListBullet)
                        ->schema([
                            Select::make("change_status")
                                ->label("Change Application Status")
                                ->options([

                                    // Shortlisting
                                    'for_shortlisting'          => 'For Shortlisting',

                                    // Shortlisting & Evaluation
                                    'shortlisted'               => 'Shortlisted',
                                    'interviewed'               => 'Interviewed',
                                    'for_assessment'            => 'For Assessment',
                                    'assessment_completed'      => 'Assessment Completed',

                                    // Background Check & Recommendation
                                    'for_background_check'      => 'For Background Check',
                                    'recommended'               => 'Recommended',
                                    'for_final_deliberation'    => 'For Final Deliberation',
                                    'approved_for_hire'         => 'Approved for Hire',

                                    // Hiring & Onboarding
                                    'job_offer_sent'            => 'Job Offer Sent',
                                    'job_offer_accepted'        => 'Job Offer Accepted',
                                    'pre_employment_requirements' => 'Pre-Employment Requirements',
                                    'hired'                     => 'Hired',
                                    'onboarded'                 => 'Onboarded',

                                    // Negative Results
                                    'not_qualified'             => 'Not Qualified',
                                    'failed_assessment'         => 'Failed Assessment',
                                    'failed_interview'          => 'Failed Interview',
                                    'not_selected'              => 'Not Selected',
                                    'rejected'                  => 'Rejected',
                                    'withdrawn'                 => 'Withdrawn',
                                    'no_show'                   => 'No-Show',
                                    'offer_declined'            => 'Offer Declined',
                                ])

                        ])
                        ->modalWidth("md")
                        ->action(function ($record, $data) {

                            return ApplicationController::ChangeStatus($data['change_status'], $record->id);
                        })
                        ->requiresConfirmation(),



                ])

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make("change_status")
                        ->icon(Heroicon::ListBullet)
                        ->schema([
                            Select::make("change_status")
                                ->label("Change Application Status")
                                ->options([

                                    // Shortlisting
                                    'for_shortlisting'          => 'For Shortlisting',

                                    // Shortlisting & Evaluation
                                    'shortlisted'               => 'Shortlisted',
                                    'interviewed'               => 'Interviewed',
                                    'for_assessment'            => 'For Assessment',
                                    'assessment_completed'      => 'Assessment Completed',

                                    // Background Check & Recommendation
                                    'for_background_check'      => 'For Background Check',
                                    'recommended'               => 'Recommended',
                                    'for_final_deliberation'    => 'For Final Deliberation',
                                    'approved_for_hire'         => 'Approved for Hire',

                                    // Hiring & Onboarding
                                    'job_offer_sent'            => 'Job Offer Sent',
                                    'job_offer_accepted'        => 'Job Offer Accepted',
                                    'pre_employment_requirements' => 'Pre-Employment Requirements',
                                    'hired'                     => 'Hired',
                                    'onboarded'                 => 'Onboarded',

                                    // Negative Results
                                    'not_qualified'             => 'Not Qualified',
                                    'failed_assessment'         => 'Failed Assessment',
                                    'failed_interview'          => 'Failed Interview',
                                    'not_selected'              => 'Not Selected',
                                    'rejected'                  => 'Rejected',
                                    'withdrawn'                 => 'Withdrawn',
                                    'no_show'                   => 'No-Show',
                                    'offer_declined'            => 'Offer Declined',
                                ])

                        ])
                        ->modalWidth("md"),

                    ActionGroup::make([
                        Action::make('Acknowledgement Email')
                            ->icon(Heroicon::BellAlert),
                        Action::make('Letter of Regret')
                            ->icon(Heroicon::BellAlert),
                        Action::make('Custom Message')
                            ->icon(Heroicon::BellAlert),

                    ])
                        ->label("Send Email Notification")
                        ->icon(Heroicon::Envelope),


                    ActionGroup::make([
                        Action::make('Educational Background')
                            ->icon(Heroicon::InformationCircle),
                        Action::make('Training')
                            ->icon(Heroicon::InformationCircle),
                        Action::make('Experience')
                            ->icon(Heroicon::InformationCircle),
                        Action::make('Eligibility')
                            ->icon(Heroicon::InformationCircle),
                        Action::make('Incomplete file uploads')
                            ->icon(Heroicon::InformationCircle),
                        Action::make('Others')
                            ->icon(Heroicon::InformationCircle),

                    ])
                        ->label("Mark not qualified")
                        ->icon(Heroicon::NoSymbol)
                        ->color("danger"),
                    BulkAction::make("Set for Initial screening")
                        ->icon(Heroicon::ChatBubbleBottomCenter)
                        ->color("success"),
                    BulkAction::make("Set for Interview")
                        ->icon(Heroicon::Calendar)
                        ->color("warning"),

                    // DeleteBulkAction::make(),
                    // ForceDeleteBulkAction::make(),
                    // RestoreBulkAction::make(),
                ]),
            ]);
    }
}
