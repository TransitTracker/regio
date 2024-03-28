<?php

namespace App\Filament\Resources\Gtfs\CalendarResource\Pages;

use App\Filament\Exports\Gtfs\CalendarExporter;
use App\Filament\Resources\Gtfs\CalendarResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCalendars extends ListRecords
{
    protected static string $resource = CalendarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make()
                ->exporter(CalendarExporter::class)
                ->columnMapping(false),
        ];
    }
}
