<?php

namespace App\Filament\Resources\Gtfs\AgencyResource\Pages;

use App\Filament\Resources\Gtfs\AgencyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgency extends EditRecord
{
    protected static string $resource = AgencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
