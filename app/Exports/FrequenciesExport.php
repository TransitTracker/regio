<?php

namespace App\Exports;

use App\Models\Gtfs\Frequency;
use App\Models\Gtfs\Timetable;
use App\Models\Gtfs\TimetablePage;
use App\Models\Gtfs\Trip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FrequenciesExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Frequency::select($this->headings())->get();
    }

    public function headings(): array
    {
        return ['agency_id', 'trip_id', 'start_time', 'end_time', 'headway_secs', 'exact_times'];
    }
}
