<?php

namespace App\Filament\Resources\CashflowEntryResource\Pages;

use App\Filament\Resources\CashflowEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCashflowEntries extends ListRecords
{
    protected static string $resource = CashflowEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
