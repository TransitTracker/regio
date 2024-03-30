<?php

namespace App\Models\Gtfs;

use App\Models\Helper\StopBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Kra8\Snowflake\HasShortflakePrimary;
use Kra8\Snowflake\HasSnowflakePrimary;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Stop extends Model
{
    use HasFactory, HasSpatial, HasShortflakePrimary;

    protected $table = 'gtfs_stops';
    protected $primaryKey = 'stop_id';
    protected $guarded = [];

    protected $casts = [
        'stop_position' => Point::class,
    ];

    /*public function stopAttribute(): BelongsTo
    {
        return $this->belongsTo(StopAttribute::class, 'stop_id', 'stop_id');
    }*/

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id', 'agency_id');
    }

    public function stopTimes(): HasMany
    {
        return $this->hasMany(StopTime::class, 'stop_id', 'stop_id');
    }

    public function trips(): HasManyThrough
    {
        return $this->hasManyThrough(Trip::class, StopTime::class, 'stop_id', 'trip_id', 'stop_id', 'trip_id');
    }

    public function stopBuilder(): HasOne
    {
        return $this->hasOne(StopBuilder::class, 'stop_id', 'stop_id');
    }

    public function parentStation(): BelongsTo
    {
        return $this->belongsTo(self::class, 'stop_id', 'parent_station');
    }

    public function toMapboxWaypoint(): string
    {
        return "{$this->stop_position->longitude},{$this->stop_position->latitude}";
    }

    protected function stopLat(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stop_position?->latitude,
        );
    }

    protected function stopLon(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stop_position?->longitude,
        );
    }
}
