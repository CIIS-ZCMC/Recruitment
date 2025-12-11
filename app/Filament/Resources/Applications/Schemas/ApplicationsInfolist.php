<?php

namespace App\Filament\Resources\Applications\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ApplicationsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Job Post Information")
                    ->description("Basic information about the job posting")
                    ->components([
                        TextEntry::make('Place of Assignment')
                            ->weight('semibold')
                            ->state(function ($record) {
                                if (! $record) return;
                                $assignment = optional($record->publishedJobPosts->jobPost->status->first())->place_of_assignment;
                                if ($assignment) {
                                    return $assignment;
                                }
                            }),
                        TextEntry::make('Employment Type')
                            ->weight('semibold')
                            ->state(function ($record) {
                                return $record->publishedJobPosts->jobPost->plantilla->first()->is_contract ? 'Contractual/Job Order' : 'Permanent/Regular';
                            }),
                        TextEntry::make('publishedJobPosts.jobPost.description')->columnSpanFull(),

                        TextEntry::make('Educational Background')
                            ->state(function ($record) {
                                if (! $record) return;
                                $background = optional($record->publishedJobPosts->jobPost->qualifications)->educational_background;
                                if ($background) {
                                    return $background;
                                }
                            })
                            ->columnSpanFull(),
                        TextEntry::make('Qualifications')
                            ->state(function ($record) {
                                if (! $record) return;
                                $qualifications = optional($record->publishedJobPosts->jobPost->qualifications)->qualification;
                                if ($qualifications) {
                                    return $qualifications;
                                }
                            })
                            ->columnSpanFull(),
                        TextEntry::make('Experience')
                            ->state(function ($record) {
                                if (! $record) return;
                                $experience = optional($record->publishedJobPosts->jobPost->qualifications)->experience;
                                if ($experience) {
                                    return $experience;
                                }
                            })
                            ->columnSpanFull(),
                        TextEntry::make('Competencies')
                            ->markdown()
                            ->state(function ($record) {
                                return optional($record->publishedJobPosts->jobPost->qualifications)->competencies;
                            })
                            ->columnSpanFull(),
                        TextEntry::make('Skills')
                            ->state(function ($record) {
                                if (! $record) return;
                                $skills = optional($record->publishedJobPosts->jobPost->qualifications)->skills;
                                if ($skills) {
                                    return $skills;
                                }
                            })
                            ->columnSpanFull(),
                        TextEntry::make('Trainings')
                            ->state(function ($record) {
                                if (! $record) return;
                                $trainings = optional($record->publishedJobPosts->jobPost->qualifications)->trainings;
                                if ($trainings) {
                                    return $trainings;
                                }
                            })
                            ->columnSpanFull(),
                    ])->columnSpan(3)->columns(2),

                Tabs::make("applicantInformation")
                    ->tabs([
                        Tab::make('Personal Information')
                            ->icon(Heroicon::InformationCircle)
                            ->schema([
                                Section::make("Applicant Information")
                                    ->description("Basic information about the applicant")
                                    ->components([
                                        TextEntry::make("Full Name")
                                            ->label("Full Name")
                                            ->color('warning')
                                            ->size("lg")
                                            ->state(fn($record) => $record->applicant->personalInformation->fullName())->columnSpan(3),
                                        TextEntry::make("applicant.personalInformation.date_of_birth"),
                                        TextEntry::make("applicant.personalInformation.sex"),
                                        TextEntry::make("applicant.personalInformation.blood_type"),
                                        TextEntry::make("applicant.personalInformation.religion"),
                                        TextEntry::make("applicant.personalInformation.place_of_birth")->columnSpan(2),

                                        TextEntry::make("applicant.personalInformation.resident_address"),
                                        TextEntry::make("applicant.personalInformation.residential_phone"),
                                        TextEntry::make("applicant.personalInformation.residential_zipcode"),
                                        TextEntry::make("applicant.personalInformation.permanent_address"),
                                        TextEntry::make("applicant.personalInformation.permanent_phone"),
                                        TextEntry::make("applicant.personalInformation.permanent_zipcode"),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('Educational Background')
                            ->icon(Heroicon::AcademicCap)
                            ->badge(fn($record) => $record->applicant->educationalBackground()->count())
                            ->schema([

                                RepeatableEntry::make('applicant.educationalBackground')
                                    ->hiddenLabel()
                                    // ->state(function ($record) {
                                    //     if (! $record) return;
                                    //    return $re
                                    // })
                                    ->schema([
                                        TextEntry::make('level')
                                            ->badge(),
                                        TextEntry::make('school')->columnSpan(2)
                                            ->size("md"),
                                        TextEntry::make('degree')->columnSpanFull(),
                                        TextEntry::make('year_from'),
                                        TextEntry::make('year_to'),

                                        TextEntry::make('year_graduated'),
                                        TextEntry::make('units_earned'),
                                        TextEntry::make('honor_received'),
                                    ])->columns(3)
                            ]),
                        Tab::make('Eligibility')
                            ->icon(Heroicon::UserCircle)
                            ->badge(fn($record) => $record->applicant->eligibilityRecords()->count())
                            ->schema([
                                RepeatableEntry::make('applicant.eligibilityRecords')
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('eligibility')
                                            ->badge()
                                            ->color("success"),
                                        TextEntry::make('rating')->columnSpan(2)
                                            ->size("md")
                                            ->weight("bold"),

                                        TextEntry::make('date_of_exam'),
                                        TextEntry::make('place_of_examination'),

                                        TextEntry::make('license_no'),
                                        TextEntry::make('released_date'),
                                        TextEntry::make('date_of_validity'),
                                    ])->columns(3)
                            ]),
                        Tab::make('Work Experience')
                            ->icon(Heroicon::Briefcase)
                            ->badge(fn($record) => $record->applicant->workExperiences()->count())
                            ->schema([
                                RepeatableEntry::make('applicant.workExperiences')
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('position')
                                            ->size("md")
                                            ->weight("bold")
                                            ->columnSpanFull(),
                                        TextEntry::make('date_from')->label("From"),
                                        TextEntry::make('date_to')->label("To"),


                                        TextEntry::make('agency_company'),
                                        TextEntry::make('status_of_appointment'),
                                        IconEntry::make('government_service')->boolean(),
                                    ])->columns(3)
                            ]),
                        Tab::make('Voluntary Work or Involvement')
                            ->icon(Heroicon::ClipboardDocument)
                            ->badge(fn($record) => $record->applicant->voluntaryWork()->count())
                            ->schema([
                                RepeatableEntry::make('applicant.voluntaryWork')
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('position')
                                            ->size("md")
                                            ->weight("bold")
                                            ->columnSpanFull(),
                                        TextEntry::make('date_from')->label("From"),
                                        TextEntry::make('date_to')->label("To"),


                                        TextEntry::make('no_of_hours'),

                                    ])->columns(3)
                            ]),
                        Tab::make('Learning and Development  Interventions (LDI)/Training Programs Attended')
                            ->badge(fn($record) => $record->applicant->trainings()->count())
                            ->icon(Heroicon::BookOpen)
                            ->schema([
                                RepeatableEntry::make('applicant.trainings')
                                    ->hiddenLabel()
                                    ->schema([
                                        TextEntry::make('name_of_training')
                                            ->size("md")
                                            //  ->color("success")
                                            ->weight("bold")
                                            ->columnSpanFull(),
                                        TextEntry::make('date_from')->label("From"),
                                        TextEntry::make('date_to')->label("To"),


                                        TextEntry::make('no_of_hours'),
                                        TextEntry::make('type_of_training'),
                                        TextEntry::make('sponsored_by'),
                                    ])->columns(3)
                            ])
                    ])->columnSpan(3),


            ])->columns(6);
    }
}
