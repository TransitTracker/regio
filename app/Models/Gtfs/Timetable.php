<?php

namespace App\Models\Gtfs;

use App\Enums\TimetableOrientation;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kra8\Snowflake\HasShortflakePrimary;

class Timetable extends Model
{
    use HasFactory, HasShortflakePrimary;

    protected $table = 'gtfs_timetables';
    protected $primaryKey = 'timetable_id';
    protected $guarded = [];

    protected $casts = [
        'orientation' => TimetableOrientation::class,
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'route_id', 'route_id');
    }

    public function timetablePage(): BelongsTo
    {
        return $this->belongsTo(TimetablePage::class, 'timetable_page_id', 'timetable_page_id');
    }

    protected function servicePattern(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes): string {
                return implode('', [
                    $attributes['monday'] ? 'L' : '.',
                    $attributes['tuesday'] ? 'M' : '.',
                    $attributes['wednesday'] ? 'M' : '.',
                    $attributes['thursday'] ? 'J' : '.',
                    $attributes['friday'] ? 'V' : '.',
                    $attributes['saturday'] ? 'S' : '.',
                    $attributes['sunday'] ? 'D' : '.',
                ]);
            },
        );
    }
}
