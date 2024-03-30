<?php

namespace App\Http\Resources\Gtfs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeoJsonStopResource extends JsonResource
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
            'geometry' => $this->stop_position,
            'properties' => [
                'stop_code' => $this->stop_code,
                'stop_name' => $this->stop_name,
                'agency_name' => $this->whenLoaded('agency', fn () => $this->agency->agency_name),
                'agency_color' => $this->whenLoaded('agency', fn () => "#{$this->agency->default_color}"),
                'agency_text_color' => $this->whenLoaded('agency', fn () => "#{$this->agency->default_text_color}"),
            ],
            'id' => $this->stop_id,
        ];
    }
}
