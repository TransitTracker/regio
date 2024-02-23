<?php

namespace App\Models\Gtfs;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Stop extends Model
{
    use HasFactory, HasSpatial;

    protected $table = 'gtfs_stops';
    protected $primaryKey = 'stop_id';
    protected $guarded = [];

    protected $casts = [
        'stop_position' => Point::class,
    ];

    public function stopTimes(): HasMany
    {
        /*
        projects            stops
            id - integer    stop_id
            name - string
        
        environments                stop_times
            id - integer            trip_id
            project_id - integer    stop_id
            name - string
        
        deployments                     trips
            id - integer
            environment_id - integer    trip_id
            commit_hash - string
        */
        return $this->hasMany(StopTime::class, 'stop_id', 'stop_id');
    }

    public function trips(): HasManyThrough
    {
        return $this->hasManyThrough(Trip::class, StopTime::class, 'stop_id', 'trip_id', 'stop_id', 'trip_id');
    }

    public function toMapboxWaypoint(): string
    {
        return "{$this->stop_position->longitude},{$this->stop_position->latitude}";
    }

    protected function stopLat(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stop_position->latitude,
        );
    }

    protected function stopLon(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stop_position->longitude,
        );
    }
}
