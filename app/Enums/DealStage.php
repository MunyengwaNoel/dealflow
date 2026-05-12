<?php

namespace App\Enums;

use Mokhosh\FilamentKanban\Concerns\IsKanbanStatus;

enum DealStage: string
{
    use IsKanbanStatus;

    case Lead = 'lead';
    case FollowUp = 'follow_up';
    case Proposal = 'proposal';
    case Negotiation = 'negotiation';
    case Won = 'won';
    case Lost = 'lost';

    public function getTitle(): string
    {
        return match ($this) {
            self::Lead => 'Lead',
            self::FollowUp => 'Follow Up',
            self::Proposal => 'Proposal',
            self::Negotiation => 'Negotiation',
            self::Won => 'Won',
            self::Lost => 'Lost',
        };
    }
}
