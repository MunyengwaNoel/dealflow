<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AlertsWidget;
use App\Filament\Widgets\BiztrackOverview;
use App\Filament\Widgets\CashflowTrendChart;
use App\Filament\Widgets\RecentInvoicesWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort    = -1;

    protected function getHeaderWidgets(): array
    {
        return [
            BiztrackOverview::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            CashflowTrendChart::class,
            AlertsWidget::class,
            RecentInvoicesWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }

    public function getHeaderWidgetsColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'xl' => 5,
        ];
    }
}
