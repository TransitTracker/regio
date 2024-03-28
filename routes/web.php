<?php

use App\Exports\AgenciesExport;
use App\Exports\RoutesExport;
use App\Exports\ServicesExport;
use App\Exports\StopsExport;
use App\Exports\StopTimesExport;
use App\Exports\TripsExport;
use App\Filament\Resources\Gtfs\StopTimeResource;
use App\Models\Gtfs\Shape;
use App\Models\Gtfs\StopTime;
use Illuminate\Support\Facades\Route;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('prepare', function () {
   dd(Shape::query()
       ->select(['shape_id', 'shape'])
       ->get()
       ->map(function (Shape $shape) {
           return collect($shape->shape->getCoordinates())
               ->map(function (array $coordinates, int $key) use ($shape) {
                   return [
                       'shape_id' => $shape->shape_id,
                       'shape_pt_lat' => $coordinates[1],
                       'shape_pt_lon' => $coordinates[0],
                       'shape_pt_sequence' => $key,
                   ];
               })
               ->toArray();
       })
    ->flatten(1));
});

Route::get('inverse', function () {
    $stops = \App\Models\Gtfs\Stop::all();

    foreach ($stops as $stop) {
        $stop->stop_position = new \MatanYadaev\EloquentSpatial\Objects\Point($stop->stop_position->longitude, $stop->stop_position->latitude);
        $stop->save();
    }
});
