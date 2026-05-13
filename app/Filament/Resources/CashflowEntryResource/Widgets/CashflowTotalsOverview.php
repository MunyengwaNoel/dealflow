<?php

namespace App\Filament\Resources\CashflowEntryResource\Widgets;

use App\Filament\Resources\CashflowEntryResource\Pages\ListCashflowEntries;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class CashflowTotalsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected static bool $isLazy = false;

    protected function getTablePage(): string
    {
        return ListCashflowEntries::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();
        $income = $this->sumForType($query, 'income');
        $expenses = $this->sumForType($query, 'expense');
        $net = $income - $expenses;

        return [
            Stat::make(__('Total income'), number_format($income, 2))
                ->description(__('Recorded in the current table scope (filters & tab).'))
                ->color('success'),
            Stat::make(__('Total expenses'), number_format($expenses, 2))
                ->description(__('Recorded in the current table scope (filters & tab).'))
                ->color('danger'),
            Stat::make(__('Net (profit)'), number_format($net, 2))
                ->description(__('Income minus expenses for the same scope.'))
                ->color($net >= 0 ? 'success' : 'danger'),
        ];
    }

    private function sumForType(Builder $base, string $type): float
    {
        /** @var Builder $clone */
        $clone = clone $base;

        return (float) $clone->where('entry_type', $type)->sum('amount');
    }
}
