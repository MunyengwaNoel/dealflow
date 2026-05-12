<?php

namespace App\Enums;

enum OrderItemStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
}
