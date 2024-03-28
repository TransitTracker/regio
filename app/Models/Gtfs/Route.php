<?php

namespace App\Models\Gtfs;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Kra8\Snowflake\HasShortflakePrimary;

class Route extends Model
{
    use HasFactory, HasShortflakePrimary;

    protected $table = 'gtfs_routes';
    protected $primaryKey = 'route_id';
    protected $guarded = [];

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id', 'agency_id');
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'route_id', 'route_id');
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class, 'route_id', 'route_id');
    }

    public function timetablePages(): HasManyThrough
    {
        return $this->hasManyThrough(
            TimetablePage::class,
            Timetable::class,
            'route_id',
            'timetable_page_id',
            'route_id',
            'timetable_page_id'
        )->distinct();
    }

    protected function routeName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->route_short_name} {$this->route_long_name}",
        );
    }
}
