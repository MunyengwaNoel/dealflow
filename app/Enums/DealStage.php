<?php

namespace App\Enums;

use Mokhosh\FilamentKanban\Concerns\IsKanbanStatus;

enum DealStage: string
{
    use IsKanbanStatus;

    case Lead = 'lead';
    case Potential = 'potential';
    case Quoted = 'quoted';
    case Negotiation = 'negotiation';
    case Won = 'won';
    case Lost = 'lost';

    public function getTitle(): string
    {
        return match ($this) {
            self::Lead => 'Lead',
            self::Potential => 'Potential',
            self::Quoted => 'Quoted',
            self::Negotiation => 'Negotiation',
            self::Won => 'Won',
            self::Lost => 'Lost',
        };
    }
}
