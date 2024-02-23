<?php

namespace App\Exports;

use App\Models\Gtfs\Route;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RoutesExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Route::select($this->headings())->get();
    }

    public function headings(): array
    {
        return ['agency_id', 'route_id', 'route_long_name', 'route_type', 'route_color'];
    }
}
