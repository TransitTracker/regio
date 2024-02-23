<?php

namespace App\Filament\Resources\Gtfs\TripResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StopTimesRelationManager extends RelationManager
{
    protected static string $relationship = 'stopTimes';

    protected static ?string $recordTitleAttribute = 'stop_sequence';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('stop_id')
                    ->relationship('stop', 'stop_name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('arrival_time')
                    ->label('Arrival')
                    ->type('time')
                    ->required(),
                Forms\Components\TextInput::make('departure_time')
                    ->label('Departure')
                    ->type('time'),
                Forms\Components\Select::make('pickup_type')
                    ->options([
                        0 => 'Regular',
                        1 => 'No pickup',
                        2 => 'Must phone agency',
                        2 => 'Must coordinate with driver',
                    ])
                    ->default(0)
                    ->required(),
                Forms\Components\Select::make('drop_off_type')
                    ->options([
                        0 => 'Regular',
                        1 => 'No drop off',
                        2 => 'Must phone agency',
                        2 => 'Must coordinate with driver',
                    ])
                    ->default(0)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->columns([
                Tables\Columns\TextColumn::make('stop.stop_name'),
                Tables\Columns\TextInputColumn::make('arrival_time')
                    ->type('time')
                    ->label('Arrival'),
                Tables\Columns\TextInputColumn::make('departure_time')
                    ->type('time')
                    ->label('Departure'),
                Tables\Columns\SelectColumn::make('pickup_type')
                    ->options([
                        0 => 'Regular',
                        1 => 'No pickup',
                        2 => 'Must phone agency',
                        2 => 'Must coordinate with driver',
                    ])
                    ->rules(['required']),
                Tables\Columns\SelectColumn::make('drop_off_type')
                    ->options([
                        0 => 'Regular',
                        1 => 'No drop off',
                        2 => 'Must phone agency',
                        2 => 'Must coordinate with driver',
                    ])
                    ->rules(['required']),
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
            ])
            ->reorderable('stop_sequence')
            ->paginated([25, 50, 100]);
    }
}
