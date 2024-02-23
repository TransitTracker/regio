<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\Point;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Point::macro('toMapboxWaypoint', function (Point $point): string {
            return "{$point->longitude},{$point->latitude}";
        });
    }
}
