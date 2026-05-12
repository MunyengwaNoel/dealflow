<?php

namespace App\Services;

use App\Models\Quote;

class QuoteNumberService
{
    /**
     * QT-YYYY-NNNN per tenant (never reset by year in sequence — uses max numeric suffix for year prefix).
     */
    public function nextQtFormat(int $tenantId): string
    {
        $year = now()->year;
        $prefix = 'QT-'.$year.'-';

        $last = Quote::query()
            ->where('tenant_id', $tenantId)
            ->where('quote_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('quote_number');

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
