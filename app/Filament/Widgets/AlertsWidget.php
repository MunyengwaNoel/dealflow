<?php

namespace App\Filament\Widgets;

use App\Models\Document;
use App\Models\Invoice;
use Filament\Widgets\Widget;

class AlertsWidget extends Widget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 1;

    protected static string $view = 'filament.widgets.alerts-widget';

    protected static ?string $pollingInterval = '60s';

    public function getViewData(): array
    {
        $expiringDocs = Document::query()
            ->with('client')
            ->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', now()->addDays(30)->toDateString())
            ->whereDate('expiry_date', '>=', now()->toDateString())
            ->orderBy('expiry_date')
            ->limit(5)
            ->get();

        $overdueInvoices = Invoice::query()
            ->with('client')
            ->where('status', 'overdue')
            ->where('amount_due', '>', 0)
            ->orderByDesc('amount_due')
            ->limit(4)
            ->get();

        return [
            'expiringDocs'    => $expiringDocs,
            'overdueInvoices' => $overdueInvoices,
        ];
    }
}
