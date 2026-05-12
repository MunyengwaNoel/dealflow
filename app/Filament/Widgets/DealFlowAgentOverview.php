<?php

namespace App\Filament\Widgets;

use App\Enums\DealStage;
use App\Enums\OrderStatus;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Quote;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DealFlowAgentOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '30s';

    protected function getTenant()
    {
        return app('tenant') ?? auth()->user()?->tenant;
    }

    protected function getStats(): array
    {
        $tenant = $this->getTenant();

        if (! $tenant) {
            return [];
        }

        $totalClients = Client::query()->count();
        $prevClients = Client::query()
            ->where('created_at', '<', now()->subDays(30))
            ->count();
        $clientTrend = $prevClients > 0
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

        $weekRevenue = Invoice::query()
            ->where('status', 'paid')
            ->where('paid_date', '>=', now()->startOfWeek()->toDateString())
            ->sum('amount_paid');

        $hotDeals = Deal::query()
            ->where('stage', DealStage::Quoted->value)
            ->where(function ($q) {
                $q->where('quote_was_opened', false)
                    ->orWhere(function ($q2) {
                        $q2->where('quote_was_opened', true)
                            ->where('updated_at', '<', now()->subDays(5));
                    });
            })
            ->count();

        $newQuotes = Quote::query()
            ->whereIn('status', ['draft', 'sent'])
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $draftOrders = Order::query()->where('status', OrderStatus::Draft)->count();

        $stats = [
            Stat::make('Hot pipeline', number_format($hotDeals))
                ->description('Quoted deals needing attention')
                ->descriptionIcon('heroicon-m-fire')
                ->color($hotDeals > 0 ? 'danger' : 'success'),

            Stat::make('Revenue this week', '$'.number_format((float) $weekRevenue, 0))
                ->description('Recorded payments')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),

            Stat::make('New quotes (7d)', number_format($newQuotes))
                ->description('Draft + sent')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),

            Stat::make('Draft orders', number_format($draftOrders))
                ->description('Wizard in progress')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('warning'),

            Stat::make('Total clients', number_format($totalClients))
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

            Stat::make('Outstanding invoices', number_format($outstanding))
                ->description('$'.number_format((float) $outstandingAmount, 2).' due')
                ->descriptionIcon($outstanding > 0
                    ? 'heroicon-m-exclamation-circle'
                    : 'heroicon-m-check-circle')
                ->color($outstanding > 5 ? 'danger' : ($outstanding > 0 ? 'warning' : 'success')),

            Stat::make('Docs expiring (30d)', number_format($expiringDocs))
                ->description($expiringDocs > 0 ? 'Action required' : 'All documents current')
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
                ->whereNotIn('stage', [DealStage::Won->value, DealStage::Lost->value])
                ->count();

            $wonDealsThisMonth = Deal::query()
                ->where('stage', DealStage::Won->value)
                ->whereMonth('updated_at', now()->month)
                ->count();

            $stats[] = Stat::make('Open quotes', number_format($openQuotes))
                ->description('Awaiting response')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info');

            $stats[] = Stat::make('Active deals', number_format($openDeals))
                ->description("{$wonDealsThisMonth} won this month")
                ->descriptionIcon('heroicon-m-trophy')
                ->color('primary');
        }

        return $stats;
    }
}
