<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make(__('Download / print'))
                ->icon('heroicon-m-arrow-down-tray')
                ->button()
                ->actions([
                    Actions\Action::make('print')
                        ->label(__('Print'))
                        ->icon('heroicon-o-printer')
                        ->url(fn (): string => route('documents.invoice.print', $this->record).'?autoprint=1')
                        ->openUrlInNewTab(),
                    Actions\Action::make('pdf')
                        ->label(__('PDF'))
                        ->icon('heroicon-o-document-arrow-down')
                        ->url(fn (): string => route('documents.invoice.pdf', $this->record))
                        ->openUrlInNewTab(),
                    Actions\Action::make('csv')
                        ->label(__('CSV'))
                        ->icon('heroicon-o-table-cells')
                        ->url(fn (): string => route('documents.invoice.csv', $this->record))
                        ->openUrlInNewTab(),
                    Actions\Action::make('xlsx')
                        ->label(__('Excel'))
                        ->icon('heroicon-o-document-chart-bar')
                        ->url(fn (): string => route('documents.invoice.xlsx', $this->record))
                        ->openUrlInNewTab(),
                ]),
            Actions\DeleteAction::make(),
        ];
    }
}
