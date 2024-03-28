<?php

namespace App\Filament\Resources\Gtfs\TimetablePageResource\Pages;

use App\Filament\Resources\Gtfs\TimetablePageResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTimetablePages extends ManageRecords
{
    protected static string $resource = TimetablePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
