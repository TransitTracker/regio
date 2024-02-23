<?php

namespace App\Filament\Resources\Gtfs\StopResource\Pages;

use App\Filament\Resources\Gtfs\StopResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStop extends CreateRecord
{
    protected static string $resource = StopResource::class;
}
