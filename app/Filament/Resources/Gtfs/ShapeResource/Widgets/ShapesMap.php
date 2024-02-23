<?php

namespace App\Filament\Resources\Gtfs\ShapeResource\Widgets;

use App\Models\Gtfs\Shape;
use Webbingbrasil\FilamentMaps\Polyline;
use Webbingbrasil\FilamentMaps\Widgets\MapWidget;

// class ShapesMap extends MapWidget
class ShapesMap
{
    /*public function getPolylines(): array
    {
        return Shape::query()
            ->select(['shape_id', 'agency_id', 'shape'])
            ->get()
            ->map(function (Shape $shape) {
                return Polyline::make($shape->shape_id)
                    ->latlngs(
                        collect($shape->shape->toArray()['coordinates'])
                            ->map(fn (array $coordinates) => array_reverse($coordinates))
                            ->all()
                    )
                    ->options(['color' => 'blue', 'weight' => 5])
                    ->popup("{$shape->shape_id} of agency_id: {$shape->agency_id}");
            })
            ->all();
    }*/
}
