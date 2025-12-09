<?php

namespace App\Filament\Resources\Applicants\Pages;

use App\Filament\Resources\Applicants\ApplicantResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ViewApplicant extends ViewRecord
{
    protected static string $resource = ApplicantResource::class;


    public function getHeading(): string|Htmlable
    {
        return "Applicant Details";
    }
    protected function getHeaderActions(): array
    {
        return [
            Action::make('Back')
                ->color('gray')
                ->icon(Heroicon::ArrowLeft)
                ->iconPosition(IconPosition::Before)
                ->extraAttributes([
                    'onclick' => 'history.back()'
                ]),
            Action::make("view_applications")
                ->outlined()
                ->label("View applications")
                ->badge(5)
                ->badgeColor('success')
                ->icon(Heroicon::DocumentText)
        ];
    }
}
