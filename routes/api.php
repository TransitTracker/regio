<?php

use App\Http\Resources\Gtfs\GeoJsonShapeCollection;
use App\Http\Resources\Gtfs\GeoJsonStopCollection;
use App\Models\Gtfs\Shape;
use App\Models\Gtfs\Stop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/shapes', function () {
    return new GeoJsonShapeCollection(
        Shape::query()
            ->select(['shape_id', 'shape'])
            ->with(['oneTrip:gtfs_trips.route_id,gtfs_trips.shape_id', 'oneTrip.route:gtfs_routes.route_id,route_short_name,route_long_name,route_color,route_text_color,route_url'])
            ->paginate());
});
Route::get('/stops', function () {
    return new GeoJsonStopCollection(
        Stop::query()
            ->select(['stop_id', 'stop_code', 'stop_name', 'stop_position', 'agency_id'])
            ->with(['agency:agency_id,agency_name,default_color,default_text_color'])
            ->paginate()
    );
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
