<?php

namespace App\Filament\Resources\Gtfs;

use App\Filament\Resources\Gtfs\FrequencyResource\Pages;
use App\Filament\Resources\Gtfs\FrequencyResource\RelationManagers;
use App\Models\Gtfs\Frequency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FrequencyResource extends Resource
{
    protected static ?string $model = Frequency::class;

    protected static ?string $navigationIcon = 'gmdi-schedule';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('agency_id')
                    ->relationship('agency', 'agency_name')
                    ->required(),
                Forms\Components\Select::make('trip_id')
                    ->relationship('trip', 'trip_headsign')
                    ->required(),
                Forms\Components\TextInput::make('start_time')
                    ->type('time')
                    ->required(),
                Forms\Components\TextInput::make('end_time')
                    ->type('time')
                    ->required(),
                Forms\Components\TextInput::make('headway_secs')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('exact_times'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_time'),
                Tables\Columns\TextColumn::make('headway_secs'),
                Tables\Columns\IconColumn::make('exact_times')
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
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
            'index' => Pages\ListFrequencies::route('/'),
            'create' => Pages\CreateFrequency::route('/create'),
            'edit' => Pages\EditFrequency::route('/{record}/edit'),
        ];
    }
}
