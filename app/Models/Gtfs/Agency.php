<?php

namespace App\Models\Gtfs;

use App\Models\User;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Kra8\Snowflake\HasShortflakePrimary;

class Agency extends Model implements HasName, HasAvatar
{
    use HasFactory, HasShortflakePrimary;

    protected $guarded = [];

    protected $table = 'gtfs_agencies';
    protected $primaryKey = 'agency_id';

    public function routes(): HasMany
    {
        return $this->hasMany(Route::class, 'agency_id', 'agency_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Calendar::class, 'agency_id', 'agency_id');
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'agency_id', 'agency_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'agency_user', 'agency_id', 'user_id');
    }

    public function getFilamentName(): string
    {
        return $this->agency_name;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return Storage::url($this->logo);
    }
}
