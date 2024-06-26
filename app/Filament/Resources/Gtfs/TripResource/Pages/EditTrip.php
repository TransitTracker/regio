<?php

namespace App\Filament\Resources\Gtfs\TripResource\Pages;

use App\Filament\Resources\Gtfs\TripResource;
use App\Models\Gtfs\Shape;
use App\Models\Gtfs\StopTime;
use App\Models\Gtfs\Trip;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use MatanYadaev\EloquentSpatial\Objects\LineString;

class EditTrip extends EditRecord
{
    protected static string $resource = TripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->requiresConfirmation(),
            Action::make('generateShape')
                ->form([
                    TextInput::make('shape_desc')
                        ->required()
                        ->label('Shape Description')
                        ->helperText('Describe the shape of the current trip')
                        ->prefix(fn (Trip $record) => "{$record->route->route_short_name}:"),
                ])
                ->action(function (Trip $record, array $data) {
                    $record->load(['stopTimes:trip_id,stop_id,stop_sequence', 'stopTimes.stop:stop_id,stop_position']);

                    $coordinatesChunk = $record->stopTimes
                        ->sortBy('stop_sequence')
                        ->map(fn (StopTime $stopTime) => $stopTime->stop->toMapboxWaypoint())
                        ->chunk(24);

                    $newChunks = collect([]);
                    $coordinatesChunk->each(function (Collection $chunk, $index) use ($coordinatesChunk, $newChunks) {
                        if ($index > 0) {
                            $overlappingChunk = $coordinatesChunk[$index - 1]->slice(23)->merge($chunk);
                            $newChunks->push($overlappingChunk);
                        } else {
                            $newChunks->push($chunk);
                        }
                    });

                    $responses = collect([]);
                    $newChunks->each(function (Collection $chunk) use ($responses) {
                        $response = Http::get("https://api.mapbox.com/directions/v5/mapbox/driving/{$chunk->join(';')}", [
                            'alternatives' => 'false',
                            'geometries' => 'geojson',
                            'overview' => 'full',
                            'steps' => 'false',
                            'notifications' => 'none',
                            'access_token' => config('app.mapbox_pk'),
                            'continue_straight' => 'false',
                        ]);
                        $responses->push($response->json('routes.0.geometry.coordinates'));
                    });

                    $shape = Shape::create([
                        'agency_id' => $this->record->agency_id,
                        'shape' => LineString::fromArray([
                            'coordinates' => $responses->flatten(1)->all(),
                            'type' => 'LineString',
                        ]),
                        'shape_desc' => "{$record->route->route_short_name}: {$data['shape_desc']}",
                    ]);

                    $record->shape_id = $shape->shape_id;
                    $record->save();

                    $this->refreshFormData(['shape_id']);

                    Notification::make()
                        ->title('Shape generated successfully')
                        ->success()
                        ->send();
                })
                ->color('gray')
                ->icon('gmdi-route')
                ->requiresConfirmation(fn (Trip $record): bool => filled($record->shape_id))
                ->modalHeading('Generate a new shape')
                ->modalDescription('There is already a shape associated with this trip. Are you sure you want to generate a new one?')
                ->modalSubmitActionLabel('Yes, generate a new one'),
        ];
    }
}
