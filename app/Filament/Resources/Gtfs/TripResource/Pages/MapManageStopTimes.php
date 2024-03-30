<?php

namespace App\Filament\Resources\Gtfs\TripResource\Pages;

use App\Enums\DropOffType;
use App\Enums\PickupType;
use App\Filament\Resources\Gtfs\TripResource;
use App\Http\Resources\Gtfs\GeoJsonStopCollection;
use App\Models\Gtfs\Stop;
use App\Models\Gtfs\StopTime;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MapManageStopTimes extends Page
{
    use InteractsWithRecord;

    public $stops;
    public string $stopsList;
    public ?string $shape;

    protected static string $resource = TripResource::class;

    protected static string $view = 'filament.resources.gtfs.trip-resource.pages.manage-stop-times';

    public function mount(int $record): void
    {
        $this->record = $this->resolveRecord($record);

        $this->record->loadMissing('shape');
        $this->shape = $this->record->shape?->shape->toFeatureCollectionJson();

        $this->record->loadMissing('stopTimes', 'stopTimes.stop');
        $this->stopsList = $this->record->stopTimes->map(function (StopTime $stopTime) {
            return [
                'stop_id' => $stopTime->stop_id,
                'stop_code' => $stopTime->stop->stop_code,
                'stop_name' => $stopTime->stop->stop_name,
                'arrival_time' => $stopTime->arrival_time,
                'departure_time' => $stopTime->departure_time,
                'stop_sequence' => $stopTime->stop_sequence,
            ];
        });

        $this->stops = (new GeoJsonStopCollection(Stop::all([
            'stop_id',
            'stop_code',
            'stop_name',
            'stop_position',
        ])))->toJson();
    }

    public function saveList()
    {
        $stopList = json_decode($this->stopsList, true);
        data_forget($stopList, '*.stop_code');
        data_forget($stopList, '*.stop_name');
        data_set($stopList, '*.pickup_type', PickupType::Regular);
        data_set($stopList, '*.drop_off_type', DropOffType::Regular);

        $this->record->stopTimes()->createMany($stopList);
        return redirect(TripResource::getUrl('edit', ['record' => $this->record]));
    }
}
