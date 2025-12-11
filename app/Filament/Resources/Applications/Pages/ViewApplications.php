<?php

namespace App\Filament\Resources\Applications\Pages;

use App\Filament\Resources\Applications\ApplicationsResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;

class ViewApplications extends ViewRecord
{
    protected static string $resource = ApplicationsResource::class;


    public function getHeading(): string|Htmlable
    {
        return $this->record->publishedJobPosts->jobPost->title . " ~ " . $this->record->applicant->name;
    }

    public function getSubheading(): string|Htmlable
    {
        return "Viewing " . $this->record->applicant->name . " application details";
    }

    protected function getHeaderActions(): array
    {
        return [


            ActionGroup::make([
                Action::make('Acknowledgement Email')
                    ->icon(Heroicon::BellAlert),
                Action::make('Letter of Regret')
                    ->icon(Heroicon::BellAlert),
                Action::make('Custom Message')
                    ->icon(Heroicon::BellAlert),

            ])
                ->tooltip("Send Email Notification")
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
                ->tooltip("Mark not qualified")
                ->label("Mark not qualified")
                ->icon(Heroicon::NoSymbol)
                ->outlined()
                ->color("danger"),

            Action::make("Set for Initial screening")
                ->icon(Heroicon::ChatBubbleBottomCenter)
                ->tooltip("Set for Initial screening")
                ->link()
                ->hiddenLabel()
                ->color("success"),
            Action::make("Set for Interview")
                ->icon(Heroicon::Calendar)
                ->tooltip("Set for Interview")
                ->hiddenLabel()
                ->link()
                ->outlined()
                ->color("warning"),

            Action::make("change_status")
                ->hiddenLabel()
                ->link()
                ->tooltip("Change Application Status")
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

            Action::make('Back')
                ->color('gray')
                ->icon(Heroicon::ArrowRight)

                ->iconPosition(IconPosition::After)
                ->extraAttributes([
                    'onclick' => 'history.back()'
                ]),


        ];
    }
}
