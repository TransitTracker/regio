<?php

namespace App\Exports;

use App\Models\Gtfs\Shape;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShapesExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Shape::query()
            ->select(['shape_id', 'shape'])
            ->get()
            ->map(function (Shape $shape) {
                return collect($shape->shape->getCoordinates())
                    ->map(function (array $coordinates, int $key) use ($shape) {
                        return [
                            'shape_id' => $shape->shape_id,
                            'shape_pt_lat' => $coordinates[1],
                            'shape_pt_lon' => $coordinates[0],
                            'shape_pt_sequence' => $key + 1,
                            // Add +1 to key, to make sure it starts at 1 and not 0
                        ];
                    })
                    ->toArray();
            })
            ->flatten(1);
    }

    public function headings(): array
    {
        return [
            'shape_id',
            'shape_pt_lat',
            'shape_pt_lon',
            'shape_pt_sequence',
        ];
    }
}
