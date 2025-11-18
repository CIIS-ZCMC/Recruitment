<?php

namespace App\Filament\Resources\JobPosts;

use App\Filament\Resources\JobPosts\Pages\CreateJobPosts;
use App\Filament\Resources\JobPosts\Pages\EditJobPosts;
use App\Filament\Resources\JobPosts\Pages\ListJobPosts;
use App\Filament\Resources\JobPosts\Pages\ViewJobPosts;
use App\Filament\Resources\JobPosts\Schemas\JobPostsForm;
use App\Filament\Resources\JobPosts\Schemas\JobPostsInfolist;
use App\Filament\Resources\JobPosts\Tables\JobPostsTable;
use App\Models\JobPosts;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class JobPostsResource extends Resource
{
    protected static ?string $model = JobPosts::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Briefcase;

    protected static ?string $recordTitleAttribute = 'Job Posts';

    protected static string | UnitEnum | null $navigationGroup = 'Management';


    public static function form(Schema $schema): Schema
    {
        return JobPostsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JobPostsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobPostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobPosts::route('/'),
            'create' => CreateJobPosts::route('/create'),
            'view' => ViewJobPosts::route('/{record}'),
            'edit' => EditJobPosts::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
