<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum StopType: int implements HasLabel
{
    case StreetCorner = 0;
    case FacingAddress = 1;
    case Place = 2;
    case Infrastructure = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::StreetCorner => 'Street Corner',
            self::FacingAddress => 'Facing Address',
            self::Place => 'Place',
            self::Infrastructure => 'Infrastructure',
        };
    }
}
