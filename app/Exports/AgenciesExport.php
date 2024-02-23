<?php

namespace App\Exports;

use App\Models\Gtfs\Agency;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AgenciesExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Agency::select($this->headings())->get();
    }

    public function headings(): array
    {
        return ['agency_id', 'agency_name', 'agency_url', 'agency_timezone'];
    }
}
