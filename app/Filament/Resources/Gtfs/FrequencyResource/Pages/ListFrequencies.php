<?php

namespace App\Filament\Resources\Gtfs\FrequencyResource\Pages;

use App\Filament\Resources\Gtfs\FrequencyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrequencies extends ListRecords
{
    protected static string $resource = FrequencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
