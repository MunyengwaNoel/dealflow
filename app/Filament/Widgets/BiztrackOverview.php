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

    protected static ?string $pollingInterval = '30s';

    protected function getTenant()
    {
        // Try the middleware-bound tenant first; fall back to the
        // authenticated user's own relationship (needed for Livewire poll
        // requests that bypass the Filament panel middleware stack).
        return app('tenant') ?? auth()->user()?->tenant;
    }

    protected function getStats(): array
    {
        $tenant = $this->getTenant();

        if (! $tenant) {
            return [];
        }

        $totalClients = Client::query()->count();
        $prevClients  = Client::query()
            ->where('created_at', '<', now()->subDays(30))
            ->count();
        $clientTrend  = $prevClients > 0
            ? round((($totalClients - $prevClients) / $prevClients) * 100, 1)
            : 0;

        $expiringDocs = Document::query()
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', now()->addDays(30)->toDateString())
            ->whereDate('expiry_date', '>=', now()->toDateString())
            ->count();

        $outstanding = Invoice::query()
            ->whereIn('status', ['sent', 'partial', 'overdue'])
            ->where('amount_due', '>', 0)
            ->count();

        $outstandingAmount = Invoice::query()
            ->whereIn('status', ['sent', 'partial', 'overdue'])
            ->where('amount_due', '>', 0)
            ->sum('amount_due');

        $stats = [
            Stat::make('Total Clients', number_format($totalClients))
                ->description($clientTrend >= 0
                    ? "+{$clientTrend}% from last 30 days"
                    : "{$clientTrend}% from last 30 days")
                ->descriptionIcon($clientTrend >= 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->color($clientTrend >= 0 ? 'success' : 'warning')
                ->chart(Client::query()
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                    ->where('created_at', '>=', now()->subDays(7))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->pluck('count')
                    ->toArray()),

            Stat::make('Outstanding Invoices', number_format($outstanding))
                ->description('$' . number_format($outstandingAmount, 2) . ' due')
                ->descriptionIcon($outstanding > 0
                    ? 'heroicon-m-exclamation-circle'
                    : 'heroicon-m-check-circle')
                ->color($outstanding > 5 ? 'danger' : ($outstanding > 0 ? 'warning' : 'success')),

            Stat::make('Docs Expiring (30d)', number_format($expiringDocs))
                ->description($expiringDocs > 0
                    ? 'Action required'
                    : 'All documents current')
                ->descriptionIcon($expiringDocs > 0
                    ? 'heroicon-m-document-minus'
                    : 'heroicon-m-document-check')
                ->color($expiringDocs > 0 ? 'warning' : 'success'),
        ];

        if ($tenant->isPro()) {
            $openQuotes = Quote::query()
                ->whereNotIn('status', ['declined', 'invoiced'])
                ->count();

            $openDeals = Deal::query()
                ->whereNotIn('stage', ['won', 'lost'])
                ->count();

            $wonDealsThisMonth = Deal::query()
                ->where('stage', 'won')
                ->whereMonth('updated_at', now()->month)
                ->count();

            $stats[] = Stat::make('Open Quotes', number_format($openQuotes))
                ->description('Awaiting response')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info');

            $stats[] = Stat::make('Active Deals', number_format($openDeals))
                ->description("{$wonDealsThisMonth} won this month")
                ->descriptionIcon('heroicon-m-trophy')
                ->color('primary');
        }

        return $stats;
    }
}
