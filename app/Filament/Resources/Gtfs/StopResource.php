<?php

namespace App\Filament\Resources\Gtfs;

use App\Filament\Resources\Gtfs\StopResource\Pages;
use App\Filament\Resources\Gtfs\StopResource\RelationManagers;
use App\Filament\Resources\StopResource\Widgets\StopsMap;
use App\Models\Gtfs\Stop;
use App\Models\Helper\Odonym;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use MatanYadaev\EloquentSpatial\Objects\Point;

class StopResource extends Resource
{
    protected static ?string $model = Stop::class;

    protected static ?string $navigationIcon = 'gmdi-pin-drop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('helper_builder')
                    ->label('Stop builder')
                    ->schema([
                        Forms\Components\ToggleButtons::make('helper_type')
                            ->label('Stop type')
                            ->grouped()
                            ->columnSpanFull()
                            ->options([
                                'corner' => 'Street corner',
                                'address' => 'Facing an address',
                                'place' => 'Place',
                                'infra' => 'Transportation infrastructure (e.g.: terminus, station)',
                            ]),
                        Forms\Components\Select::make('helper_municipality')
                            ->label('Municipality')
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search): array => Odonym::where('municipality', 'like', "%{$search}%")->limit(50)->distinct('municipality')->pluck('municipality')->toArray()),
                        Forms\Components\Select::make('helper_travelling_street')
                            ->label('Street where the vehicle is located')
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search, Get $get): array => Odonym::where('toponym', 'like', "%{$search}%")->where('municipality', $get('helper_municipality'))->limit(50)->pluck('toponym')->toArray()),
                    ]),
                Forms\Components\TextInput::make('stop_position')
                    ->required()
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
                Forms\Components\TextInput::make('stop_code'),
                Forms\Components\TextInput::make('stop_name'),
                Forms\Components\TextInput::make('stop_desc'),
                Forms\Components\TextInput::make('zone_id'),
                Forms\Components\Toggle::make('weelchair_boarding'),
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
            'create' => Pages\CreateStop::route('/create'),
            'edit' => Pages\EditStop::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            // StopsMap::class,
        ];
    }
}
