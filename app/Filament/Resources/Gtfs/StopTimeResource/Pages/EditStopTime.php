<?php

namespace App\Filament\Resources\Gtfs\StopTimeResource\Pages;

use App\Filament\Resources\Gtfs\StopTimeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStopTime extends EditRecord
{
    protected static string $resource = StopTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
