<?php

namespace App\Http\Resources\Gtfs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GeoJsonStopCollection extends ResourceCollection
{
    public static $wrap = 'features';

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'FeatureCollection',
            'features' => GeoJsonStopResource::collection($this->collection),
        ];
    }
}
