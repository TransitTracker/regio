<?php

namespace App\Models\Gtfs;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Kra8\Snowflake\HasShortflakePrimary;

class Trip extends Model
{
    use HasFactory, HasShortflakePrimary;

    protected $table = 'gtfs_trips';
    protected $primaryKey = 'trip_id';
    protected $guarded = [];

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id', 'agency_id');
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'route_id', 'route_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Calendar::class, 'service_id', 'service_id');
    }

    public function shape(): BelongsTo
    {
        return $this->belongsTo(Shape::class, 'shape_id', 'shape_id');
    }

    public function stopTimes(): HasMany
    {
        return $this->hasMany(StopTime::class, 'trip_id', 'trip_id');
    }

    public function firstDeparture(): HasOne
    {
        return $this->hasOne(StopTime::class, 'trip_id', 'trip_id')->ofMany('stop_sequence', 'MIN');
    }

    public function replicateWithStopTimes(string $startingTime, ?string $newShortName, bool $inverse = false): self
    {
        // Calculate minutes difference between original trip and new
        // Important to set time as in same day, so if a startingTime is earlier than the current trip, the difference is negative
        $this->loadMissing('firstDeparture');
        $minutesDiff = Carbon::parse($this->firstDeparture->arrival_time)
            ->setDate(2000, 1, 1)
            ->diffInMinutes(Carbon::parse($startingTime)->setDate(2000, 1, 1), false);

        $this->loadMissing('stopTimes');
        $stopTimes = $this->stopTimes;

        // Clone existing trip
        $clone = $this->replicate();

        if (filled($newShortName)) {
            $clone->trip_short_name = $newShortName;
        }

        // If inverse, remove some attributes
        if ($inverse) {
            $clone->direction_id = !$this->direction_id;
            $clone->trip_headsign = null;
            $clone->shape_id = null;
            // Inverse ordering of stop times
            $stopTimes = $stopTimes->sortByDesc('stop_sequence');
        }

        $clone->push();

        $newStopSequence = 1;

        foreach ($stopTimes as $stopTime) {
            $clonedStopTime = $stopTime->replicate();

            if ($inverse) {
                $clonedStopTime->stop_sequence = $newStopSequence;
            }

            // Calculate new time for each stop
            // Do not calculate for inverse, only use the new starting time
            $newTime = $inverse ? $startingTime : (new Carbon($stopTime->arrival_time))->addMinutes($minutesDiff);

            $clonedStopTime->arrival_time = $newTime;
            $clonedStopTime->departure_time = $newTime;
            $clone->stopTimes()->save($clonedStopTime);

            $newStopSequence += 1;
        }

        return $clone;
    }
}
