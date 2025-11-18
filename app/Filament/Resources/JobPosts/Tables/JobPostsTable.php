<?php

namespace App\Filament\Resources\JobPosts\Tables;

use App\Models\JobPostPlantilla;
use App\Models\JobPosts;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Actions\ActionGroup;

class JobPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime(),
                TextColumn::make('deleted_at')
                    ->dateTime(),
                TextColumn::make('plantilla')
                    ->state(function (JobPosts $record) {
                        return $record->plantilla?->plantilla_no;
                    }),

            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([

                ActionGroup::make([
                    ViewAction::make()->icon('heroicon-o-eye'),
                    EditAction::make()->icon('heroicon-o-pencil'),
                ])

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public function getPlantilla(JobPosts $record)
    {
        return JobPostPlantilla::query()
            ->where('job_post_id', $record->id)
            ->first();
    }
}
