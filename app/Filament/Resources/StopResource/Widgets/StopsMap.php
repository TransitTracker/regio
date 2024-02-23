<?php

namespace App\Filament\Resources\StopResource\Widgets;

use App\Models\Gtfs\Stop;
use Webbingbrasil\FilamentMaps\Widgets\MapWidget;
use Webbingbrasil\FilamentMaps\Marker;

class StopsMap
// class StopsMap extends MapWidget
{
    /*public function getMarkers(): array
    {
        return Stop::query()
            ->select(['stop_id', 'stop_name', 'stop_position'])
            ->get()
            ->map(function (Stop $stop) {
                return Marker::make($stop->stop_id)
                    ->lat($stop->stop_position->latitude)
                    ->lng($stop->stop_position->longitude)
                    ->popup($stop->stop_name);
            })
            ->all();
    }*/
}
