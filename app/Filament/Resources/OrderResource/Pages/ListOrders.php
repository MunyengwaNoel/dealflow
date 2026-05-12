<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Pages\OrderWizardPage;
use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('new_order')
                ->label('New order wizard')
                ->url(OrderWizardPage::getUrl())
                ->icon('heroicon-o-sparkles')
                ->color('primary'),
        ];
    }
}
