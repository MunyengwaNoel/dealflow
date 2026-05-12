<?php

namespace App\Enums;

enum DealPriority: string
{
    case Hot = 'hot';
    case Warm = 'warm';
    case Cold = 'cold';
    case Dead = 'dead';

    public function label(): string
    {
        return match ($this) {
            self::Hot => 'Hot',
            self::Warm => 'Warm',
            self::Cold => 'Cold',
            self::Dead => 'Dead',
        };
    }

    public function emoji(): string
    {
        return match ($this) {
            self::Hot => '🔥',
            self::Warm => '⭐',
            self::Cold => '❄️',
            self::Dead => '💀',
        };
    }
}
