<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\CashflowEntry;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\Quote;

class DashboardController extends ApiController
{
    public function show()
    {
        $tenant = app('tenant');

        return $this->successResponse([
            'clients_total' => Client::query()->count(),
            'documents_expiring_soon' => Document::query()
                ->whereNotNull('expiry_date')
                ->whereDate('expiry_date', '<=', now()->addDays(30)->toDateString())
                ->whereDate('expiry_date', '>=', now()->toDateString())
                ->count(),
            'quotes_open' => Quote::query()->whereNotIn('status', ['declined', 'invoiced'])->count(),
            'invoices_outstanding' => Invoice::query()->whereIn('status', ['sent', 'partial', 'overdue'])->where('amount_due', '>', 0)->count(),
            'deals_open' => Deal::query()->whereNotIn('stage', ['won', 'lost'])->count(),
            'cashflow_30d' => CashflowEntry::query()
                ->whereDate('entry_date', '>=', now()->subDays(30)->toDateString())
                ->selectRaw("SUM(CASE WHEN entry_type = 'income' THEN amount ELSE -amount END) as net")
                ->value('net'),
            'plan' => $tenant?->plan,
        ], 'OK');
    }
}
