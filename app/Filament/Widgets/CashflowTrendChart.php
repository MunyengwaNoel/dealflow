<?php

namespace App\Filament\Widgets;

use App\Models\CashflowEntry;
use Filament\Widgets\ChartWidget;

class CashflowTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Cashflow trend (30 days)';

    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        $tenant = app('tenant');

        return $tenant && $tenant->isPro();
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $labels = [];
        $income = [];
        $expense = [];

        for ($i = 29; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $labels[] = now()->subDays($i)->format('M j');
            $income[] = (float) CashflowEntry::query()
                ->whereDate('entry_date', $day)
                ->where('entry_type', 'income')
                ->sum('amount');
            $expense[] = (float) CashflowEntry::query()
                ->whereDate('entry_date', $day)
                ->where('entry_type', 'expense')
                ->sum('amount');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Income',
                    'data' => $income,
                    'borderColor' => '#16a34a',
                    'backgroundColor' => 'rgba(22,163,74,0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Expense',
                    'data' => $expense,
                    'borderColor' => '#dc2626',
                    'backgroundColor' => 'rgba(220,38,38,0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
