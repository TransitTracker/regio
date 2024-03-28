<?php

namespace App\Filament\Resources\Gtfs\StopResource\Widgets;

use App\Filament\Resources\Gtfs\StopResource\Pages\ListStops;
use App\Models\Gtfs\Stop;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\Widget;

class StopsMap extends Widget
{
    use InteractsWithPageTable;

    protected static string $view = 'filament.resources.gtfs.stop-resource.widgets.stops-map';

    protected int | string | array $columnSpan = 'full';

    protected function getTablePage(): string
    {
        return ListStops::class;
    }
}
