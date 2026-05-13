<?php

namespace App\Filament\Resources\CashflowEntryResource\Pages;

use App\Filament\Resources\CashflowEntryResource;
use App\Filament\Resources\CashflowEntryResource\Widgets\CashflowTotalsOverview;
use App\Models\CashflowEntry;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListCashflowEntries extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = CashflowEntryResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('All')),
            'income' => Tab::make(__('Income'))
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('entry_type', 'income')),
            'expenses' => Tab::make(__('Expenses'))
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('entry_type', 'expense')),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CashflowTotalsOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportCsv')
                ->label(__('Export CSV'))
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (): StreamedResponse {
                    $filename = 'cashflow-entries-'.now()->format('Y-m-d-His').'.csv';

                    return response()->streamDownload(function (): void {
                        $handle = fopen('php://output', 'w');
                        if ($handle === false) {
                            return;
                        }
                        fwrite($handle, "\xEF\xBB\xBF");
                        fputcsv($handle, [
                            'entry_type',
                            'category',
                            'description',
                            'amount',
                            'payment_method',
                            'entry_date',
                            'reference',
                            'client',
                            'invoice',
                            'recorded_by',
                        ]);

                        $this->getFilteredTableQuery()
                            ->with(['client', 'invoice', 'recordedBy'])
                            ->orderByDesc('entry_date')
                            ->orderByDesc('id')
                            ->chunk(500, function ($rows) use ($handle): void {
                                foreach ($rows as $entry) {
                                    /** @var CashflowEntry $entry */
                                    fputcsv($handle, [
                                        $entry->entry_type,
                                        $entry->category,
                                        $entry->description,
                                        $entry->amount,
                                        $entry->payment_method,
                                        optional($entry->entry_date)->toDateString(),
                                        $entry->reference,
                                        $entry->client?->name,
                                        $entry->invoice?->invoice_number,
                                        $entry->recordedBy?->name,
                                    ]);
                                }
                            });

                        fclose($handle);
                    }, $filename, [
                        'Content-Type' => 'text/csv; charset=UTF-8',
                    ]);
                }),
            Actions\CreateAction::make(),
        ];
    }
}
