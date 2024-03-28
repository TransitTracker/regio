<?php

namespace App\Filament\Resources\Gtfs\StopResource\Pages;

use App\Filament\Resources\Gtfs\StopResource;
use Filament\Actions\DeleteAction;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStop extends EditRecord
{
    protected static string $resource = StopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
