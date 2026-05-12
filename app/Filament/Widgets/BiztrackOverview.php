<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Deal;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\Quote;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BiztrackOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $tenant = app('tenant');
        if (! $tenant) {
            return [];
        }

        $expiringDocs = Document::query()
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', now()->addDays(30)->toDateString())
            ->whereDate('expiry_date', '>=', now()->toDateString())
            ->count();

        $stats = [
            Stat::make('Clients', (string) Client::query()->count()),
            Stat::make('Documents expiring (30d)', (string) $expiringDocs),
            Stat::make('Outstanding invoices', (string) Invoice::query()
                ->whereIn('status', ['sent', 'partial', 'overdue'])
                ->where('amount_due', '>', 0)
                ->count()),
        ];

        if ($tenant->isPro()) {
            $stats[] = Stat::make('Open quotes', (string) Quote::query()
                ->whereNotIn('status', ['declined', 'invoiced'])
                ->count());
            $stats[] = Stat::make('Open deals', (string) Deal::query()
                ->whereNotIn('stage', ['won', 'lost'])
                ->count());
        }

        return $stats;
    }
}
