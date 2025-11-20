<?php

namespace App\Filament\Resources\JobPosts\Schemas;

use Carbon\Carbon;
use Filament\Forms\Components\Repeater\TableColumn as RepeaterTableColumn;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group as ComponentsGroup;
use Filament\Schemas\Components\Section;


use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Schemas\Components\Grid;
use Filament\Support\Icons\Heroicon;
use Filament\Infolists\Components\IconEntry;
use Filament\Support\Enums\Size;

class JobPostsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make("Job Post Information")
                    ->description("Basic information about the job posting")
                    ->schema([
                        TextEntry::make('Status')

                            ->weight('semibold')
                            ->badge()

                            ->color(fn($state) => match ($state) {
                                'Published' => 'success',
                                'Filing Closed'    => 'warning',
                                'Unpublished'     => 'danger',
                                default     => 'gray',
                            })
                            ->state(function ($record) {
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
                            })->columnSpan(2),


                        TextEntry::make('title')
                            ->weight('bold')
                            ->size("lg"),


                        ComponentsGroup::make()
                            ->schema([
                                TextEntry::make('Published_date')->label('Published Date')
                                    ->state(function ($record) {

                                        if (! $record) return;
                                        $date = optional($record->published->first())->published_date;
                                        if ($date) {
                                            return Carbon::parse($date)->format('F j, Y g:i A');
                                        }
                                    }),

                                TextEntry::make('closing_date')->label('Closing Date')
                                    ->state(function ($record) {

                                        if (! $record) return;
                                        $date = optional($record->published->first())->closing_date . " " . optional($record->published->first())->closing_time;
                                        if ($date) {
                                            return Carbon::parse($date)->format('F j, Y g:i A');
                                        }
                                    }),


                            ])->columns(2)->columnSpan(1),


                        TextEntry::make('description'),


                        ComponentsGroup::make()
                            ->schema([
                                TextEntry::make('Salary Grade')
                                    ->badge()

                                    ->state(function ($record) {

                                        if (! $record) return;
                                        $grade = optional($record->plantilla->first())->salary_grade;
                                        if ($grade) {
                                            return "SG " . $grade;
                                        }
                                    }),
                                TextEntry::make('Monthly Salary')
                                    ->weight('bold')
                                    ->size("md")
                                    ->state(function ($record) {
                                        if (! $record) return;
                                        $salary = optional($record->plantilla->first())->salary;
                                        if ($salary) {
                                            return "₱ " . number_format($salary, 2);
                                        }
                                    }),
                            ])
                            ->columns(2),



                        TextEntry::make('Place of Assignment')
                            ->weight('semibold')
                            ->state(function ($record) {
                                if (! $record) return;
                                $assignment = optional($record->status->first())->place_of_assignment;
                                if ($assignment) {
                                    return $assignment;
                                }
                            }),
                        TextEntry::make('Employment Type')
                            ->weight('semibold')
                            ->state(function ($record) {
                                return $record->plantilla->first()->is_contract ? 'Contractual/Job Order' : 'Permanent/Regular';
                            }),



                    ])->columns(2)->columnSpan(2),



                ComponentsGroup::make()
                    ->schema([
                        Section::make(fn($record) => !$record->plantilla->first()?->is_contract ? "Plantilla Information" : null)
                            ->description(fn($record) => !$record->plantilla->first()?->is_contract ? "Information about the plantilla position" : null)
                            ->schema([
                                RepeatableEntry::make('requirements')
                                    ->hiddenLabel()
                                    ->label('Requirements')
                                    ->state(
                                        fn($record) =>
                                        $record?->plantilla?->map(fn($item, $key) => [
                                            'id' => $item->id,
                                            'plantilla' => $item->plantilla_no,
                                            'is_contract' => $item->is_contract,
                                            'index' => $key + 1
                                        ])->toArray() ?? []
                                    )
                                    ->schema([

                                        TextEntry::make('plantilla')
                                            ->hiddenLabel()
                                            ->live()
                                            ->default(''),
                                        TextEntry::make('is_contract')
                                            ->label('Type')
                                            ->hiddenLabel()
                                            ->badge()
                                            ->color(fn($state) => $state === 1 ? 'warning' : 'success')
                                            ->formatStateUsing(fn($state) => $state === 1 ? 'Contractual/Job Order' : 'Regular/Permanent'),

                                    ])
                                    ->alignJustify('center')
                                    ->contained(true)
                                    ->grid(3)



                            ]),



                    ])->columnSpan(2),





                Section::make("Job Qualifications")
                    ->schema([
                        TextEntry::make('Educational Background')
                            ->state(function ($record) {
                                if (! $record) return;
                                $background = optional($record->qualifications)->educational_background;
                                if ($background) {
                                    return $background;
                                }
                            }),
                        TextEntry::make('Qualifications')
                            ->state(function ($record) {
                                if (! $record) return;
                                $qualifications = optional($record->qualifications)->qualification;
                                if ($qualifications) {
                                    return $qualifications;
                                }
                            }),
                        TextEntry::make('Experience')
                            ->state(function ($record) {
                                if (! $record) return;
                                $experience = optional($record->qualifications)->experience;
                                if ($experience) {
                                    return $experience;
                                }
                            }),
                        TextEntry::make('Competencies')
                            ->markdown()
                            ->state(function ($record) {
                                return optional($record->qualifications)->competencies;
                            }),
                        TextEntry::make('Skills')
                            ->state(function ($record) {
                                if (! $record) return;
                                $skills = optional($record->qualifications)->skills;
                                if ($skills) {
                                    return $skills;
                                }
                            }),
                        TextEntry::make('Trainings')
                            ->state(function ($record) {
                                if (! $record) return;
                                $trainings = optional($record->qualifications)->trainings;
                                if ($trainings) {
                                    return $trainings;
                                }
                            }),
                    ])->columns(2)->columnSpan(2),

                Section::make("File Information")
                    ->description("File requirement information")
                    ->schema([
                        RepeatableEntry::make('File')

                            ->label('File Requirements')
                            ->state(function ($record) {
                                if (! $record) return;
                                $data = $record->required_files?->map(function ($item) {
                                    return [
                                        'id' => $item->id,
                                        'file_type' => $item->file_type,
                                        'file_name' => $item->file_name,
                                    ];
                                })->toArray() ?? [];

                                return $data;
                            })
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('file_type')->label('File Type'),
                                        TextEntry::make('file_name')->label('File Name')
                                            ->icon(Heroicon::PaperClip),
                                    ]),
                            ])->grid(3)


                    ])->columnSpan(2)
            ])->columns(2);
    }
}
