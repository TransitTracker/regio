<?php

namespace App\Filament\Resources\Gtfs;

use App\Enums\DropOffType;
use App\Enums\PickupType;
use App\Enums\RouteType;
use App\Filament\Resources\Gtfs\TripResource\Pages;
use App\Filament\Resources\Gtfs\TripResource\RelationManagers;
use App\Models\Gtfs\Calendar;
use App\Models\Gtfs\Route;
use App\Models\Gtfs\Shape;
use App\Models\Gtfs\Stop;
use App\Models\Gtfs\StopTime;
use App\Models\Gtfs\Trip;
use Awcodes\TableRepeater\Header;
use Carbon\Carbon;
use Closure;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Position;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class TripResource extends Resource
{
    protected static ?string $model = Trip::class;

    protected static ?string $navigationIcon = 'gmdi-view-timeline';
    protected static ?string $navigationGroup = 'GTFS Creator';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('route_id')
                    ->relationship(
                        name: 'route',
                        titleAttribute: 'route_long_name',
                        modifyQueryUsing: fn (Builder $query, Forms\Get $get) => $query->where('agency_id', Filament::getTenant()->getKey()),
                    )
                    ->searchable(['route_short_name', 'route_long_name'])
                    ->getOptionLabelFromRecordUsing(fn (Route $record) => "{$record->route_id}: {$record->route_short_name} {$record->route_long_name}")
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('trip_headsign')
                    ->columnSpan(2)
                    ->maxLength(255),
                Forms\Components\TextInput::make('trip_short_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('block_id')
                    ->maxLength(255),
                Forms\Components\Select::make('service_id')
                    ->relationship(
                        name: 'service',
                        titleAttribute: 'service_description',
                        modifyQueryUsing: fn (Builder $query, Forms\Get $get) => $query->where('agency_id', Filament::getTenant()->getKey()),
                    )
                    ->getOptionLabelFromRecordUsing(fn (Calendar $record): string => "[{$record->service_pattern}] {$record->service_description}")
                    ->required(),
                Forms\Components\Select::make('shape_id')
                    ->relationship(
                        name: 'shape',
                        titleAttribute: 'shape_id',
                        // TODO: Filter by route
                    )
                    ->getOptionLabelFromRecordUsing(fn (Shape $shape): string => $shape->shape_desc ?? $shape->shape_id),
                Forms\Components\Toggle::make('wheelchair_accessible')
                    ->inline(false),
                Forms\Components\Toggle::make('bikes_allowed')
                    ->inline(false),
                Forms\Components\ToggleButtons::make('direction_id')
                    ->label('Direction')
                    ->options([
                        false => 'Inbound',
                        true => 'Outbound',
                    ])
                    ->icons([
                        false => 'mdi-arrow-right',
                        true => 'mdi-arrow-left',
                    ])
                    ->grouped()
                    ->default(false),
                \Awcodes\TableRepeater\Components\TableRepeater::make('stopTimes')
                    ->relationship()
                    ->headers([
                        Header::make('stop'),
                        Header::make('arrival'),
                        Header::make('departure'),
                        Header::make('Pickup Type'),
                        Header::make('Drop Off Type'),
                    ])
                    ->schema([
                        Forms\Components\Select::make('stop_id')
                            ->relationship('stop', 'stop_name')
                            ->searchable(['stop_code', 'stop_id', 'stop_name', 'stop_desc'])
                            ->searchDebounce(250)
                            ->getOptionLabelFromRecordUsing(fn (Stop $record): string => "[{$record->stop_code}] {$record->stop_name}")
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TimePicker::make('arrival_time')
                            ->label('Arrival')
                            ->seconds(false)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state, Forms\Get $get) => $set('departure_time', $state))
                            ->required(),
                        Forms\Components\TimePicker::make('departure_time')
                            ->seconds(false)
                            ->label('Departure'),
                        Forms\Components\Select::make('pickup_type')
                            ->options(PickupType::class)
                            ->default(0)
                            ->required(),
                        Forms\Components\Select::make('drop_off_type')
                            ->options(DropOffType::class)
                            ->default(0)
                            ->required(),
                    ])
                    ->extraActions([
                        Forms\Components\Actions\Action::make('addMultiple')
                            ->form([
                                Forms\Components\TextInput::make('howMany')
                                    ->required()
                                    ->numeric(),
                            ])
                            ->action(function (array $data, \Awcodes\TableRepeater\Components\TableRepeater $component): void {
                                $state = $component->getState();

                                for ($i = 1; $i <= $data['howMany']; $i++) {
                                    $state[Str::uuid()->toString()] = [];
                                }

                                $component->state($state);
                            })
                    ])
                    ->orderColumn('stop_sequence')
                    ->columnSpanFull()
                    ->hiddenOn('create'),
                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('previewDirections')
                        ->icon('mdi-directions')
                        ->hidden(fn (string $operation): bool => $operation === 'create')
                        ->url(
                            url: fn (?Trip $record): string => "https://www.google.com/maps/dir/".collect($record->stopTimes)->sortBy('stop_sequence')->map(fn (StopTime $stopTime): string => $stopTime->stop?->stop_position?->latitude.",".$stopTime->stop?->stop_position?->longitude)->join('/'),
                            shouldOpenInNewTab: true,
                        ),
                ])
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('agency.agency_name'),
                Tables\Columns\TextColumn::make('route.route_short_name'),
                Tables\Columns\TextColumn::make('trip_short_name'),
                Tables\Columns\TextColumn::make('trip_headsign'),
                Tables\Columns\IconColumn::make('direction_id')
                    ->label('Direction')
                    ->boolean()
                    ->trueIcon('mdi-arrow-left')
                    ->trueColor('primary')
                    ->falseIcon('mdi-arrow-right')
                    ->falseColor('primary'),
                Tables\Columns\TextColumn::make('service.service_pattern')
                    ->fontFamily(FontFamily::Mono),
                Tables\Columns\TextColumn::make('firstDeparture.arrival_time')
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('agency_id')
                    ->relationship('agency', 'agency_name'),
                Tables\Filters\SelectFilter::make('route_id')
                    ->relationship('route', 'route_short_name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('replicate')
                    ->icon('mdi-content-copy')
                    ->form([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TimePicker::make('starting_time')
                                    ->seconds(false)
                                    ->label('Starting time')
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('new_trip_short_name')
                                    ->helperText('Optional'),
                                Forms\Components\Toggle::make('inverse_stop_order')
                                    ->helperText('Stop time will not be calculated')
                                    ->inline(false),
                            ])
                    ])
                    ->action(function (array $data, Trip $record): void {
                        $record->replicateWithStopTimes($data['starting_time'], $data['new_trip_short_name'], $data['inverse_stop_order']);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->actionsPosition(Tables\Enums\ActionsPosition::BeforeCells);
    }

    public static function getRelations(): array
    {
        return [
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
