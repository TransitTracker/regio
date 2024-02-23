<?php

namespace App\Filament\Resources\Gtfs\TripResource\Pages;

use App\Filament\Resources\Gtfs\TripResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrips extends ListRecords
{
    protected static string $resource = TripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
