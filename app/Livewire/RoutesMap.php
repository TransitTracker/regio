<?php

namespace App\Livewire;

use App\Http\Resources\Gtfs\GeoJsonShapeCollection;
use App\Http\Resources\Gtfs\GeoJsonStopCollection;
use App\Models\Gtfs\Shape;
use App\Models\Gtfs\Stop;
use Livewire\Component;

class RoutesMap extends Component
{
    public $stops;
    public $shapes;

    public function render()
    {
        return view('livewire.routes-map');
    }

    public function mount()
    {
        $this->stops = (new GeoJsonStopCollection(
            Stop::query()
                ->select(['stop_id', 'stop_code', 'stop_name', 'stop_position', 'agency_id'])
                ->with(['agency:agency_id,agency_name,default_color,default_text_color'])
                ->get()
        ))->toJson();

        $this->shapes = (new GeoJsonShapeCollection(
            Shape::query()
                ->select(['shape_id', 'shape'])
                ->with(['oneTrip:gtfs_trips.route_id,gtfs_trips.shape_id', 'oneTrip.route:gtfs_routes.route_id,route_short_name,route_long_name,route_color,route_text_color,route_url'])
                ->get()
        ))->toJson();
    }
}
