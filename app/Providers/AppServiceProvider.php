<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
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

        FilamentAsset::register([
            Css::make('leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'),
            Css::make('leaflet-draw', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css'),
            Js::make('leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'),
            Js::make('leaflet-draw', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js'),
            Js::make('stops-map', resource_path('js/stops-map.js'))->loadedOnRequest(),
            Js::make('shapes-map', resource_path('js/shapes-map.js'))->loadedOnRequest(),
        ]);

        FilamentIcon::register([
            'panels::resources.pages.edit-record.navigation-item' => 'mdi-pencil',
            'forms::components.builder.actions.clone' => 'mdi-content-copy',
            'forms::components.builder.actions.delete' => 'mdi-delete',
            'forms::components.builder.actions.reorder' => 'mdi-reorder-horizontal',
        ]);

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch->locales(['fr', 'en']);
        });
    }
}
