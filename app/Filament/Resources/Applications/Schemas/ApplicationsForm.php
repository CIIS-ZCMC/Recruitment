<?php

namespace App\Filament\Resources\Applications\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ApplicationsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('applicant_id')
                    ->required()
                    ->numeric(),
                TextInput::make('published_job_post_id')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'approved' => 'Approved',
            'shortlisted' => 'Shortlisted',
            'interviewed' => 'Interviewed',
            'recommended' => 'Recommended',
            'hired' => 'Hired',
            'onboarded' => 'Onboarded',
            'failed_interview' => 'Failed interview',
            'not_qualified' => 'Not qualified',
            'rejected' => 'Rejected',
            'not_selected' => 'Not selected',
            'withdrawn' => 'Withdrawn',
            'no_show' => 'No show',
        ])
                    ->default('pending')
                    ->required(),
                Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }
}
