<?php

namespace App\Services;

use App\Models\Order;

class OrderNumberService
{
    public function next(int $tenantId): string
    {
        $year = now()->year;
        $prefix = 'ORD-'.$year.'-';

        $last = Order::query()
            ->where('tenant_id', $tenantId)
            ->where('order_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('order_number');

        $n = 1;
        if ($last && strlen($last) > strlen($prefix)) {
            $suffix = substr($last, strlen($prefix));
            if (ctype_digit($suffix)) {
                $n = ((int) $suffix) + 1;
            }
        }

        return $prefix.str_pad((string) $n, 4, '0', STR_PAD_LEFT);
    }
}
