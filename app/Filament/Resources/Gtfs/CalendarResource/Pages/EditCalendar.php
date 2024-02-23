<?php

namespace App\Filament\Resources\Gtfs\CalendarResource\Pages;

use App\Filament\Resources\Gtfs\CalendarResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCalendar extends EditRecord
{
    protected static string $resource = CalendarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
