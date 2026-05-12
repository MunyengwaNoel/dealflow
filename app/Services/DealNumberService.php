<?php

namespace App\Services;

use App\Models\Deal;

class DealNumberService
{
    public function next(int $tenantId): string
    {
        $prefix = 'DL-'.$tenantId.'-';
        $last = Deal::query()
            ->forTenant($tenantId)
            ->where('deal_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('deal_number');
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
