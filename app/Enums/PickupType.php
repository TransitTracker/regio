<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PickupType: int implements HasLabel, HasIcon, HasColor
{
    case Regular = 0;
    case NoPickup = 1;
    case MustPhone = 2;
    case MustCoordinateDriver = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Regular => 'Regularly scheduled',
            self::NoPickup => 'No pickup',
            self::MustPhone => 'Must phone agency',
            self::MustCoordinateDriver => 'Must coordinate with driver',
        };
    }
    public function getIcon(): ?string
    {
        return match ($this) {
            self::Regular => 'mdi-check',
            self::NoPickup => 'mdi-close',
            self::MustPhone => 'mdi-phone',
            self::MustCoordinateDriver => 'mdi-account',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Regular => null,
            self::NoPickup => 'danger',
            self::MustPhone => 'warning',
            self::MustCoordinateDriver => 'warning',
        };
    }
}
