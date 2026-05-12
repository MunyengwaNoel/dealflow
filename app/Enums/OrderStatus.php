<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Draft = 'draft';
    case Quoted = 'quoted';
    case Accepted = 'accepted';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
