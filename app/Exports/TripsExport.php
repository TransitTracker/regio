<?php

namespace App\Exports;

use App\Models\Gtfs\Trip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TripsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Trip::select($this->headings())->get();
    }

    public function headings(): array
    {
        return ['route_id', 'service_id', 'trip_id', 'trip_headsign', 'shape_id', 'agency_id'];
    }
}
