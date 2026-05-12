<?php

namespace App\Filament\Widgets;

use App\Models\CashflowEntry;
use Filament\Widgets\ChartWidget;

class CashflowTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Cashflow Trend';

    protected static ?string $description = 'Income vs expenses over the last 30 days';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 2;

    public static function canView(): bool
    {
        $tenant = app('tenant') ?? auth()->user()?->tenant;

        return $tenant && $tenant->isPro();
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                    'labels'   => [
                        'usePointStyle' => true,
                        'padding'       => 16,
                        'font'          => ['size' => 12, 'weight' => '600'],
                    ],
                ],
                'tooltip' => [
                    'mode'      => 'index',
                    'intersect' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'grid'  => ['display' => false],
                    'ticks' => ['font' => ['size' => 11]],
                ],
                'y' => [
                    'grid'    => ['color' => 'rgba(0,0,0,0.04)'],
                    'ticks'   => [
                        'callback' => "function(v){return '$'+v.toLocaleString()}",
                        'font'     => ['size' => 11],
                    ],
                    'beginAtZero' => true,
                ],
            ],
            'interaction' => [
                'mode'      => 'nearest',
                'axis'      => 'x',
                'intersect' => false,
            ],
            'elements' => [
                'line'  => ['tension' => 0.4],
                'point' => ['radius' => 3, 'hoverRadius' => 6],
            ],
        ];
    }

    protected function getData(): array
    {
        $labels  = [];
        $income  = [];
        $expense = [];

        for ($i = 29; $i >= 0; $i--) {
            $day       = now()->subDays($i)->toDateString();
            $labels[]  = now()->subDays($i)->format('M j');
            $income[]  = (float) CashflowEntry::query()
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
                    'label'           => 'Income',
                    'data'            => $income,
                    'borderColor'     => '#10b981',
                    'backgroundColor' => 'rgba(16,185,129,0.08)',
                    'borderWidth'     => 2.5,
                    'fill'            => true,
                    'pointBackgroundColor' => '#10b981',
                ],
                [
                    'label'           => 'Expenses',
                    'data'            => $expense,
                    'borderColor'     => '#f43f5e',
                    'backgroundColor' => 'rgba(244,63,94,0.06)',
                    'borderWidth'     => 2.5,
                    'fill'            => true,
                    'pointBackgroundColor' => '#f43f5e',
                ],
            ],
            'labels' => $labels,
        ];
    }
}
