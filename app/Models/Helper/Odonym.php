<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Odonym extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'helper_odonyms';
}
