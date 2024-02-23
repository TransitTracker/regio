<?php

namespace App\Filament\Resources\Gtfs;

use App\Filament\Resources\Gtfs\TripResource\Pages;
use App\Filament\Resources\Gtfs\TripResource\RelationManagers;
use App\Models\Gtfs\Route;
use App\Models\Gtfs\Trip;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Position;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TripResource extends Resource
{
    protected static ?string $model = Trip::class;

    protected static ?string $navigationIcon = 'gmdi-view-timeline';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('agency_id')
                    ->relationship('agency', 'agency_name')
                    ->reactive()
                    ->columnSpanFull()
                    ->required()
                    ->columnSpan(2),
                Forms\Components\Select::make('route_id')
                    ->relationship('route', 'route_long_name')
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn (Route $record) => "{$record->route_id}: {$record->route_short_name} {$record->route_long_name}")
                    ->required(),
                Forms\Components\TextInput::make('trip_headsign')
                    ->maxLength(255),
                Forms\Components\TextInput::make('trip_short_name')
                    ->maxLength(255),
                Forms\Components\Toggle::make('direction_id')->label('Travel in the opposite direction'),
                Forms\Components\TextInput::make('block_id')
                    ->maxLength(255),
                Forms\Components\Select::make('shape_id')
                    ->relationship('shape', 'shape_id'),
                Forms\Components\Toggle::make('wheelchair_accessible'),
                Forms\Components\Toggle::make('bikes_allowed'),
                Forms\Components\Fieldset::make('Service')
                    ->relationship('service')
                    ->schema([
                        Forms\Components\Toggle::make('monday')->inline(false),
                        Forms\Components\Toggle::make('tuesday')->inline(false),
                        Forms\Components\Toggle::make('wednesday')->inline(false),
                        Forms\Components\Toggle::make('thursday')->inline(false),
                        Forms\Components\Toggle::make('friday')->inline(false),
                        Forms\Components\Toggle::make('saturday')->inline(false),
                        Forms\Components\Toggle::make('sunday')->inline(false),
                        Forms\Components\TextInput::make('start_date')
                            ->type('date')
                            ->required()
                            ->default(now()->startOfMonth()->toDateString())
                            ->columnSpan(3),
                        Forms\Components\TextInput::make('end_date')
                            ->type('date')
                            ->required()
                            ->default(now()->addYear()->endOfYear()->toDateString())
                            ->columnSpan(3),
                        Forms\Components\TextInput::make('service_id')
                            ->disabled()
                            ->label('Service ID'),
                        Forms\Components\TextInput::make('agency_id')
                            ->default(fn (\Filament\Forms\Get $get) => $get('../../agency_id'))
                            ->hidden(),
                    ])
                    ->columns(7),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('route_id'),
                Tables\Columns\TextColumn::make('service_id'),
                Tables\Columns\TextColumn::make('trip_headsign'),
                Tables\Columns\TextColumn::make('trip_short_name'),
                Tables\Columns\IconColumn::make('direction_id')
                    ->boolean(),
                Tables\Columns\TextColumn::make('block_id'),
                Tables\Columns\TextColumn::make('shape_id'),
                Tables\Columns\IconColumn::make('wheelchair_accessible')
                    ->boolean(),
                Tables\Columns\IconColumn::make('bikes_allowed')
                    ->boolean(),
                Tables\Columns\TextColumn::make('agency_id'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ReplicateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->actionsPosition(Position::BeforeCells);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\StopTimesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrips::route('/'),
            'create' => Pages\CreateTrip::route('/create'),
            'edit' => Pages\EditTrip::route('/{record}/edit'),
        ];
    }
}
