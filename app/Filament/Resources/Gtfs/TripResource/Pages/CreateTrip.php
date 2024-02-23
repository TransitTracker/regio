<?php

namespace App\Filament\Resources\Gtfs\TripResource\Pages;

use App\Filament\Resources\Gtfs\TripResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTrip extends CreateRecord
{
    protected static string $resource = TripResource::class;
}
