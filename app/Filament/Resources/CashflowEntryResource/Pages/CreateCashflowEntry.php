<?php

namespace App\Filament\Resources\CashflowEntryResource\Pages;

use App\Filament\Resources\CashflowEntryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCashflowEntry extends CreateRecord
{
    protected static string $resource = CashflowEntryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['recorded_by'] = auth()->id();
        $data['client_id'] = $data['client_id'] ?: null;
        $data['invoice_id'] = $data['invoice_id'] ?: null;

        return $data;
    }
}
