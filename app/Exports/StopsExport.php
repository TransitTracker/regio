<?php

namespace App\Exports;

use App\Models\Gtfs\Stop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StopsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Stop::query()
            ->select(['stop_id', 'stop_code', 'stop_name', 'stop_desc', 'stop_position'])
            ->get()
            ->map(function (Stop $stop) {
                return [
                    'stop_id' => $stop->stop_id,
                    'stop_code' => $stop->stop_code,
                    'stop_name' => $stop->stop_name,
                    'stop_desc' => $stop->stop_desc,
                    'stop_lat' => $stop->stop_position->latitude,
                    'stop_lon' => $stop->stop_position->longitude,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'stop_id',
            'stop_code',
            'stop_name',
            'stop_desc',
            'stop_lat',
            'stop_lon',
        ];
    }
}
