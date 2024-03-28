<?php

namespace App\Filament\Resources\Gtfs;

use App\Filament\Resources\Gtfs\TimetablePageResource\Pages;
use App\Filament\Resources\Gtfs\TimetablePageResource\RelationManagers;
use App\Filament\Resources\TimetablePageResource\RelationManagers\TimetablesRelationManager;
use App\Models\Gtfs\TimetablePage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TimetablePageResource extends Resource
{
    protected static ?string $model = TimetablePage::class;

    protected static ?string $navigationGroup = 'GTFS Creator';
    protected static ?string $navigationParentItem = 'Timetables';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('timetable_page_label')
                    ->hint('Optional')
                    ->maxLength(255),
                Forms\Components\TextInput::make('filename')
                    ->hint('Optional')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('timetable_page_label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('filename')
                    ->searchable(),
                Tables\Columns\TextColumn::make('timetables_count')->counts('timetables'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn (TimetablePage $record) => $record->timetables_count),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTimetablePages::route('/'),
        ];
    }

    public static function getRelations(): array
    {
        return [
        ];
    }
}
