<?php

namespace App\Filament\Resources\Gtfs\ShapeResource\Widgets;

use App\Filament\Resources\Gtfs\ShapeResource\Pages\ManageShapes;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\Widget;
class ShapesMap extends Widget
{
    use InteractsWithPageTable;

    protected static string $view = 'filament.resources.gtfs.shape-resource.widgets.shapes-map';

    protected int | string | array $columnSpan = 'full';

    protected function getTablePage(): string
    {
        return ManageShapes::class;
    }
}
