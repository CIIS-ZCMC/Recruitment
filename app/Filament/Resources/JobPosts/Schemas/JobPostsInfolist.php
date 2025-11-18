<?php

namespace App\Filament\Resources\JobPosts\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;

class JobPostsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('description'),
            ]);
    }
}
