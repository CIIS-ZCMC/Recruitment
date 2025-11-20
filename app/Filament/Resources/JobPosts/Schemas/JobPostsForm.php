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
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Schemas\Components\Text;
use Filament\Tables\Columns\TextColumn;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\Width;

class JobPostsForm
{
    public static function configure(Schema $schema): Schema
    {
        $operation =  $schema->getOperation();

        $title = $operation == "create" ? "Create" : "Update";
        $description = "Fill in the details of the job posting.";

        return $schema
            ->components([
                Section::make("{$title} Job Posting")
                    ->description($description)
                    ->schema([
                        Group::make()
                            ->schema([
                                Group::make()
                                    ->schema([
                                        Group::make()
                                            ->schema([
                                                TextInput::make('title')->label("Job Title")->required()->autofocus()->columnSpan(3),
                                                DatePicker::make('publication_date')->label("Publication Date")->required()->columnSpan(1)
                                                    ->afterStateHydrated(function ($component, $state, $record) {
                                                        if (! $record) return;
                                                        $date = optional($record->published->first())->published_date;
                                                        if ($date) {
                                                            $component->state($date);
                                                        }
                                                    }),
                                                DateTimePicker::make('closing_date')->label("Closing Date Time")->required()->columnSpan(1)
                                                    ->afterStateHydrated(function ($component, $state, $record) {
                                                        if (! $record) return;
                                                        $date = optional($record->published->first())->closing_date . " " . optional($record->published->first())->closing_time;
                                                        if ($date) {
                                                            $component->state($date);
                                                        }
                                                    }),
                                                TextInput::make('max_applicants')->label("Max Applicants ( Optional )")->numeric()->columnSpan(1)
                                                    ->prefix("Limit")
                                                    ->afterStateHydrated(function ($component, $state, $record) {
                                                        if (! $record) return;
                                                        $date = optional($record->published->first())->max_applicants;
                                                        if ($date) {
                                                            $component->state($date);
                                                        }
                                                    }),
                                            ])->columns(3)->columnSpan(2),
                                        Group::make()
                                            ->schema([

                                                Select::make('salary_grade')
                                                    ->label('Salary Grade')
                                                    ->required()
                                                    ->options(
                                                        collect(range(1, 33))->mapWithKeys(
                                                            fn($grade) => [$grade => "SG $grade"]
                                                        )->toArray()
                                                    )
                                                    ->placeholder('Select salary grade')->searchable()
                                                    ->afterStateHydrated(function ($component, $state, $record) {
                                                        if (! $record) return;
                                                        $data = optional($record->plantilla->first())->salary_grade;
                                                        if ($data) {
                                                            $component->state($data);
                                                        }
                                                    }),
                                                TextInput::make('salary')->label("Monthly Salary")->required()->numeric()->prefix("₱")
                                                    ->columnSpan(2)
                                                    ->afterStateHydrated(function ($component, $state, $record) {
                                                        if (! $record) return;
                                                        $data = optional($record->plantilla->first())->salary;
                                                        if ($data) {
                                                            $component->state($data);
                                                        }
                                                    }),


                                                TextInput::make('place_of_assignment')->label("Place of Assignment")->required()
                                                    ->columnSpan(3)
                                                    ->afterStateHydrated(function ($component, $state, $record) {
                                                        if (! $record) return;
                                                        $data = optional($record->status->first())->place_of_assignment;
                                                        if ($data) {
                                                            $component->state($data);
                                                        }
                                                    }),

                                            ])->columnSpan(2)->columns(3),
                                    ])
                                    ->columnSpan(2),
                                MarkdownEditor::make('description')->columnSpan(1),


                            ])->columns(3)->columnSpan(3),

                        Checkbox::make('is_contract')
                            ->label("Is Contract of Service or Job Order")
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if (! $record) return;
                                $component->state($record->plantilla->first()->is_contract);
                            })
                            ->live()
                            ->columnSpan(1),

                        TextInput::make('no_of_vacancies')->label("Number of Vacancies")
                            ->required()
                            ->numeric()
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if (! $record) return;
                                $component->state($record->plantilla->count());
                            })
                            ->hidden(fn(Get $get) => !$get('is_contract'))
                            ->maxWidth(Width::ExtraSmall)
                            ->columnSpan(3),

                        Section::make()
                            ->description("⚠️ Skipped if position is Contract of Service or Job Order.")
                            ->hidden(fn(Get $get) => $get('is_contract'))
                            ->schema([

                                Repeater::make('plantilla')
                                    ->label("Manage Plantilla")
                                    ->addActionLabel("Add Plantilla")
                                    ->default([])
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if (! $record) return;
                                        $data = $record->plantilla?->map(function ($item) {
                                            return [
                                                'id' => $item->id,
                                                'plantilla' => $item->plantilla_no,
                                            ];
                                        })->toArray() ?? [];

                                        $component->state($data);
                                    })

                                    ->schema([
                                        TextInput::make('plantilla')->label("Plantilla")->required()->columnSpan(1),
                                    ])
                            ])
                            ->columnSpan(3)
                    ])->columnSpan(2)->columns(3)->collapsible(),
                Section::make("Job Qualifications")
                    ->description("Fill in the job qualifications.")
                    ->schema([

                        MarkdownEditor::make('educational_background')->label("Educational Background")
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if (! $record) return;


                                $data = optional($record->qualifications)->educational_background;
                                if ($data) {
                                    $component->state($data);
                                }
                            }),
                        MarkdownEditor::make('qualification')->label("Qualification")
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if (! $record) return;
                                $data = optional($record->qualifications)->qualification;
                                if ($data) {
                                    $component->state($data);
                                }
                            }),
                        MarkdownEditor::make('experience')->label("Experience")
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if (! $record) return;
                                $data = optional($record->qualifications)->experience;
                                if ($data) {
                                    $component->state($data);
                                }
                            }),
                        MarkdownEditor::make('competencies')->label("Competencies")
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if (! $record) return;
                                $data = optional($record->qualifications)->competencies;
                                if ($data) {
                                    $component->state($data);
                                }
                            }),
                        MarkdownEditor::make('skills')->label("Skills")
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if (! $record) return;
                                $data = optional($record->qualifications)->skills;
                                if ($data) {
                                    $component->state($data);
                                }
                            }),
                        MarkdownEditor::make('trainings')->label("Trainings")
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if (! $record) return;
                                $data = optional($record->qualifications)->trainings;
                                if ($data) {
                                    $component->state($data);
                                }
                            }),


                        // ...
                    ])->columnSpan(2)->collapsible(),

                Section::make("File Upload")
                    ->description("Required files for the job posting.")

                    ->schema([
                        Repeater::make('file_requirements')
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if (! $record) return;
                                $data = $record->required_files?->map(function ($item) {
                                    return [
                                        'id' => $item->id,
                                        'file_type' => $item->file_type,
                                        'file_name' => $item->file_name,
                                    ];
                                })->toArray() ?? [];

                                $component->state($data);
                            })
                            ->table([
                                TableColumn::make('File Type'),
                                TableColumn::make('File Name'),
                            ])
                            ->schema([
                                TextInput::make('file_type')
                                    ->required(),
                                TextInput::make('file_name')
                                    ->required(),
                            ])
                    ])->columnSpan(2),
            ])->columns(2);
    }
}
