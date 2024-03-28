<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum LocationType: int implements HasIcon, HasLabel
{
    case StopPlatform = 0;
    case Station = 1;
    case EntranceExit = 2;
    case GenericNode = 3;
    case BoardingArea = 4;

    public function getIcon(): ?string
    {
        return match ($this->value) {
            0 => 'mdi-bus-stop',
            1 => 'mdi-bus-stop-covered',
            2 => 'mdi-location-exit',
            4 => 'mdi-texture-box',
            default => null,
        };
    }

    public function getLabel(): ?string
    {
        return match ($this->value) {
            0 => 'Stop / Platform',
            1 => 'Station',
            2 => 'Entrance / Exit',
            3 => 'Generic Node',
            4 => 'Boarding Area',
        };
    }
}
