<?php

namespace App\Filament\Resources\Gtfs\RouteResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TimetablePagesRelationManager extends RelationManager
{
    protected static string $relationship = 'timetablePages';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('timetable_page_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('timetable_page_label')
                    ->maxLength(255),
                Forms\Components\TextInput::make('filename')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('timetable_page_id')
            ->columns([
                Tables\Columns\TextColumn::make('timetable_page_id'),
                Tables\Columns\TextColumn::make('timetable_page_label'),
                Tables\Columns\TextColumn::make('filename'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
