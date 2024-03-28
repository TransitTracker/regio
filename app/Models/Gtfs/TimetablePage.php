<?php

namespace App\Models\Gtfs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kra8\Snowflake\HasShortflakePrimary;

class TimetablePage extends Model
{
    use HasFactory, HasShortflakePrimary;

    protected $table = 'gtfs_timetable_pages';
    protected $primaryKey = 'timetable_page_id';
    protected $guarded = [];

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class, 'timetable_page_id', 'timetable_page_id');
    }
}
