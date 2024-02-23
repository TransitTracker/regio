<?php

namespace App\Enums;

enum RouteType: int
{
    case Tram = 0;
    case Subway = 1;
    case Rail = 2;
    case Bus = 3;
    case Ferry = 4;
    case CableTram = 5;
    case AerialLift = 6;
    case Funicular = 7;
    case Trolleybus = 11;
    case Monorail = 12;

    public static function options(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_column(self::cases(), 'name'),
        );
    }

    public function icon(): string
    {
        return match ($this->value) {
            0 => 'gmdi-tram',
            1 => 'gmdi-subway',
            2 => 'gmdi-train',
            3 => 'gmdi-directions-bus',
            4 => 'gmdi-directions-boat',
        };
    }
}
