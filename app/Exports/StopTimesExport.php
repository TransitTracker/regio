<?php

namespace App\Exports;

use App\Models\Gtfs\StopTime;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StopTimesExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return StopTime::select($this->headings())->get();
    }

    public function headings(): array
    {
        return ['trip_id', 'arrival_time', 'departure_time', 'stop_id', 'stop_sequence', 'pickup_type', 'drop_off_type'];
    }
}
