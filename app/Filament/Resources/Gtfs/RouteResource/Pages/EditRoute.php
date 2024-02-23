<?php

namespace App\Filament\Resources\Gtfs\RouteResource\Pages;

use App\Filament\Resources\Gtfs\RouteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoute extends EditRecord
{
    protected static string $resource = RouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
