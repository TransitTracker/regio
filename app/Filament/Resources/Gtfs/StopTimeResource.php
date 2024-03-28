<?php

namespace App\Filament\Resources\Gtfs;

use App\Filament\Resources\Gtfs\StopTimeResource\Pages;
use App\Filament\Resources\Gtfs\StopTimeResource\RelationManagers;
use App\Models\Gtfs\StopTime;
use App\Models\Gtfs\Trip;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StopTimeResource extends Resource
{
    protected static ?string $model = StopTime::class;

    protected static ?string $navigationIcon = 'gmdi-departure-board';
    protected static ?string $navigationGroup = 'GTFS Creator';
    protected static ?string $navigationParentItem = 'Trips';
    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stop_sequence'),
                Tables\Columns\TextColumn::make('arrival_time'),
                Tables\Columns\TextColumn::make('departure_time'),
                Tables\Columns\TextColumn::make('stop.stop_name'),
                Tables\Columns\TextColumn::make('pickup_type'),
                Tables\Columns\TextColumn::make('drop_off_type'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('Edit trip')),
            ])
            ->defaultGroup('trip_id')
            ->groups([
                Tables\Grouping\Group::make('trip_id')
                    ->label('Trip')
                    ->getTitleFromRecordUsing(fn (StopTime $record): string => "{$record->trip_id} {$record->trip->trip_headsign} ({$record->agency->agency_name})")
                    ->getDescriptionFromRecordUsing(fn (StopTime $record): string => "{$record->trip->route->route_short_name} {$record->trip->route->route_long_name}")
            ])
            ->recordUrl(
                fn (StopTime $record): string => route('filament.admin.resources.gtfs.trips.edit', ['record' => $record->trip]),
            );
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
            'index' => Pages\ListStopTimes::route('/'),
        ];
    }
}
