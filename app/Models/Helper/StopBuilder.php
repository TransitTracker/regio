<?php

namespace App\Models\Helper;

use App\Enums\StopType;
use App\Models\Gtfs\Stop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StopBuilder extends Model
{
    use HasFactory;

    protected $table = 'helper_stop_builders';

    protected $casts = [
        'stop_type' => StopType::class,
    ];
    protected $guarded = [];

    public function stop(): BelongsTo
    {
        return $this->belongsTo(Stop::class, 'stop_id', 'stop_id');
    }

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Odonym::class, 'stop_city', 'municipality');
    }

    public function mainStreet(): BelongsTo
    {
        return $this->belongsTo(Odonym::class, 'main_street_id', 'topo_id');
    }

    public function crossStreet(): BelongsTo
    {
        return $this->belongsTo(Odonym::class, 'cross_street_id', 'topo_id');
    }
}
