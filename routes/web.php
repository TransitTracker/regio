<?php

use App\Exports\AgenciesExport;
use App\Exports\RoutesExport;
use App\Exports\ServicesExport;
use App\Exports\StopsExport;
use App\Exports\StopTimesExport;
use App\Exports\TripsExport;
use App\Filament\Resources\Gtfs\StopTimeResource;
use App\Http\Resources\Gtfs\GeoJsonShapeCollection;
use App\Http\Resources\Gtfs\GeoJsonStopCollection;
use App\Livewire\RoutesMap;
use App\Models\Gtfs\Shape;
use App\Models\Gtfs\Stop;
use App\Models\Gtfs\StopTime;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as Formats;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/map', RoutesMap::class);
Route::get('/', function () {
    return Inertia::render('Map', [
        'shapes' => new GeoJsonShapeCollection(
            Shape::query()
                ->select(['shape_id', 'shape'])
                ->with(['oneTrip:gtfs_trips.route_id,gtfs_trips.shape_id', 'oneTrip.route:gtfs_routes.route_id,route_short_name,route_long_name,route_color,route_text_color,route_url'])
                ->get()
        ),
        'stops' => new GeoJsonStopCollection(
            Stop::query()
                ->select(['stop_id', 'stop_code', 'stop_name', 'stop_position', 'agency_id'])
                ->with(['agency:agency_id,agency_name,default_color,default_text_color'])
                ->get()
        ),
    ]);
});
