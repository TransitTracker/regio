<?php

namespace App\Exports;

use App\Models\Gtfs\Timetable;
use App\Models\Gtfs\TimetablePage;
use App\Models\Gtfs\Trip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TimetablePagesExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return TimetablePage::select($this->headings())->get();
    }

    public function headings(): array
    {
        return ['timetable_page_id', 'timetable_page_label', 'filename'];
    }
}
