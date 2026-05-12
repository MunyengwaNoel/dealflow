<?php

namespace App\Filament\Resources\CashflowEntryResource\Pages;

use App\Filament\Resources\CashflowEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCashflowEntry extends EditRecord
{
    protected static string $resource = CashflowEntryResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['client_id'] = $data['client_id'] ?: null;
        $data['invoice_id'] = $data['invoice_id'] ?: null;

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
