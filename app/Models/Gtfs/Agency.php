<?php

namespace App\Models\Gtfs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agency extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'gtfs_agencies';
    protected $primaryKey = 'agency_id';

    public function routes(): HasMany
    {
        return $this->hasMany(Route::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Calendar::class);
    }
}
