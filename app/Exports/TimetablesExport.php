<?php

namespace App\Exports;

use App\Models\Gtfs\Calendar;
use App\Models\Gtfs\Timetable;
use App\Models\Gtfs\Trip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TimetablesExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Timetable::query()
            ->select($this->headings())
            ->get()
            ->map(function (Timetable $timetable) {
                return array_merge($timetable->toArray(), [
                    'monday' => $timetable->monday ? '1' : '0',
                    'tuesday' => $timetable->tuesday ? '1' : '0',
                    'wednesday' => $timetable->wednesday ? '1' : '0',
                    'thursday' => $timetable->thursday ? '1' : '0',
                    'friday' => $timetable->friday ? '1' : '0',
                    'saturday' => $timetable->saturday ? '1' : '0',
                    'sunday' => $timetable->sunday ? '1' : '0',
                ]);
            });
    }

    public function headings(): array
    {
        return ['timetable_id', 'route_id', 'direction_id', 'start_date', 'end_date', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday', 'start_time', 'end_time', 'include_exceptions', 'timetable_label', 'service_notes', 'orientation', 'timetable_page_id', 'timetable_sequence', 'direction_name', 'show_trip_continuation'];
    }
}
