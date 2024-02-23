<?php

namespace App\Imports;

use App\Models\Gtfs\Stop;
use Maatwebsite\Excel\Concerns\ToModel;
use MatanYadaev\EloquentSpatial\Objects\Point;

class VSHStopsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (
            blank($row[0]) ||
            blank($row[1]) ||
            blank($row[2]) ||
            blank($row[3])
        ) {
            return null;
        }

        return new Stop([
            'stop_code' => $row[0],
            'stop_name' => $row[1],
            'stop_position' => new Point((float) $row[2], (float) $row[3]),
            'agency_id' => 4,
        ]);
    }
}
