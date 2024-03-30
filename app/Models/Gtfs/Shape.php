<?php

namespace App\Models\Gtfs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Kra8\Snowflake\HasShortflakePrimary;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Shape extends Model
{
    use HasFactory, HasSpatial, HasShortflakePrimary;

    protected $table = 'gtfs_shapes';
    protected $primaryKey = 'shape_id';
    protected $guarded = [];

    protected $casts = [
        'shape' => LineString::class,
    ];

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id', 'agency_id');
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'shape_id', 'shape_id');
    }

    public function oneTrip(): HasOne
    {
        return $this->hasOne(Trip::class, 'shape_id', 'shape_id')->ofMany('trip_id');
    }
}
