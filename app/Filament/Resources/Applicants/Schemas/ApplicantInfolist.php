<?php

namespace App\Filament\Resources\Applicants\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

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

                Section::make("Educational Background")
                    ->description("")
                    ->schema([])->columnSpanFull(),
                Section::make("Eligibility")
                    ->description("")
                    ->schema([])->columnSpanFull(),
                Section::make("Work Experience")
                    ->description("")
                    ->schema([])->columnSpanFull(),

                Section::make("Voluntary Work or Involvement")
                    ->description("")
                    ->schema([])->columnSpanFull(),
                Section::make("Learning and Development Interventions (LDI)/Training Programs Attended")
                    ->description("")
                    ->schema([])->columnSpanFull(),
            ])->columns(3);
    }
}
