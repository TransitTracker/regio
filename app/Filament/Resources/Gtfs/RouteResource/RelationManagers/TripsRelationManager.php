<?php

namespace App\Filament\Resources\Gtfs\RouteResource\RelationManagers;

use App\Filament\Resources\Gtfs\TripResource;
use App\Models\Gtfs\Trip;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontFamily;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TripsRelationManager extends RelationManager
{
    protected static string $relationship = 'trips';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('trip_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('trip_id')
            ->columns([
                Tables\Columns\TextColumn::make('trip_short_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('trip_headsign')
                    ->sortable(),
                Tables\Columns\IconColumn::make('direction_id')
                    ->sortable()
                    ->boolean()
                    ->trueIcon('mdi-arrow-left')
                    ->trueColor('primary')
                    ->falseIcon('mdi-arrow-right')
                    ->falseColor('primary'),
                Tables\Columns\TextColumn::make('service.service_pattern')
                    ->sortable()
                    ->fontFamily(FontFamily::Mono),
                Tables\Columns\TextColumn::make('firstDeparture.arrival_time')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('direction_id')
                    ->falseLabel('Inbound')
                    ->trueLabel('Outbound')
                    ->placeholder('Both directions'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->url(fn () => TripResource::getUrl('create')),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->url(fn (Trip $record) => TripResource::getUrl('edit', [$record]))
                    ->icon('mdi-pencil'),
            ]);
    }
}
