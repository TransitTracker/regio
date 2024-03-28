<?php

namespace App\Filament\Resources\Gtfs;

use App\Enums\TimetableOrientation;
use App\Filament\Resources\Gtfs\TimetableResource\Pages;
use App\Filament\Resources\Gtfs\TimetableResource\RelationManagers;
use App\Models\Gtfs\Route;
use App\Models\Gtfs\Timetable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TimetableResource extends Resource
{
    protected static ?string $model = Timetable::class;

    protected static ?string $navigationIcon = 'mdi-timetable';
    protected static ?string $navigationGroup = 'GTFS Creator';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('route_id')
                    ->relationship('route', 'route_id')
                    ->getOptionLabelFromRecordUsing(fn (Route $record) => "{$record->route_short_name} {$record->route_long_name}")
                    ->required(),
                Forms\Components\ToggleButtons::make('direction_id')
                    ->options([
                        null => 'Both',
                        false => 'Inbound',
                        true => 'Outbound',
                    ])
                    ->icons([
                        null => 'mdi-arrow-left-right',
                        false => 'mdi-arrow-right',
                        true => 'mdi-arrow-left',
                    ])
                    ->grouped(),
                Forms\Components\Select::make('timetable_page_id')
                    ->relationship('timetablePage', 'timetable_page_id')
                    ->helperText('Use to regroup timetables together.'),
                Forms\Components\Section::make('Application period')
                    ->collapsed()
                    ->columns(14)
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->columnSpan(7),
                        Forms\Components\DatePicker::make('end_date')
                            ->columnSpan(7),
                        Forms\Components\TimePicker::make('start_time')
                            ->columnSpan(7)
                            ->seconds(false),
                        Forms\Components\TimePicker::make('end_time')
                            ->columnSpan(7)
                            ->seconds(false),
                        Forms\Components\Toggle::make('monday')
                            ->required()
                            ->columnSpan(2)
                            ->inline(false),
                        Forms\Components\Toggle::make('tuesday')
                            ->required()
                            ->columnSpan(2)
                            ->inline(false),
                        Forms\Components\Toggle::make('wednesday')
                            ->required()
                            ->columnSpan(2)
                            ->inline(false),
                        Forms\Components\Toggle::make('thursday')
                            ->required()
                            ->columnSpan(2)
                            ->inline(false),
                        Forms\Components\Toggle::make('friday')
                            ->required()
                            ->columnSpan(2)
                            ->inline(false),
                        Forms\Components\Toggle::make('saturday')
                            ->required()
                            ->columnSpan(2)
                            ->inline(false),
                        Forms\Components\Toggle::make('sunday')
                            ->required()
                            ->columnSpan(2)
                            ->inline(false),
                    ]),
                Forms\Components\Section::make('Display')
                    ->collapsed()
                    ->description('All these fields are optional. If unsure, leave empty.')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('direction_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('timetable_label')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('service_notes')
                            ->maxLength(255),
                        Forms\Components\Select::make('orientation')
                            ->options(TimetableOrientation::class)
                            ->default(TimetableOrientation::Horizontal),
                        Forms\Components\TextInput::make('timetable_sequence')
                            ->helperText('Used to sort timetables on a page')
                            ->integer()
                            ->minValue(0),
                        Forms\Components\Toggle::make('include_exceptions'),
                        Forms\Components\Toggle::make('show_trip_continuation'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('route.route_name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('direction_name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('direction_id')
                    ->label('Direction')
                    ->boolean()
                    ->trueIcon('mdi-arrow-left')
                    ->trueColor('primary')
                    ->falseIcon('mdi-arrow-right')
                    ->falseColor('primary'),
                Tables\Columns\TextColumn::make('timetable_label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('service_notes')
                    ->searchable(),
                Tables\Columns\TextColumn::make('service_pattern')
                    ->fontFamily(FontFamily::Mono),
                Tables\Columns\TextColumn::make('timetablePage.filename')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTimetables::route('/'),
        ];
    }
}
