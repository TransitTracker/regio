<?php

use App\Exports\AgenciesExport;
use App\Exports\RoutesExport;
use App\Exports\ServicesExport;
use App\Exports\StopsExport;
use App\Exports\StopTimesExport;
use App\Exports\TripsExport;
use App\Filament\Resources\Gtfs\StopTimeResource;
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
    Excel::store(new AgenciesExport(), 'intercar/agencies.txt', 'public', Formats::CSV);
    Excel::store(new RoutesExport(), 'intercar/routes.txt', 'public', Formats::CSV);
    Excel::store(new ServicesExport(), 'intercar/calendar.txt', 'public', Formats::CSV);
    Excel::store(new StopsExport(), 'intercar/stops.txt', 'public', Formats::CSV);
    Excel::store(new StopTimesExport(), 'intercar/stop_times.txt', 'public', Formats::CSV);
    Excel::store(new TripsExport(), 'intercar/trips.txt', 'public', Formats::CSV);
});
