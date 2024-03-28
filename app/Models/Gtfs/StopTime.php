<?php

namespace App\Models\Gtfs;

use App\Enums\DropOffType;
use App\Enums\PickupType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StopTime extends Model
{
    use HasFactory;

    protected $table = 'gtfs_stop_times';
    protected $guarded = [];
    protected $casts = [
        'pickup_type' => PickupType::class,
        'drop_off_type' => DropOffType::class,
    ];

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id', 'agency_id');
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'trip_id');
    }

    public function stop(): BelongsTo
    {
        return $this->belongsTo(Stop::class, 'stop_id', 'stop_id');
    }
}
