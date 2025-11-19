<?php

namespace App\Filament\Resources\JobPosts\Pages;

use App\Filament\Resources\JobPosts\JobPostsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

class ListJobPosts extends ListRecords
{
    protected static string $resource = JobPostsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon(Heroicon::Plus),

        ];
    }
}
