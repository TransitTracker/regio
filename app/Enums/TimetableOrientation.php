<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TimetableOrientation: string
{
    case Vertical = 'vertical';
    case Horizontal = 'horizontal';
    case Hourly = 'hourly';
}
