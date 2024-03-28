<?php

namespace App\Models\Gtfs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kra8\Snowflake\HasShortflakePrimary;

class Frequency extends Model
{
    use HasFactory, HasShortflakePrimary;

    protected $table = 'gtfs_frequencies';
    protected $primaryKey = 'trip_id';
    protected $guarded = [];

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id', 'agency_id');
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'trip_id');
    }
}
