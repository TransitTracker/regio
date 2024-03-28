<?php

namespace App\Models\Gtfs;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kra8\Snowflake\HasShortflakePrimary;

class Calendar extends Model
{
    use HasFactory, HasShortflakePrimary;

    protected $table = 'gtfs_services';
    protected $primaryKey = 'service_id';
    protected $guarded = [];

    protected $casts = [
        'monday' => 'boolean',
        'tuesday' => 'boolean',
        'wednesday' => 'boolean',
        'thursday' => 'boolean',
        'friday' => 'boolean',
        'saturday' => 'boolean',
        'sunday' => 'boolean',
    ];

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id', 'agency_id');
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'service_id', 'service_id');
    }

    protected function servicePattern(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes): string {
                return implode('', [
                    $attributes['monday'] ? 'L' : '.',
                    $attributes['tuesday'] ? 'M' : '.',
                    $attributes['wednesday'] ? 'M' : '.',
                    $attributes['thursday'] ? 'J' : '.',
                    $attributes['friday'] ? 'V' : '.',
                    $attributes['saturday'] ? 'S' : '.',
                    $attributes['sunday'] ? 'D' : '.',
                ]);
            },
        );
    }
}
