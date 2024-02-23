<?php

namespace App\Filament\Resources\Gtfs\RouteResource\Pages;

use App\Filament\Resources\Gtfs\RouteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Position;

class ListRoutes extends ListRecords
{
    protected static string $resource = RouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableActionsPosition(): ?string
    {
        return Position::BeforeCells;
    }

    protected function getTableReorderColumn(): ?string
    {
        return 'route_sort_order';
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'route_sort_order';
    }
}
