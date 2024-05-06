<?php

namespace App\Http\Resources\Gtfs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeoJsonShapeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'Feature',
            'geometry' => $this->shape,
            'properties' => [
                'route_color' => "#{$this->oneTrip?->route?->route_color}",
                'route_text_color' => "#{$this->oneTrip?->route?->route_text_color}",
                'route_short_name' => $this->oneTrip?->route?->route_short_name,
                'route_long_name' => $this->oneTrip?->route?->route_long_name,
                'route_url' => $this->oneTrip?->route?->route_url,
            ],
            'id' => $this->shape_id,
        ];
    }
}
