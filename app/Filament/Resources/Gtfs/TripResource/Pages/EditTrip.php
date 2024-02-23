<?php

namespace App\Filament\Resources\Gtfs\TripResource\Pages;

use App\Filament\Resources\Gtfs\TripResource;
use App\Models\Gtfs\Shape;
use App\Models\Gtfs\StopTime;
use App\Models\Gtfs\Trip;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Http;
use MatanYadaev\EloquentSpatial\Objects\LineString;

class EditTrip extends EditRecord
{
    protected static string $resource = TripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('generateShape')
                ->action(function () {
                    $this->record->load(['stopTimes:trip_id,stop_id,stop_sequence', 'stopTimes.stop:stop_id,stop_position']);

                    $coordinates = $this->record->stopTimes->sortBy('stop_sequence')->map(fn (StopTime $stopTime) => $stopTime->stop->toMapboxWaypoint())->join(';');

                    $response = Http::get("https://api.mapbox.com/directions/v5/mapbox/driving/{$coordinates}", [
                        'alternatives' => 'false',
                        'geometries' => 'geojson',
                        'overview' => 'full',
                        'steps' => 'false',
                        'notifications' => 'none',
                        'access_token' => config('app.mapbox_pk'),
                    ])['routes'][0]['geometry'];

                    $shape = Shape::create([
                        'agency_id' => $this->record->agency_id,
                        'shape' => LineString::fromArray($response),
                    ]);

                    $this->record->shape_id = $shape->shape_id;

                    Notification::make()
                        ->title('Shape generated successfully')
                        ->success()
                        ->send();
                })
                ->color('gray')
                ->icon('gmdi-route')
                ->requiresConfirmation(fn () => filled($this->record->shape_id))
                ->modalSubheading('This trip already has a shape attached to it'),
        ];
    }
}
