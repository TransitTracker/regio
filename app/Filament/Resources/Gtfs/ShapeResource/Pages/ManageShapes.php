<?php

namespace App\Filament\Resources\Gtfs\ShapeResource\Pages;

use App\Filament\Resources\Gtfs\ShapeResource;
use App\Filament\Resources\Gtfs\ShapeResource\Widgets\ShapesMap;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageShapes extends ManageRecords
{
    protected static string $resource = ShapeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // ShapesMap::class,
        ];
    }
}
