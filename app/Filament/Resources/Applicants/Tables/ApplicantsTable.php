<?php

namespace App\Filament\Resources\Applicants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ApplicantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->searchable()
            ->recordUrl(null)
            ->columns([
                IconColumn::make('status')
                    ->boolean(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('Contact Informations')
                    ->getStateUsing(fn($record) => $record->email . " | " . $record->phone),
                TextColumn::make('personalInformation.sex')
                    ->label("Gender")
                    ->searchable(),
                TextColumn::make('Application Filed')
                    ->label("Total Application Filed")
                    ->badge()
                    ->getStateUsing(fn($record) => 3),
            ])
            ->filters([
                Filter::make('is_featured')
                    ->query(fn(Builder $query) => $query->where('is_featured', true)),
                SelectFilter::make('status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                //EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
