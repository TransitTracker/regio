<?php

namespace App\Filament\Resources\Gtfs\RouteResource\RelationManagers;

use App\Enums\TimetableOrientation;
use App\Models\Gtfs\Route;
use App\Models\Gtfs\Timetable;
use App\Models\Gtfs\TimetablePage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontFamily;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TimetablesRelationManager extends RelationManager
{
    protected static string $relationship = 'timetables';

    public function form(Form $form): Form
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
                        0 => 'Inbound',
                        1 => 'Outbound',
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
                        Forms\Components\TextInput::make('timetable_label')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('service_notes')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('direction_name')
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('timetable_id')
            ->reorderable('timetable_sequence')
            ->columns([
                Tables\Columns\TextColumn::make('timetable_id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('direction_name'),
                Tables\Columns\IconColumn::make('direction_id')
                    ->boolean()
                    ->trueIcon('mdi-arrow-left')
                    ->trueColor('primary')
                    ->falseIcon('mdi-arrow-right')
                    ->falseColor('primary'),
                Tables\Columns\TextColumn::make('service_pattern')
                    ->fontFamily(FontFamily::Mono),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->color('gray'),
                Tables\Actions\Action::make('generateTimetable')
                    ->color('primary')
                    ->form([
                        Forms\Components\ToggleButtons::make('service_types')
                            ->helperText('Choose all applicable service types')
                            ->options([
                                'week' => 'Week (Monday to Friday)',
                                'weekend' => 'Weekend (Saturday and Sunday)',
                                'saturday' => 'Saturday',
                                'sunday' => 'Sunday',
                                'every' => 'Every day (Sunday to Saturday)'
                            ])
                            ->inline()
                            ->multiple()
                            ->required(),
                        Forms\Components\Toggle::make('generate_both_directions')
                            ->default(true)
                            ->required()
                            ->helperText('Recommended to generate both directions'),
                    ])
                    ->action(function (array $data, RelationManager $livewire) {
                        $record = $livewire->getOwnerRecord();
                        $record->loadMissing('agency:agency_id,agency_name');

                        $timetablePage = TimetablePage::create([
                            'filename' => Str::slug("{$record->agency->agency_name} {$record->route_short_name}"),
                        ]);

                        foreach ($data['service_types'] as $serviceType) {
                            $timetablePage->timetables()->saveMany([
                                new Timetable([
                                    'route_id' => $record->route_id,
                                    'direction_id' => 1,
                                    ...$this->generateColumnsServiceType($serviceType),
                                ]),
                                new Timetable([
                                    'route_id' => $record->route_id,
                                    'direction_id' => 0,
                                    ...$this->generateColumnsServiceType($serviceType),
                                ]),
                            ]);
                        }
                    }),
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

    protected function generateColumnsServiceType($serviceType): array
    {
        return match ($serviceType) {
            'week' => [
                'monday' => 1,
                'tuesday' => 1,
                'wednesday' => 1,
                'thursday' => 1,
                'friday' => 1,
                'saturday' => 0,
                'sunday' => 0,
            ],
            'weekend' => [
                'monday' => 0,
                'tuesday' => 0,
                'wednesday' => 0,
                'thursday' => 0,
                'friday' => 0,
                'saturday' => 1,
                'sunday' => 1,
            ],
            'saturday' => [
                'monday' => 0,
                'tuesday' => 0,
                'wednesday' => 0,
                'thursday' => 0,
                'friday' => 0,
                'saturday' => 1,
                'sunday' => 0,
            ],
            'sunday' => [
                'monday' => 0,
                'tuesday' => 0,
                'wednesday' => 0,
                'thursday' => 0,
                'friday' => 0,
                'saturday' => 0,
                'sunday' => 1,
            ],
            'every' => [
                'monday' => 1,
                'tuesday' => 1,
                'wednesday' => 1,
                'thursday' => 1,
                'friday' => 1,
                'saturday' => 1,
                'sunday' => 1,
            ],
        };
    }
}
