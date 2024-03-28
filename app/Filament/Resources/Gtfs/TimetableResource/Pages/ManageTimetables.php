<?php

namespace App\Filament\Resources\Gtfs\TimetableResource\Pages;

use App\Filament\Resources\Gtfs\TimetableResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageTimetables extends ManageRecords
{
    protected static string $resource = TimetableResource::class;
    protected ?string $subheading = 'This is an extra file to generate timetables using GTFS-to-HTML.';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
