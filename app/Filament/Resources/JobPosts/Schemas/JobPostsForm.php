<?php

namespace App\Filament\Resources\JobPosts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Box;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;

class JobPostsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Create New Job Posting")
                    ->description("Fill in the details of the job posting.")
                    ->schema([
                        Group::make()
                            ->schema([
                                TextInput::make('title')->label("Job Title")->required()->columnSpan(3)->autofocus(),
                                Group::make()
                                    ->schema([
                                        TextInput::make('monthly_salary')->label("Monthly Salary")->required()->numeric()->prefix("₱"),
                                        Select::make('salary_grade')
                                            ->label('Salary Grade')
                                            ->required()
                                            ->options(
                                                collect(range(1, 33))->mapWithKeys(
                                                    fn($grade) => [$grade => "SG $grade"]
                                                )->toArray()
                                            )
                                            ->placeholder('Select salary grade')->searchable(),
                                        TextInput::make('no_of_vacancies')->label("Number of Vacancies")->required()->numeric(),
                                        TextInput::make('place_of_assignment')->label("Place of Assignment")->required(),
                                    ])->columns(2)->columnSpan(3),
                            ])->columnSpan(2)->columns(3),

                        Group::make()
                            ->schema([
                                TextInput::make('plantilla_no')->label("Plantilla No.")->columnSpan(2),
                                DatePicker::make('publication_date')->label("Publication Date"),
                                DateTimePicker::make('closing_date')->label("Closing Date Time"),
                                TextInput::make('max_applicants')->label("Max Applicants")->numeric()->columnSpan(2),

                            ])->columns(2),


                        MarkdownEditor::make('description')->columnSpan(3),
                    ])->columnSpan(2)->columns(3)->collapsible(),
                Section::make("Job Qualifications")
                    ->description("Fill in the job qualifications.")
                    ->schema([

                        MarkdownEditor::make('educational_background')->label("Educational Background"),
                        MarkdownEditor::make('qualification')->label("Qualification"),
                        MarkdownEditor::make('experience')->label("Experience"),
                        MarkdownEditor::make('competencies')->label("Competencies"),
                        MarkdownEditor::make('skills')->label("Skills"),
                        MarkdownEditor::make('trainings')->label("Trainings"),


                        // ...
                    ])->columnSpan(2)->collapsible(),

                Section::make("File Upload")
                    ->description("Required files for the job posting.")
                    ->schema([
                        KeyValue::make("file_requirements")
                            ->keyLabel("File Type")
                            ->valueLabel("File Naming")
                            ->addActionLabel('Add requirement')
                            ->columns(2)
                            ->columnSpan(2)
                            ->required(),
                    ])->columnSpan(2),
            ])->columns(2);
    }
}
