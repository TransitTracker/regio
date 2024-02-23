<?php

namespace App\Models\Gtfs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Shape extends Model
{
    use HasFactory, HasSpatial;

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
}
