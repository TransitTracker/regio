<?php

namespace App\Exports;

use App\Models\Gtfs\Calendar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ServicesExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Calendar::select($this->headings())->get();
    }

    public function headings(): array
    {
        return ['service_id', 'agency_id', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday', 'start_date', 'end_date'];
    }
}
