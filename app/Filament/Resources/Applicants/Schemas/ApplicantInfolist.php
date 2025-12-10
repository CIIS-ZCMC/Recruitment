<?php

namespace App\Filament\Resources\Applicants\Schemas;


use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;

class ApplicantInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make("Contact Information")
                            ->description("Phone number and email address")
                            ->schema([
                                TextEntry::make("email")->icon(Heroicon::Envelope),
                                TextEntry::make("phone")->icon(Heroicon::Phone)
                            ])
                    ])->columnSpan(1),
                Group::make()
                    ->schema([
                        Section::make("Personal Information")
                            ->description("Applicant personal information")
                            ->schema([
                                TextEntry::make("Full Name")
                                    ->label("Full Name")
                                    ->color('warning')
                                    ->size("lg")
                                    ->state(fn($record) => $record->personalInformation->fullName())->columnSpan(3),
                                TextEntry::make("personalInformation.date_of_birth"),
                                TextEntry::make("personalInformation.sex"),
                                TextEntry::make("personalInformation.blood_type"),
                                TextEntry::make("personalInformation.religion"),
                                TextEntry::make("personalInformation.place_of_birth")->columnSpan(2),


                                Section::make('')
                                    ->schema([])
                                    ->columnSpanFull()
                                    ->extraAttributes(['class' => 'py-4']),

                                TextEntry::make("personalInformation.resident_address"),
                                TextEntry::make("personalInformation.residential_phone"),
                                TextEntry::make("personalInformation.residential_zipcode"),
                                TextEntry::make("personalInformation.permanent_address"),
                                TextEntry::make("personalInformation.permanent_phone"),
                                TextEntry::make("personalInformation.permanent_zipcode"),
                            ])->columns(3)
                    ])->columnSpan(2),

                Tabs::make('Tabs')

                    ->tabs([
                        Tab::make('Educational Background')
                            ->icon(Heroicon::AcademicCap)
                            ->badge(fn($record) => $record->educationalBackground()->count())
                            ->schema([

                                RepeatableEntry::make('educationalBackground')
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
                            ->badge(fn($record) => $record->eligibilityRecords()->count())
                            ->schema([
                                RepeatableEntry::make('eligibilityRecords')
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
                            ->badge(fn($record) => $record->workExperiences()->count())
                            ->schema([
                                RepeatableEntry::make('workExperiences')
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
                            ->badge(fn($record) => $record->voluntaryWork()->count())
                            ->schema([
                                RepeatableEntry::make('voluntaryWork')
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
                            ->badge(fn($record) => $record->trainings()->count())
                            ->icon(Heroicon::BookOpen)
                            ->schema([
                                RepeatableEntry::make('trainings')
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
                            ]),
                    ])

                    ->columnSpanFull()

                    ->vertical()
                    ->persistTab(),

            ])->columns(3);
    }
}
