<?php

namespace App\Filament\Resources\Gtfs\StopResource\Pages;

use App\Filament\Resources\Gtfs\StopResource;
use App\Filament\Resources\StopResource\Widgets\StopsMap;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStops extends ListRecords
{
    protected static string $resource = StopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // StopsMap::class,
        ];
    }
}
