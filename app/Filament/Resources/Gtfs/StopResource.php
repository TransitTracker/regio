<?php

namespace App\Filament\Resources\Gtfs;

use App\Enums\LocationType;
use App\Enums\StopType;
use App\Filament\Resources\Gtfs\StopResource\Pages;
use App\Filament\Resources\Gtfs\StopResource\RelationManagers;
use App\Filament\Resources\StopResource\Widgets\StopsMap;
use App\Models\Gtfs\Stop;
use App\Models\Helper\Odonym;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use MatanYadaev\EloquentSpatial\Objects\Point;

class StopResource extends Resource
{
    protected static ?string $model = Stop::class;

    protected static ?string $navigationIcon = 'gmdi-pin-drop';
    protected static ?string $navigationGroup = 'GTFS Creator';
    protected static ?int $navigationSort = 2;
    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        // Todo: add parent stop and child stops
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Fieldset::make('Stop builder')
                    ->relationship(
                        name: 'stopBuilder',
                        condition: fn (?array $state): bool => filled($state['stop_type']),
                    )
                    ->schema([
                        Forms\Components\ToggleButtons::make('stop_type')
                            ->grouped()
                            ->columnSpanFull()
                            ->options(StopType::class)
                            ->live(),
                        Forms\Components\Select::make('stop_city')
                            ->requiredWith('stop_type')
                            ->relationship(
                                name: 'municipality',
                                titleAttribute: 'municipality',
                                modifyQueryUsing: fn (Builder $query) => $query->distinct()
                            )
                            ->searchable(),
                        Forms\Components\Select::make('main_street_id')
                            ->relationship(
                                name: 'mainStreet',
                                titleAttribute: 'toponym',
                                modifyQueryUsing: function (Builder $query, Forms\Get $get) {
                                    $municipality = $get('stop_city');
                                    info($municipality);
                                    return $query->where('municipality', $municipality);
                                },
                            )
                            ->requiredWith('stop_type')
                            ->searchable()
                            ->helperText('Street where the vehicle is located')
                            ->visible(fn (Forms\Get $get) => $get('stop_type') == StopType::StreetCorner->value || $get('stop_type') == StopType::FacingAddress->value),
                        Forms\Components\Select::make('cross_street_id')
                            ->relationship(
                                name: 'crossStreet',
                                titleAttribute: 'toponym',
                                modifyQueryUsing: fn (Builder $query, Forms\Get $get) => $query->where('municipality', $get('stop_city')),
                            )
                            ->searchable()
                            ->requiredWith('stop_type')
                            ->helperText('Perpendicular street')
                            ->visible(fn (Forms\Get $get) => $get('stop_type') == StopType::StreetCorner->value),
                        Forms\Components\TextInput::make('place_name')
                            ->requiredWith('stop_type')
                            ->visible(fn (Forms\Get $get) => $get('stop_type') == StopType::Place->value || $get('stop_type') == StopType::Infrastructure->value)
                            ->label(fn (Forms\Get $get) => $get('stop_type') == StopType::Place->value ? 'Place name' : 'Infrastructure name')
                            ->helperText(fn (Forms\Get $get) => $get('stop_type') == StopType::Place->value ? 'Use Place only if the bus stop is inside this place. Otherwise, use the Stop description field.' : 'Bus terminal, subway station, train station'),
                        Forms\Components\TextInput::make('facing_address')
                            ->requiredWith('stop_type')
                            ->visible(fn (Forms\Get $get) => $get('stop_type') == StopType::FacingAddress->value),
                        Forms\Components\TextInput::make('place_name_precision')
                            ->visible(fn (Forms\Get $get) => $get('stop_type') == StopType::Infrastructure->value)
                            ->label('Precision')
                            ->helperText('For example, quay 3, door 2, platform 1'),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('generateName')
                                ->icon('mdi-refresh')
                                ->color('gray')
                                ->action(function (Forms\Get $get, Forms\Set $set) {
                                   $stopType = intval($get('stop_type'));

                                   // Values
                                    $mainStreet = Odonym::where('topo_id', $get('main_street_id'))->select(['topo_id', 'specific'])->first()?->specific;
                                    $crossStreet = Odonym::where('topo_id', $get('cross_street_id'))->select(['topo_id', 'specific'])->first()?->specific;

                                   if ($stopType === StopType::StreetCorner->value) {
                                       $set('../stop_name', "{$mainStreet} / {$crossStreet}");
                                       return;
                                   }

                                   if ($stopType === StopType::FacingAddress->value) {
                                       $set('../stop_name', "{$mainStreet} / No {$get('facing_address')}");
                                       return;
                                   }
                                   if ($stopType === StopType::Infrastructure->value && filled($get('place_name_precision'))) {
                                       $set('../stop_name', "{$get('place_name')} ({$get('place_name_precision')})");
                                       return;
                                   }
                                    $set('../stop_name', $get('place_name'));
                                }),
                        ])
                            ->columnSpanFull()
                            ->alignCenter()
                            ->verticallyAlignEnd(),
                    ]),
                Forms\Components\ToggleButtons::make('location_type')
                    ->columnSpan(2)
                    ->options(LocationType::class)
                    ->grouped()
                    ->live()
                    ->default(LocationType::StopPlatform),
                Forms\Components\Select::make('parent_station')
                    ->relationship('parentStation', 'stop_name', ignoreRecord: true)
                    ->required(fn (Forms\Get $get) => in_array($get('location_type'), [2, 3, 4]))
                    ->disabled(fn (Forms\Get $get) => $get('location_type' == 1))
                    ->hidden(fn (Forms\Get $get) => $get('location_type' == 1))
                    ->searchable(),
                Forms\Components\TextInput::make('stop_name')
                    ->columnSpan(2)
                    ->readOnly(),
                TextInput::make('platform_code')
                    ->helperText('Only for stations with multiple platforms. Do not add words like "Platform" or "Door", only the number or letter.'),
                Forms\Components\TextInput::make('stop_desc')
                    ->columnSpanFull()
                    ->label('Description')
                    ->helperText('For additional information, like: In front of Town Hall'),
                Forms\Components\TextInput::make('stop_position')
                    ->required()
                    ->label('Position')
                    ->helperText('Format: Longitude, Latitude')
                    ->rules([
                        function () {
                            return function (string $attribute, $value, \Closure $fail) {
                                $pattern = '/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?([1-9]?\d(\.\d+)?|1[0-7]\d(\.\d+)?|180(\.0+)?)$/';

                                if (!preg_match($pattern, $value)) {
                                    return $fail('The format of this position is invalid');
                                }
                            };
                        },
                    ])
                    ->afterStateHydrated(function (TextInput $component, $state) {
                        if (blank($state) || !key_exists('coordinates', $state)) return null;

                        $component->state(join(', ', array_reverse($state['coordinates'])));
                    })
                    ->dehydrateStateUsing(function ($state) {
                        $coordinates = collect(explode(',', preg_replace('/\s+/', '', $state)))
                            ->map(fn (string $item) => floatval($item))
                            ->map(fn (float $item) => round($item, 6))
                            ->all();

                        return new Point($coordinates[0], $coordinates[1]);
                    }),
                Forms\Components\TextInput::make('stop_code')
                    ->helperText('Used for customer information'),
                // Todo: make Zone ID a relationship
                Forms\Components\TextInput::make('zone_id')
                    ->label('Zone ID'),
                Forms\Components\ToggleButtons::make('weelchair_boarding')
                    ->options([
                        0 => 'No information',
                        1 => 'Accessible',
                        2 => 'Not accessible',
                    ])
                    ->icons([
                        0 => 'mdi-help',
                        1 => 'mdi-wheelchair-accessibility',
                        2 => 'mdi-close',
                    ])
                    ->colors([
                        1 => 'success',
                        2 => 'danger',
                    ])
                    ->grouped()
                    ->default(0)
                    ->columnSpan(2),
//                Forms\Components\TextInput::make('stop_id')
//                    ->disabledOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stop_code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stop_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stop_desc')
                    ->searchable(),
                Tables\Columns\TextColumn::make('trips_count')
                    ->counts('trips')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->groups([
                'zone_id',
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
            'index' => Pages\ListStops::route('/'),
//            'create' => Pages\CreateStop::route('/create'),
            'edit' => Pages\EditStop::route('/{record}/edit'),
            'map' => Pages\StopsMap::route('/map'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            // StopsMap::class,
        ];
    }
}
