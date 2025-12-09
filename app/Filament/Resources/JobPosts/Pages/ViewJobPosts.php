<?php

namespace App\Filament\Resources\JobPosts\Pages;

use App\Filament\Resources\JobPosts\JobPostsResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;

class ViewJobPosts extends ViewRecord
{
    protected static string $resource = JobPostsResource::class;

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
            EditAction::make(),
        ];
    }
}
