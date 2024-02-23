<?php

namespace App\Filament\Resources\Gtfs;

use App\Filament\Resources\Gtfs\CalendarResource\Pages;
use App\Filament\Resources\Gtfs\CalendarResource\RelationManagers;
use App\Models\Gtfs\Calendar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CalendarResource extends Resource
{
    protected static ?string $model = Calendar::class;

    protected static ?string $navigationIcon = 'gmdi-calendar-month';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('agency_id')
                    ->required()
                    ->relationship('agency', 'agency_name')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('monday'),
                Forms\Components\Toggle::make('tuesday'),
                Forms\Components\Toggle::make('wednesday'),
                Forms\Components\Toggle::make('thursday'),
                Forms\Components\Toggle::make('friday'),
                Forms\Components\Toggle::make('saturday'),
                Forms\Components\Toggle::make('sunday'),
                Forms\Components\DatePicker::make('start_date')
                    ->required()->columnSpan(2),
                Forms\Components\DatePicker::make('end_date')
                    ->required()->columnSpan(2),
                Forms\Components\TextInput::make('service_description')->columnSpanFull(),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('monday')
                    ->boolean(),
                Tables\Columns\IconColumn::make('tuesday')
                    ->boolean(),
                Tables\Columns\IconColumn::make('wednesday')
                    ->boolean(),
                Tables\Columns\IconColumn::make('thursday')
                    ->boolean(),
                Tables\Columns\IconColumn::make('friday')
                    ->boolean(),
                Tables\Columns\IconColumn::make('saturday')
                    ->boolean(),
                Tables\Columns\IconColumn::make('sunday')
                    ->boolean(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date(),
                Tables\Columns\TextColumn::make('agency_id'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('trips_count')
                    ->counts('trips'),
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
            RelationManagers\TripsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCalendars::route('/'),
            'create' => Pages\CreateCalendar::route('/create'),
            'edit' => Pages\EditCalendar::route('/{record}/edit'),
        ];
    }
}
