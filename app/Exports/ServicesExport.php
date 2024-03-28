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
        return Calendar::query()
            ->select($this->headings())
            ->get()
            ->map(function (Calendar $calendar) {
                return array_merge($calendar->toArray(), [
                    'monday' => $calendar->monday ? '1' : '0',
                    'tuesday' => $calendar->tuesday ? '1' : '0',
                    'wednesday' => $calendar->wednesday ? '1' : '0',
                    'thursday' => $calendar->thursday ? '1' : '0',
                    'friday' => $calendar->friday ? '1' : '0',
                    'saturday' => $calendar->saturday ? '1' : '0',
                    'sunday' => $calendar->sunday ? '1' : '0',
                ]);
            });
    }

    public function headings(): array
    {
        return ['service_id', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday', 'start_date', 'end_date'];
    }
}
