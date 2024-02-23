<?php

namespace App\Filament\Resources\Gtfs\FrequencyResource\Pages;

use App\Filament\Resources\Gtfs\FrequencyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFrequency extends EditRecord
{
    protected static string $resource = FrequencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
